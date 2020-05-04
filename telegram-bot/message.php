<?php
require(__DIR__.'/../utils.php');
require(__DIR__.'/../database.php');
$db = new MyDB();

$text = $TG->data['message']['text'] ?? '';

if ($TG->ChatID < 0) {
	if ($TG->ChatID != -1001268692253)
		$TG->sendMsg([
			'text' => '目前尚未支援群組功能',
			'reply_markup' => [
				'inline_keyboard' => [
					[
						'text' => '📢 靠清 2.0 頻道',
						'url' => 'https://t.me/xNTHU'
					]
				]
			]
		]);

	if (substr($text, 0, 1) != '/')
		exit;
}

$USER = $db->getUserByTg($TG->FromID);
if (!$USER) {
	$msg = "【靠北清大 2.0 帳號申請單】\n\n";
	$msg .= "1. 姓名： `XXX`\n";
	$msg .= "2. 學號： `108062000`\n";
	$msg .= "3. 系級： `資工系 23 級`\n";
	$msg .= "4. Telegram Username： `@{$TG->data['message']['from']['username']}`\n";
	$msg .= "5. Telegram UID： `{$TG->FromID}`\n";
	$result = $TG->sendMsg([
		'text' => $msg,
		'parse_mode' => 'Markdown',
	]);

	$msg = "您尚未驗證清大身份，請*使用清大信箱*，填寫以上申請單後寄至維護團隊\n\n";
	$msg .= "主旨： *靠北清大 2.0 - 帳號申請*\n";
	$msg .= "收件人： x@nthu.io\n";
	$msg .= "\n目前為人工審核，寄出後請靜待維護團隊確認身份";
	$TG->sendMsg([
		'text' => $msg,
		'parse_mode' => 'Markdown',
	]);
	exit;
}

