<?php
session_start();
require_once('utils.php');
require_once('database.php');
$db = new MyDB();

if (($_SERVER['HTTP_CONTENT_TYPE'] ?? '') == 'application/json')
	$_POST = json_decode(file_get_contents('php://input'), true);

header('Content-Type: application/json');

/* HTTP Method: GET */
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$ACTION = $_GET['action'] ?? 'x';

	if ($ACTION == 'votes') {
		$uid = $_GET['uid'] ?? '';
		if (strlen($uid) != 4)
			err('uid invalid. 投稿編號無效');

		$result = ['ok' => true];

		$post = $db->getSubmissionByUid($uid);
		$result['approvals'] = (int) $post['approvals'];
		$result['rejects'] = (int) $post['rejects'];

		$result['votes'] = [];
		if (true || isset($_SESSION['nctu_id'])) {
			$votes = $db->getVotesByUid($uid);

			foreach ($votes as $item) {
				$id = $item['voter'];
				$user = $db->getUserByNctu($id);

				$result['votes'][] = [
					'vote' => (int) $item['vote'],
					'id' => $id,
					'dep' => idToDep($id),
					'name' => $user['name'],
					'reason' => $item['reason'],
				];
			}
		}

		echo json_encode($result, JSON_PRETTY_PRINT);
	} else {
		err('Unknown GET action. 未知的操作');
	}
}

/* HTTP Method: POST */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$ACTION = $_GET['action'] ?? 'x';

	if ($ACTION == 'submission') {
		/* Prepare post content */
		$body = $_POST['body'] ?? 'X';
		$body = str_replace("\r", "", $body);
		$body = preg_replace("#\n\s+\n#", "\n\n", $body);
		$body = trim($body);

		$has_img = (isset($_FILES['img']) && $_FILES['img']['size']);

		/* Check POST data */
		$error = checkSubmitData($body, $has_img);
		if (!empty($error))
			err($error);

		/*
		 * Generate UID in base58 space
		 *
		 * Caution: collision is not handled
		 */
		$uid = rand58(4);

		/* Upload Image */
		if ($has_img) {
			$error = uploadImage($uid);
			if (!empty($error))
				err($error);
		}

		$ip_addr = $_SERVER['REMOTE_ADDR'];

		/* Get Author Name */
		if (isset($_SESSION['nctu_id'])) {
			$USER = $db->getUserByNctu($_SESSION['nctu_id']);
			$author_id = $USER['nctu_id'];
			$author_name = $USER['name'];
			$author_photo = $USER['tg_photo'] ?? '';
		} else {
			$ip_from = ip_from($ip_addr);
			$author_id = '';
			$author_name = "匿名, $ip_from";
			$author_photo = '';
		}

		/* Insert record */
		$error = $db->insertSubmission($uid, $body, $has_img, $ip_addr, $author_id, $author_name, $author_photo);
		if ($error[0] != '00000')
			err("Database error {$error[0]}, {$error[1]}, {$error[2]}. 資料庫發生錯誤");


		/* Success, return post data */
		$ip_masked = ip_mask($ip_addr);
		if (empty($author_photo))
			$author_photo = genPic($ip_masked);

		echo json_encode([
			'ok' => true,
			'uid' => $uid,
			'body' => $body,
			'has_img' => $has_img,
			'ip_masked' => $ip_masked,
			'author_name' => $author_name,
			'author_photo' => $author_photo,
		], JSON_PRETTY_PRINT);
	} else if ($ACTION == 'vote') {
		if (!isset($_SESSION['nctu_id']))
			err('請先登入');

		$uid = $_POST['uid'] ?? '';
		if (strlen($uid) != 4)
			err('uid invalid. 投稿編號無效');

		$voter = $_SESSION['nctu_id'];

		$vote = $_POST['vote'] ?? 0;
		if ($vote != 1 && $vote != -1)
			err('vote invalid. 投票類型無效');

		$reason = $_POST['reason'] ?? '';
		$reason = trim($reason);
		if (mb_strlen($reason) < 5)
			err('附註請輸入 5 個字以上');
		if (mb_strlen($reason) > 100)
			err('附註請輸入 100 個字以內');

		$result = $db->voteSubmissions($uid, $voter, $vote, $reason);
		echo json_encode($result, JSON_PRETTY_PRINT);
	} else {
		err('Unknown POST action. 未知的操作');
	}
}

