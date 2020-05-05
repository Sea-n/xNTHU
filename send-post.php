<?php
if (!isset($argv))
	exit('Please run from command line.');

require_once('utils.php');
require_once('database.php');
require_once('config.php');
require_once('telegram-bot/class.php');
$db = new MyDB();
$TG = new Telegram();

$cmd = $argv[1] ?? '';
if ($cmd == 'update') {
	if (!isset($argv[2]))
		exit('No ID.');

	$id = $argv[2];
	$post = $db->getPostById($id);

	update_telegram($post);
	exit;
}


/* Check unfinished post */
$posts = $db->getPosts(1);
if (isset($posts[0]) && $posts[0]['status'] == 4)
	$post = $posts[0];

/* Get all pending submissions, oldest first */
if (!isset($post)) {
	$submissions = $db->getSubmissions(0, false);

	foreach ($submissions as $item) {
		if (checkEligible($item)) {
			$post = $db->setPostId($item['uid']);
			break;
		}
	}
}

if (!isset($post))
	exit;


/* Prepare post content */
assert(isset($post['id']));
$id = $post['id'];
$uid = $post['uid'];
$body = $post['body'];

/* img cannot be URL, Twitter required local file upload */
$img = $post['has_img'] ? $uid : '';

$time = strtotime($post['created_at']);
$time = date("Y 年 m 月 d 日 H:i", $time);
$link = "https://x.nthu.io/post/$id";

/* Send post to every SNS */
$sns = [
	'Telegram' => 'telegram',
	'Facebook' => 'facebook',
];
foreach ($sns as $name => $key) {
	try {
		$func = "send_$key";
		if (isset($post["{$key}_id"]) && ($post["{$key}_id"] > 0 || strlen($post["{$key}_id"]) > 1))
			continue;

		$pid = $func($id, $body, $img);

		if ($pid <= 0) { // Retry limit exceed
			$dt = time() - strtotime($post['posted_at']);
			if ($dt > 3*5*60) // Total 3 times
				$pid = 1;
		}

		if ($pid > 0)
			$db->updatePostSns($id, $key, $pid);
		$post["{$key}_id"] = $pid;
	} catch (Exception $e) {
		echo "Send $name Error " . $e->getCode() . ': ' .$e->getMessage() . "\n";
		echo $e->lastResponse . "\n\n";
	}
}

/* Update with link to other SNS */
$sns = [
	'Telegram' => 'telegram',
];
foreach ($sns as $name => $key) {
	try {
		$func = "update_$key";
		if (!isset($post["{$key}_id"]) || $post["{$key}_id"] < 0)
			continue;  // not posted, could be be edit

		$func($post);
	} catch (Exception $e) {
		echo "Edit $name Error " . $e->getCode() . ': ' .$e->getMessage() . "\n";
		echo $e->lastResponse . "\n\n";
	}
}

/* Remove vote keyboard in Telegram */
$msgs = $db->getTgMsgsByUid($uid);
foreach ($msgs as $item) {
	$TG->deleteMsg($item['chat_id'], $item['msg_id']);
	$db->deleteTgMsg($uid, $item['chat_id']);
}