if (substr($text, 0, 1) == '/') {
	$text = substr($text, 1);
	[$cmd, $arg] = explode(' ', $text, 2);
	$cmd = explode('@', $cmd, 2)[0];

	switch($cmd) {
		case 'start':
		case 'help':
			$msg = "歡迎使用靠北清大 2.0 機器人\n\n";
			$msg .= "目前支援的指令：\n";
			$msg .= "/name 更改網站上的暱稱\n";
			$msg .= "/send 發送測試貼文\n";
			$msg .= "/delete 刪除貼文\n";

			$TG->sendMsg([
				'text' => $msg
			]);
			break;

		case 'send':
			$body = "學生計算機年會（Students’ Information Technology Conference）自 2013 年發起，以學生為本、由學生自發舉辦，長期投身學生資訊教育與推廣開源精神，希望引領更多學子踏入資訊的殿堂，更冀望所有對資訊有興趣的學生，能夠在年會裏齊聚一堂，彼此激盪、傳承、啟發，達到「學以致用、教學相長」的實際展現。";

			$result = $TG->getTelegram('sendPhoto', [
				'chat_id' => $TG->ChatID,
				'photo' => "https://x.nthu.io/img/TEST.jpg",
				'caption' => $body,
				'reply_markup' => [
					'inline_keyboard' => [
						[
							[
								'text' => '✅ 通過',
								'callback_data' => "approve_TEST"
							],
							[
								'text' => '❌ 駁回',
								'callback_data' => "reject_TEST"
							]
						],
						[
							[
								'text' => '開啟審核頁面',
								'login_url' => [
									'url' => "https://x.nthu.io/login-tg?r=%2Freview%2FTEST"
								]
							]
						]
					]
				]
			]);

			$db->setTgMsg('TEST', $TG->ChatID, $result['result']['message_id']);
			break;

		case 'name':
			$arg = $TG->enHTML(trim($arg));
			if (empty($arg) || mb_strlen($arg) > 10) {
				$TG->sendMsg([
					'text' => "使用方式：`/name 新暱稱`\n\n字數上限：10 個字",
					'parse_mode' => 'Markdown'
				]);
				break;
			}

			$db->updateUserNameTg($TG->FromID, $arg);

			$TG->sendMsg([
				'text' => '修改成功！',
				'reply_markup' => [
					'inline_keyboard' => [
						[
							[
								'text' => '開啟網站',
								'login_url' => [
									'url' => "https://x.nthu.io/login-tg"
								]
							]
						]
					]
				]
			]);
			break;

		case 'unlink':
			$db->unlinkUserTg($TG->FromID);
			$TG->sendMsg([
				'text' => "已取消連結，請點擊下方按鈕連結新的 NCTU OAuth 帳號",
				'reply_markup' => [
					'inline_keyboard' => [
						[
							[
								'text' => '綁定靠清 2.0 網站',
								'login_url' => [
									'url' => "https://x.nthu.io/login-tg?r=%2F"
								]
							]
						]
					]
				]
			]);
			break;

		case 'adduser':
			if ($TG->FromID != 109780439) {
				$TG->sendMsg([
					'text' => "此功能僅限管理員使用",
				]);
				exit;
			}

			$args = explode(' ', $arg);
			if (count($args) != 2) {
				$TG->sendMsg([
					'text' => "使用方式：/adduser <NTHU ID> <TG ID>",
				]);
				exit;
			}

			$nthu_id = $args[0];
			$tg_id = $args[1];

			$db->insertUserNthu($nthu_id, $tg_id);

			$result = $TG->sendMsg([
				'chat_id' => $tg_id,
				'text' => "🎉 驗證成功！\n\n請點擊以下按鈕登入靠北清大 2.0 網站",
				'reply_markup' => [
					'inline_keyboard' => [
						[
							[
								'text' => '登入靠北清大 2.0',
								'login_url' => [
									'url' => "https://x.nthu.io/login-tg?r=%2F"
								]
							]
						]
					]
				]
			]);

			if ($result['ok'])
				$TG->sendMsg([
					'text' => "Done.\n"
				]);
			else
				$TG->sendMsg([
					'text' => "Failed.\n\n" . json_encode($result, JSON_PRETTY_PRINT)
				]);
			break;

		case 'update':
			if ($TG->FromID != 109780439) {
				$TG->sendMsg([
					'text' => "此功能僅限管理員使用",
				]);
				exit;
			}

			[$column, $body] = explode(' ', $arg, 2);

			if ($column != "body") {
				$TG->sendMsg([
					'text' => "Column '$column' unsupported."
				]);
				exit;
			}

			if (!preg_match('/^#投稿(\w{4})/um', $TG->data['message']['reply_to_message']['text'] ?? '', $matches)) {
				$TG->sendMsg([
					'text' => 'Please reply to submission message.'
				]);
				exit;
			}
			$uid = $matches[1];

			$db->updatePostBody($uid, $body);

			$TG->sendMsg([
				'text' => "Done.\n"
			]);
			break;

		case 'delete':
			$TG->sendMsg([
				'text' => "此功能僅限管理員使用\n\n" .
					"如果您有興趣為靠清 2.0 盡一份心力的話，歡迎聯絡開發團隊 🙃"
			]);
			break;

		default:
			$TG->sendMsg([
				'text' => "未知的指令\n\n如需查看使用說明請使用 /help 功能"
			]);
			break;
	}

	exit;
}

if (preg_match('#^\[(approve|reject)/([a-zA-Z0-9]+)\]#', $TG->data['message']['reply_to_message']['text'] ?? '', $matches)) {
	$vote = $matches[1] == 'approve' ? 1 : -1;
	$uid = $matches[2];
	$reason = $text;

	$type = $vote == 1 ? '✅ 通過' : '❌ 駁回';

	if (empty($reason) || mb_strlen($reason) > 100) {
		$TG->sendMsg([
			'text' => '請輸入 1 - 100 字投票附註'
		]);

		exit;
	}

	try {
		$result = $db->voteSubmissions($uid, $USER['nctu_id'], $vote, $reason);
		if (!$result['ok'])
			$msg = $result['msg'];
		else {
			$msg = "您成功為 #投稿$uid 投下了 $type\n\n";
			$msg .= "目前通過 {$result['approvals']} 票、駁回 {$result['rejects']} 票";

			system("php " . __DIR__ . "/../jobs.php vote $uid {$USER['nctu_id']} > /dev/null &");
		}
	} catch (Exception $e) {
		$msg = 'Error ' . $e->getCode() . ': ' .$e->getMessage() . "\n";
	}

	$TG->sendMsg([
		'text' => $msg,
	]);

	$TG->getTelegram('deleteMessage', [
		'chat_id' => $TG->ChatID,
		'message_id' => $TG->data['message']['reply_to_message']['message_id'],
	]);


	exit;
}