/* HTTP Method: DELETE */
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
	$ACTION = $_GET['action'] ?? 'x';

	if ($ACTION == 'submission') {
		$uid = $_POST['uid'] ?? '';
		if (strlen($uid) != 4)
			err("uid ($uid) invalid. 投稿編號無效");

		$post = $db->getSubmissionByUid($uid);
		if (!$post)
			err('找不到該篇投稿');

		$ts = strtotime($post['created_at']);
		$dt = time() - $ts;
		if ($dt > 5*60)
			err('已超出刪除期限，請來信聯絡開發團隊');

		if ($_SERVER['REMOTE_ADDR'] !== $post['ip_addr'])
			err('無法驗證身份：IP 位址不相符');

		$reason = $_POST['reason'] ?? '';
		$reason = trim($reason);
		if (mb_strlen($reason) < 5)
			err('附註請輸入 5 個字以上');
		if (mb_strlen($reason) > 100)
			err('附註請輸入 100 個字以內');

		try {
			$db->deleteSubmission($uid, "自刪 $reason");
			echo json_encode([
				'ok' => true,
				'msg' => '刪除成功！'
			], JSON_PRETTY_PRINT);
		} catch (Exception $e) {
			err('Database Error ' . $e->getCode() . ': ' . $e->getMessage() . "\n" . $e->lastResponse);
		}
	} else {
		err('Unknown DELETE action. 未知的操作');
	}
}


/* Exit with fail and error message. */
function err(string $msg) {
	exit(json_encode([
		'ok' => false,
		'msg' => $msg
	], JSON_PRETTY_PRINT));
}

/* Return error string or empty on success */
function checkSubmitData(string $body, bool $has_img): string {
	/* Check CSRF Token */
	if (!isset($_SESSION['csrf_token']) || !isset($_POST['csrf_token']))
		return 'No CSRF Token. 請重新操作';

	if ($_SESSION['csrf_token'] !== $_POST['csrf_token'])
		return 'Invalid CSRF Token. 請重新操作';

	unset($_SESSION['csrf_token']);  // Prevent reuse (e.g., refresh the submit page)

	/* Check CAPTCHA */
	$captcha = trim($_POST['captcha'] ?? 'X');
	if ($captcha != '交大竹湖' && $captcha != '交大竹狐') {
		if (mb_strlen($captcha) > 1 && mb_strlen($captcha) < 20)
			error_log("Captcha failed: $captcha.");
		return 'Are you human? 驗證碼錯誤';
	}

	/* Check Body */
	if (mb_strlen($body) < 5)
		return 'Body too short. 文章過短';

	if ($has_img && mb_strlen($body) > 1000)
		return 'Body too long (' . mb_strlen($body) . ' chars). 文章過長';

	if (mb_strlen($body) > 4000)
		return 'Body too long (' . mb_strlen($body) . ' chars). 文章過長';

	return '';
}

/* Return error message or empty */
function uploadImage(string $uid): string {
	$src = $_FILES['img']['tmp_name'];
	if (!file_exists($src) || !is_uploaded_file($src))
		return 'Uploaded file not found. 上傳發生錯誤';

	if ($_FILES['img']['size'] > 5*1000*1000)
		return 'Image too large. 圖片過大';

	/* Check file type */
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	if (!($ext = array_search($finfo->file($src), [
			'jpg' => 'image/jpeg',
			'png' => 'image/png',
			'gif' => 'image/gif',
		], true)))
		return 'Extension not recognized. 圖片副檔名錯誤';

	$dst = __DIR__ . "/img/$uid";
	if (!move_uploaded_file($src, $dst))
		return 'Failed to move uploaded file. 上傳發生錯誤';

	/* Check image size */
	$size = getimagesize($dst);
	$width = $size[0];
	$height = $size[1];

	if ($width * $height < 320*320)
		return 'Image must be at least 320x320.';

	if ($width/4 > $height)
		return 'Image must be at least 4:1';

	if ($width < $height/2)
		return 'Image must be at least 1:2';

	/* Convert all file type to jpg */
	system("ffmpeg -i $dst $dst.jpg");

	return '';
}
