<?php
require_once(__DIR__ . '/utils.php');
require_once(__DIR__ . '/config.php');
class MyDB {
	public $pdo;

	public function __construct() {
		$this->pdo = new PDO('mysql:host=localhost;dbname=xnctu', 'xnctu', MYSQL_PASSWORD);
		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

	/* Return error info or ['00000', null, null] on success */
	public function insertSubmission(string $uid, string $body, bool $has_img, string $ip_addr, string $author_id, string $author_name, string $author_photo): array {
		if (strlen($uid) != 4)
			return ['SEAN', 0, 'UID invalid. (should be 4 chars)'];

		if (mb_strlen($body) < 5)
			return ['SEAN', 0, 'Body too short. (at least 5 chars)'];

		if (mb_strlen($body) > 4000)
			return ['SEAN', 0, 'Body too long. (max 4000 chars)'];

		if ($has_img && mb_strlen($body) > 1000)
			return ['SEAN', 0, 'Body too long. (max 1000 chars with image)'];

		$sql = "INSERT INTO submissions(uid, body, has_img, ip_addr, author_id, author_name, author_photo) VALUES (:uid, :body, :has_img, :ip_addr, :author_id, :author_name, :author_photo)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':uid' => $uid,
			':body' => $body,
			':has_img' => $has_img,
			':ip_addr' => $ip_addr,
			':author_id' => $author_id,
			':author_name' => $author_name,
			':author_photo' => $author_photo,
		]);