function checkEligible(array $post): bool {
	/* Prevent publish demo post */
	if ($post['status'] != 3)
		return false;

	$dt = time() - strtotime($post['created_at']);
	$vote = $post['approvals'] - $post['rejects'];

	/* Rule for Logged-in users */
	if (!empty($post['author_id'])) {
		if ($dt < 4*60)
			return false;
		if ($vote < 0)
			return false;

		return true;
	}

	/* Rule for NTHU IP address */
	if ($post['author_name'] == '匿名, 清大' || $post['author_name'] == '匿名, 交大') {
		/* Night mode: 02:00 - 07:59 */
		if (2 <= idate('H') && idate('H') <= 7) {
			if ($vote < 3)
				return false;
		}

		/* Less than 10 min */
		if ($dt < 9*60)
			return false;

		/* 1hour - 2hour */
		if ($dt < 119*60 && $vote < 2)
			return false;

		/* More than 2 hour */
		if ($vote < 0)
			return false;

		return true;
	}

	/* Rule for Taiwan IP address */
	if (strpos($post['author_name'], '境外') === false) {
		/* If no reject & more than 10 min */
		if ($post['rejects'] == 0)
			if ($dt > 9*60 && $vote >= 5)
				return true;

		/* Less than 30 min */
		if ($dt < 29*60)
			return false;

		/* 30min - 2hour */
		 if ($dt < 119*60 && $vote < 7)
			 return false;

		 /* 2hour - 6hour */
		 if ($dt < 6*60*60 && $vote < 5)
			 return false;

		 /* More than 6 hour */
		 if ($vote < 3)
			 return false;

		 return true;
	}

	/* Rule for Foreign IP address */
	if (true) {
		if ($dt < 59*60)
			return false;

		if ($vote < 10)
			return false;

		return true;
	}
}

function send_telegram(int $id, string $body, string $img = ''): int {
	global $TG, $link;

	/* Check latest line */
	$lines = explode("\n", $body);
	$end = end($lines);
	$is_url = filter_var($end, FILTER_VALIDATE_URL);
	if (empty($img) && $is_url)
		$msg = "<a href='$end'>\x20\x0c</a>";
	else
		$msg = "";

	$msg .= "<a href='$link'>#靠清$id</a>\n\n" . enHTML($body);

	/* Send to @xNTHU */
	if (empty($img))
		$result = $TG->sendMsg([
			'chat_id' => '@xNTHU',
			'text' => $msg,
			'parse_mode' => 'HTML',
			'disable_web_page_preview' => !$is_url
		]);
	else
		$result = $TG->sendPhoto([
			'chat_id' => '@xNTHU',
			'photo' => "https://x.nthu.io/img/{$img}.jpg",
			'caption' => $msg,
			'parse_mode' => 'HTML',
		]);

	$tg_id = $result['result']['message_id'];

	return $tg_id;
}

function send_facebook(int $id, string $body, string $img = ''): int {
	global $link, $time;
	$msg = "#靠清$id\n\n";
	$msg .= "$body\n\n";
	$msg .= "投稿時間：$time\n";
	$msg .= "✅ $link";

	$URL = 'https://graph.facebook.com/v6.0/' . FB_PAGES_ID . (empty($img) ? '/feed' : '/photos');
   
	$data = ['access_token' => FB_ACCESS_TOKEN];
	if (empty($img)) {
		$data['message'] = $msg;

		$lines = explode("\n", $body);
		$end = end($lines);
		if (filter_var($end, FILTER_VALIDATE_URL) && strpos($end, 'facebook') === false)
			$data['link'] = $end;
	} else {
		$data['url'] = "https://x.nthu.io/img/$img.jpg";
		$data['caption'] = $msg;
	}

	$curl = curl_init();
	curl_setopt_array($curl, [
		CURLOPT_URL => $URL,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => $data
	]);

	$result = curl_exec($curl);
	curl_close($curl);
	$result = json_decode($result, true);

	$fb_id = $result['post_id'] ?? $result['id'] ?? '0_0';
	$post_id = (int) explode('_', $fb_id)[1];

	if ($post_id == 0) {
		echo "Facebook result error:";
		var_dump($result);
	}

	return $post_id;
}

function update_telegram(array $post) {
	global $TG;

	$TG->editMarkup([
		'chat_id' => '@xNTHU',
		'message_id' => $post['telegram_id'],
		'reply_markup' => [
			'inline_keyboard' => [
				[
					[
						'text' => 'Facebook',
						'url' => "https://www.facebook.com/xNTHU2.0/posts/{$post['facebook_id']}"
					],
				]
			]
		]
	]);
}
