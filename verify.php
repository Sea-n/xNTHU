<?php
session_start();
require_once('database.php');
$db = new MyDB();

$stuid = $_GET['stuid'] ?? '';
$sub = $_GET['sub'] ?? '';
$code = $_GET['code'] ?? '';

if (!empty($stuid) || !empty($sub) || !empty($code)) {
	$data_check_string = "verify_{$stuid}_{$sub}";
	$hash = hash_hmac('sha256', $data_check_string, VERIFY_SECRET);
	$hash = substr($hash, 0, 8);
	if (!hash_equals($hash, $code))
		exit('Verify failed. 驗證碼錯誤');

	$GOOGLE = $db->getGoogleBySub($sub);
} else {
	if (isset($_SESSION['stuid'])) {
		header('Location: /');
		exit;
	}
	if (!isset($_SESSION['google_sub'])) {
		header('Location: /login-google');
		exit;
	}

	$GOOGLE = $db->getGoogleBySub($_SESSION['google_sub']);
}

if (!empty($GOOGLE['stuid'])) {
	$_SESSION['stuid'] = $GOOGLE['stuid'];
	unset($_SESSION['google_sub']);
	header('Location: /');
	exit;
}

$gname = toHTML("{$GOOGLE['name']} ({$GOOGLE['email']})");

$TITLE = '驗證交清身份';
$IMG = "https://$DOMAIN/assets/img/og.png";
?>
<!DOCTYPE html>
<html lang="zh-TW">
	<head>
<?php include('includes/head.php'); ?>
		<script src="/assets/js/verify.js"></script>
	</head>
	<body>
<?php
include('includes/nav.php');
include('includes/header.php');
?>
		<div class="ts container" name="main">
<?php if (empty($code)) { ?>
			<h2 class="ts header">清大信箱驗證</h2>
			<p>為確認學生身份，請輸入您的學號，驗證信將寄送至 <b>s<span id="mail-stuid">108062000</span>@m<span id="mail-year">108</span>.nthu.edu.tw</b> 信箱。</p>
			<form id="send-verify" class="ts form" action="/api/verify" method="POST">
				<div class="required inline field">
					<label>學號</label>
					<div class="two wide">
						<input name="stuid" id="stuid" placeholder="108062000" />
					</div>
				</div>
				<input id="submit" type="submit" class="ts button" value="發送驗證信" />
			</form>
			<p>寄出驗證信後，請開啟 <a id="mail-url" target="_blank" href="https://m108-mail.nthu.edu.tw/">https://m108-mail.nthu.edu.tw/</a> 信箱收驗證碼，如未收到麻煩檢查垃圾信件，重寄後三分鐘仍未收到請聯絡維護團隊。</p>

			<h2 class="ts header">如果你是交大生...</h2>
			<p>請先 <a href="/login-nctu">點我綁定 NCTU OAuth</a> 帳號</p>

			<h2 class="ts header">選錯帳號？</h2>
			<p>請 <a href="/logout">點我登出</a> <u><?= $gname ?></u> 帳號</p>
<?php } else { ?>
			<h2 class="ts header">清大信箱驗證</h2>
			<p>請確認是否將 <u><?= $gname ?></u> 綁定至學號 <u><?= $stuid ?></u>？以後您可以用此 Google 帳號登入<?= SITENAME ?>。</p>
			<div class="ts buttons">
				<button class="ts positive button" onclick="confirmVerify();">確認</button>
				<button class="ts negative button" onclick="location.href = '/';">取消</button>
			</div>
<?php } ?>
		</div>
<?php include('includes/footer.php'); ?>
	</body>
</html>