		return $stmt->errorInfo();
	}

	public function getSubmissionByUid(string $uid) {
		$sql = "SELECT * FROM submissions WHERE uid = :uid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':uid' => $uid]);
		return $stmt->fetch();
	}

	public function getSubmissions(int $limit, bool $desc = true) {
		if ($limit == 0) $limit = 9487;

		if ($desc)
			$sql = "SELECT * FROM submissions ORDER BY created_at DESC";
		else
			$sql = "SELECT * FROM submissions ORDER BY created_at ASC";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();

		$results = [];
		while ($item = $stmt->fetch()) {
			if (isset($item['id']))
				continue;

			if (isset($item['deleted_at']))
				continue;

			if (!$limit--)
				break;

			$results[] = $item;
		}

		return $results;
	}

	public function getDeletedSubmissions(int $limit, bool $desc = true) {
		if ($limit == 0) $limit = 9487;

		if ($desc)
			$sql = "SELECT * FROM submissions WHERE deleted_at ORDER BY created_at DESC";
		else
			$sql = "SELECT * FROM submissions WHERE deleted_at ORDER BY created_at ASC";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();

		$results = [];
		while ($item = $stmt->fetch()) {
			if (!$limit--)
				break;

			$results[] = $item;
		}

		return $results;
	}

	/* Return submissions with previous vote */
	public function getSubmissionsForVoter(string $nctu_id, int $limit) {
		if ($limit == 0) $limit = 9487;

		$data = $this->getVotesByUser($nctu_id);
		$votes = [];
		foreach ($data as $item)
			$votes[ $item['uid'] ] = $item['vote'];

		$sql = "SELECT * FROM submissions ORDER BY created_at DESC";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();

		$results = [];
		while ($item = $stmt->fetch()) {
			if (isset($item['id']))
				continue;

			if (isset($item['deleted_at']))
				continue;

			/* Should be 1 or -1 or NULL, not 0 */
			if (isset($votes[ $item['uid'] ]))
				$item['vote'] = $votes[ $item['uid'] ];

			if (!$limit--)
				break;

			$results[] = $item;
		}

		return $results;
	}

	public function deleteSubmission(string $uid, string $reason) {
		$sql = "UPDATE submissions SET delete_note = :reason, deleted_at = CURRENT_TIMESTAMP WHERE uid = :uid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':uid' => $uid,
			':reason' => $reason
		]);
	}

	public function getPostById(string $id) {
		$sql = "SELECT * FROM posts WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':id' => $id]);
		return $stmt->fetch();
	}

	/* Get posts newest first */
	public function getPosts(int $limit) {
		if ($limit == 0) $limit = 9487;

		$sql = "SELECT * FROM posts ORDER BY created_at DESC";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();

		$results = [];
		while ($item = $stmt->fetch()) {
			if (isset($item['deleted_at']))
				continue;

			if (!$limit--)
				break;

			$results[] = $item;
		}

		return $results;
	}

	/* Check can user vote for certain submission or not */
	public function canVote(string $uid, string $voter): array {
		if ($uid == 'TEST')
			return ['ok' => true];

		$sql = "SELECT * FROM submissions WHERE uid = :uid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':uid' => $uid]);
		if (!($item = $stmt->fetch()))
			return ['ok' => false, 'msg' => 'uid not found. 找不到該投稿'];

		if (isset($item['id']))
			return ['ok' => false, 'msg' => 'Already posted. 太晚囉，貼文已發出'];

		if (isset($item['deleted_at']))
			return [
				'ok' => false,
				'msg' => '投稿已刪除，理由：' . $item['delete_note']
			];

		$sql = "SELECT created_at FROM votes WHERE uid = :uid AND voter = :voter";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':uid' => $uid,
			':voter' => $voter
		]);
		if ($stmt->fetch())
			return ['ok' => false, 'msg' => 'Already voted. 您已投過票'];

		return ['ok' => true];
	}

	public function voteSubmissions(string $uid, string $voter, int $vote, string $reason = '') {
		if ($uid == 'TEST')
			return [
				'ok' => true,
				'approvals' => 87,
				'rejects' => 42,
			];

		if ($vote == 1)
			$type = 'approvals';
		else if ($vote == -1)
			$type = 'rejects';
		else
			return ['ok' => false, 'msg' => 'Unknown vote. 未知的投票類型'];

		if (mb_strlen($reason) > 100)
			return ['ok' => false, 'msg' => 'Reason too long. 附註文字過長'];

		$check = $this->canVote($uid, $voter);
		if (!$check['ok'])
			return $check;

		$sql = "INSERT INTO votes(uid, vote, reason, voter) VALUES (:uid, :vote, :reason, :voter)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':uid' => $uid,
			':vote' => $vote,
			':reason' => $reason,
			':voter' => $voter
		]);

		/* Caution: use string combine in SQL query */
		$sql = "UPDATE submissions SET $type = $type + 1 WHERE uid = :uid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':uid' => $uid]);

		$sql = "SELECT approvals, rejects FROM submissions WHERE uid = :uid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':uid' => $uid]);
		$result = $stmt->fetch();

		$result['ok'] = true;
		return $result;
	}

	public function getVotes() {
		$sql = "SELECT * FROM votes";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute();

		$results = [];
		while ($item = $stmt->fetch())
			$results[] = $item;

		return $results;
	}

	public function getVotesByUid(string $uid) {
		$sql = "SELECT * FROM votes WHERE uid = :uid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':uid' => $uid]);

		$results = [];
		while ($item = $stmt->fetch())
			$results[] = $item;

		return $results;
	}

	private function getVotesByUser(string $nctu_id) {
		$sql = "SELECT * FROM votes WHERE voter = :nctu_id ORDER BY created_at DESC";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':nctu_id' => $nctu_id]);

		$results = [];
		while ($item = $stmt->fetch())
			$results[] = $item;

		return $results;
	}

	private function isSubmissionEligible(array $item) {
		$dt = time() - strtotime($item['created_at']);
		$vote = $item['approvals'] - $item['rejects'];

		/* Rule for Logged-in users */
		if (!empty($item['author_id'])) {
			if ($dt < 10*60)
				return false;

			if ($vote < 0)
				return false;

			return true;
		}

		/* Rule for NCTU IP address */
		if (substr($item['ip_addr'], 0, 8) == '140.113.'
		 || substr($item['ip_addr'], 0, 9) == '2001:f18:') {
			if ($dt < 10*60)
				return false;

			if ($vote < 0)
				return false;

			if ($dt < 2*60*60) {
				if ($vote < 2)
					return false;
			} else if ($dt < 6*60*60) {
				if ($vote < 1)
					return false;
			}

			return true;
		}

		/* Rule for Taiwan IP address */
		if (strpos($item['author_name'], '境外') === false) {
			if ($dt < 10*60)  // 0 - 30min
				return false;

			if ($dt < 24*60*60) {  // 10min - 24hr
				if ($vote <= 0)
					return false;

				if ($dt < 1*60*60) {  // 30min - 1hr
					if ($vote < 5)
						return false;
				} else if ($dt < 6*60*60) {  // 1hr - 6hr
					if ($vote < 3)
						return false;
				} else                    {  // 6hr - 24hr
					if ($vote < 1)
						return false;
				}
			} else {
				if ($vote < 0)
					return false;
			}

			return true;
		}

		/* Rule for Foreign IP address */
		if (true) {
			if ($dt < 60*60)
				return false;

			if ($vote < 10)
				return false;

			return true;
		}
	}

	public function getPostReady() {
		/* Check undone post */
		$posts = $this->getPosts(1);
		if (isset($posts[0])
		&& ($posts[0]['telegram_id'] <= 0
		 || $posts[0]['plurk_id']    <= 0
		 || $posts[0]['twitter_id']  <= 0
		 || $posts[0]['facebook_id'] <= 0))
			return $posts[0];

		/* Get all pending submissions, oldest first */
		$submissions = $this->getSubmissions(0, false);

		foreach ($submissions as $item) {
			if ($this->isSubmissionEligible($item)) {
				$post = $item;
				break;
			}
		}

		/* No eligible pending submission */
		if (!isset($post))
			return false;

		$sql = "INSERT INTO posts(uid, body, has_img, ip_addr, author_id, author_name, author_photo, approvals, rejects, submitted_at) VALUES (:uid, :body, :has_img, :ip_addr, :author_id, :author_name, :author_photo, :approvals, :rejects, :submitted_at)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':uid' => $post['uid'],
			':body' => $post['body'],
			':has_img' => $post['has_img'],
			':ip_addr' => $post['ip_addr'],
			':author_id' => $post['author_id'],
			':author_name' => $post['author_name'],
			':author_photo' => $post['author_photo'],
			':approvals' => $post['approvals'],
			':rejects' => $post['rejects'],
			':submitted_at' => $post['created_at']
		]);

		$id = $this->pdo->lastInsertId();
		$post['id'] = $id;
		$post['submitted_at'] = $post['created_at'];

		$sql = "UPDATE submissions SET id = :id WHERE uid = :uid";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':uid' => $post['uid'],
			':id' => $id
		]);

		return $post;
	}

	/* Update SNS post ID */
	public function updatePostSns(int $id, string $type, int $pid) {
		if (!in_array($type, ['telegram', 'plurk', 'twitter', 'facebook']))
			return false;

		/* Caution: use string combine in SQL query */
		$sql = "UPDATE posts SET {$type}_id = :pid WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':id' => $id,
			':pid' => $pid,
		]);
	}

	public function insertUserNctu(string $nctu_id, string $mail) {
		$sql = "SELECT nctu_id FROM users WHERE nctu_id = :nctu_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':nctu_id' => $nctu_id]);

		if ($stmt->fetch())
			return false;

		$sql = "INSERT INTO users(name, nctu_id, nctu_mail) VALUES (:name, :nctu_id, :mail)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':name' => $nctu_id,
			':nctu_id' => $nctu_id,
			':mail' => $mail
		]);
	}

	public function insertUserTg(string $nctu_id, array $tg) {
		$sql = "SELECT nctu_id FROM users WHERE nctu_id = :nctu_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':nctu_id' => $nctu_id]);

		if (!$stmt->fetch())
			return false;

		$name = $tg['first_name'];
		if (isset($tg['last_name']))
			$name .= ' ' . $tg['last_name'];

		$sql = "UPDATE users SET tg_id = :tg_id, tg_name = :name, tg_username = :username, tg_photo = :photo WHERE nctu_id = :nctu_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':nctu_id' => $nctu_id,
			':tg_id' => $tg['id'],
			':name' => $name,
			':username' => $tg['username'] ?? '',
			':photo' => $tg['photo_url'] ?? '',
		]);
	}

	public function updateUserTgProfile(array $tg) {
		$name = $tg['first_name'];
		if (isset($tg['last_name']))
			$name .= ' ' . $tg['last_name'];

		$sql = "UPDATE users SET tg_name = :name, tg_username = :username, tg_photo = :photo WHERE tg_id = :tg_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':tg_id' => $tg['id'],
			':name' => $name,
			':username' => $tg['username'] ?? '',
			':photo' => $tg['photo_url'] ?? '',
		]);
	}

	public function updateUserNameTg(int $tg_id, string $name) {
		$sql = "UPDATE users SET name = :name WHERE tg_id = :tg_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':tg_id' => $tg_id,
			':name' => $name,
		]);
	}

	public function getUserByNctu(string $id) {
		$sql = "SELECT * FROM users WHERE nctu_id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':id' => $id]);
		return $stmt->fetch();
	}

	public function getUserByTg(int $id) {
		$sql = "SELECT * FROM users WHERE tg_id = :id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':id' => $id]);
		return $stmt->fetch();
	}

	public function getTgUsers() {
		$sql = "SELECT * FROM users WHERE tg_id > 0";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([]);

		$results = [];
		while ($item = $stmt->fetch())
			$results[] = $item;

		return $results;
	}

	public function removeUserTg(int $tg_id) {
		$sql = "UPDATE users SET tg_name = NULL WHERE tg_id = :tg_id";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([':tg_id' => $tg['id']]);
	}

	public function setTgMsg(string $uid, int $chat, int $msg) {
		if ($this->getTgMsg($uid, $chat))
			$this->updateTgMsg($uid, $chat, $msg);
		else
			$this->insertTgMsg($uid, $chat, $msg);
	}


	public function insertTgMsg(string $uid, int $chat, int $msg) {
		$sql = "INSERT INTO tg_msg(uid, chat_id, msg_id) VALUES (:uid, :chat, :msg)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':uid' => $uid,
			':chat' => $chat,
			':msg' => $msg,
		]);
	}

	public function updateTgMsg(string $uid, int $chat, int $msg) {
		$sql = "UPDATE tg_msg SET msg_id = :msg WHERE uid = :uid AND chat_id = :chat";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':uid' => $uid,
			':chat' => $chat,
			':msg' => $msg,
		]);
	}

	public function getTgMsg(string $uid, int $chat): int {
		$sql = "SELECT msg_id FROM tg_msg WHERE uid = :uid AND chat_id = :chat";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':uid' => $uid,
			':chat' => $chat,
		]);

		$item = $stmt->fetch();

		return $item['msg_id'] ?? 0;
	}

	public function deleteTgMsg(string $uid, int $chat) {
		$sql = "DELETE FROM tg_msg WHERE uid = :uid AND chat_id = :chat";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':uid' => $uid,
			':chat' => $chat,
		]);
	}
}
