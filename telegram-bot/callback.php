<?php
require(__DIR__.'/../database.php');
$db = new MyDB();

if (!isset($TG->data['callback_query']['data'])) {
	$TG->sendMsg([
		'text' => 'Error: No callback data.'
	]);
	exit;
}

$USER = $db->getUserByTg($TG->FromID);
if (!$USER) {
	$TG->getTelegram('answerCallbackQuery', [
		'callback_query_id' => $TG->data['callback_query']['id'],
		'show_alert' => true,
		'text' => "您尚未綁定 NCTU 帳號，請至靠北交大 2.0 網站登入"
	]);
	exit;
}

$arg = $TG->data['callback_query']['data'];
$args = explode('_', $arg);
switch ($args[0]) {
	case 'test':
		if ($args[1] == 'send') {
			$body = "學生計算機年會（Students’ Information Technology Conference）自 2013 年發起，以學生為本、由學生自發舉辦，長期投身學生資訊教育與推廣開源精神，希望引領更多學子踏入資訊的殿堂，更冀望所有對資訊有興趣的學生，能夠在年會裏齊聚一堂，彼此激盪、傳承、啟發，達到「學以致用、教學相長」的實際展現。";

			$TG->getTelegram('sendPhoto', [
				'chat_id' => $TG->ChatID,
				'photo' => "https://x.nctu.app/img/4ZSf.png",
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
								'url' => "https://x.nctu.app/review?uid=TEST"
							]
						]
					]
				]
			]);

		}
		break;

	case 'approve':
		$TG->getTelegram('answerCallbackQuery', [
			'callback_query_id' => $TG->data['callback_query']['id'],
			'show_alert' => true,
			'text' => "您確定要通過此投稿嗎？\n\n請點擊綠色按鈕確認"
		]);

		$uid = $args[1];
		$keyboard = [[]];
		for ($i=0; $i<5; $i++)
			for ($j=0; $j<4; $j++)
				if ($i == 3 && $j == 2)
					$keyboard[$i][$j] = [
						'text' => '✅ 通過',
						'callback_data' => "vote_{$uid}_1"
					];
				else
					$keyboard[$i][$j] = [
						'text' => '✖️ 取消',
						'callback_data' => "cancel_{$uid}_$i$j"
					];
		$TG->getTelegram('editMessageReplyMarkup', [
			'chat_id' => $TG->ChatID,
			'message_id' => $TG->MsgID,
			'reply_markup' => [
				'inline_keyboard' => $keyboard
			]
		]);
		break;

	case 'reject':
		$TG->getTelegram('answerCallbackQuery', [
			'callback_query_id' => $TG->data['callback_query']['id'],
			'show_alert' => true,
			'text' => "您確定要駁回此投稿嗎？\n\n請點擊紅色按鈕確認"
		]);

		$uid = $args[1];
		$keyboard = [[]];
		for ($i=0; $i<5; $i++)
			for ($j=0; $j<4; $j++)
				if ($i == 3 && $j == 2)
					$keyboard[$i][$j] = [
						'text' => '❌ 駁回',
						'callback_data' => "vote_{$uid}_-1"
					];
				else
					$keyboard[$i][$j] = [
						'text' => '✖️ 取消',
						'callback_data' => "cancel_{$uid}_$i$j"
					];
		$TG->getTelegram('editMessageReplyMarkup', [
			'chat_id' => $TG->ChatID,
			'message_id' => $TG->MsgID,
			'reply_markup' => [
				'inline_keyboard' => $keyboard
			]
		]);
		break;

	case 'vote':
		$uid = $args[1];
		$vote = $args[2];
		try {
			$result = ['ok'=>0,'msg'=>json_encode($USER['nctu_id'])];
			$result = $db->voteSubmissions($uid, $USER['nctu_id'], $vote, 'Vote via Telegram bot');
			if (!$result['ok'])
				$msg = $result['msg'];
			else
				$msg = "投票成功！\n\n目前通過 {$result['approvals']} 票、駁回 {$result['rejects']} 票";
		} catch (Exception $e) {
			$msg = 'Error ' . $e->getCode() . ': ' .$e->getMessage() . "\n";
		}

		$TG->getTelegram('answerCallbackQuery', [
			'callback_query_id' => $TG->data['callback_query']['id'],
			'show_alert' => true,
			'text' => $msg
		]);

		$TG->getTelegram('editMessageReplyMarkup', [
			'chat_id' => $TG->ChatID,
			'message_id' => $TG->MsgID,
			'reply_markup' => [
				'inline_keyboard' => [
					[
						[
							'text' => '開啟審核頁面',
							'url' => "https://x.nctu.app/review?uid=$uid"
						]
					]
				]
			]
		]);
		break;

	case 'cancel':
		$uid = $args[1];
		$TG->getTelegram('editMessageReplyMarkup', [
			'chat_id' => $TG->ChatID,
			'message_id' => $TG->MsgID,
			'reply_markup' => [
				'inline_keyboard' => [
					[
						[
							'text' => '✅ 通過',
							'callback_data' => "approve_$uid"
						],
						[
							'text' => '❌ 駁回',
							'callback_data' => "reject_$uid"
						]
					],
					[
						[
							'text' => '開啟審核頁面',
							'url' => "https://x.nctu.app/review?uid=$uid"
						]
					]
				]
			]
		]);
		break;

	default:
		$TG->sendMsg([
			'text' => "Unknown callback data: {$arg}"
		]);
		break;
}