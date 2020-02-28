<?php
session_start();
require_once('utils.php');
require_once('database.php');
$db = new MyDB();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	$action = $_GET['action'] ?? 'x';
	switch ($action) {
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (($_SERVER['HTTP_CONTENT_TYPE'] ?? '') == 'application/json')
		$_POST = json_decode(file_get_contents('php://input'), true);

	$action = $_POST['action'] ?? 'x';
	switch ($action) {
		case 'vote':
			if (!isset($_SESSION['nctu_id']))
				exit(json_encode([
					'ok' => false,
					'msg' => '請先登入'
				]));

			$uid = $_POST['uid'] ?? '';
			if (strlen($uid) != 4)
				exit(json_encode([
					'ok' => false,
					'msg' => 'uid invalid. 投稿編號無效'
				]));

			$voter = $_SESSION['nctu_id'];

			$vote = $_POST['vote'] ?? 0;
			if ($vote != 1 && $vote != -1)
				exit(json_encode([
					'ok' => false,
					'msg' => 'vote invalid. 投票類型無效'
				]));

			$reason = $_POST['reason'] ?? '';
			$reason = trim($reason);
			if (mb_strlen($reason) < 5)
				exit(json_encode([
					'ok' => false,
					'msg' => '附註請輸入 5 個字以上'
				]));
			if (mb_strlen($reason) > 100)
				exit(json_encode([
					'ok' => false,
					'msg' => '附註請輸入 100 個字以內'
				]));

			$result = $db->voteSubmissions($uid, $voter, $vote, $reason);
			echo json_encode($result);
			break;

		case 'delete':
			$uid = $_POST['uid'] ?? '';
			if (strlen($uid) != 4)
				exit(json_encode([
					'ok' => false,
					'msg' => 'uid invalid. 投稿編號無效'
				]));

			$post = $db->getSubmissionByUid($uid);
			if (!$post)
				exit(json_encode([
					'ok' => false,
					'msg' => '找不到該篇投稿'
				]));

			$ts = strtotime($post['created_at']);
			$dt = time() - $ts;
			if ($dt > 5*60)
				exit(json_encode([
					'ok' => false,
					'msg' => '已超出刪除期限，請來信聯絡開發團隊'
				]));

			if ($_SERVER['REMOTE_ADDR'] !== $post['ip_addr'])
				exit(json_encode([
					'ok' => false,
					'msg' => '無法驗證身份：IP 位址不相符'
				]));

			$reason = $_POST['reason'] ?? '';
			$reason = trim($reason);
			if (mb_strlen($reason) < 5)
				exit(json_encode([
					'ok' => false,
					'msg' => '附註請輸入 5 個字以上'
				]));
			if (mb_strlen($reason) > 100)
				exit(json_encode([
					'ok' => false,
					'msg' => '附註請輸入 100 個字以內'
				]));

			try {
				$db->deleteSubmission($uid, "自刪 $reason");
				echo json_encode([
					'ok' => true,
					'msg' => '刪除成功！'
				]);
			} catch (Exception $e) {
				echo json_encode([
					'ok' => 'false',
					'msg' => ' Database Error ' . $e->getCode() . ': ' . $e->getMessage() . "\n" . $e->lastResponse
				]);
			}

			break;

		default:
			exit(json_encode([
				'ok' => false,
				'msg' => 'Unknown action.'
			]));
			break;
	}
}
