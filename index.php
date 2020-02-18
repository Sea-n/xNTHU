<?php
session_start();

?>
<!DOCTYPE html>
<html lang="zh-TW">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>靠交 2.0</title>
		<link rel="icon" type="image/png" href="/assets/img/logo-192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="/assets/img/logo-128.png" sizes="128x128">
		<link rel="icon" type="image/png" href="/assets/img/logo-96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="/assets/img/logo-64.png" sizes="64x64">
		<link rel="icon" type="image/png" href="/assets/img/logo-32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/assets/img/logo-16.png" sizes="16x16">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="keywords" content="NCTU, 靠北交大, 靠交 2.0">
		<meta name="description" content="給您一個沒有偷懶小編的靠北交大">
		<meta property="og:title" content="靠交 2.0">
		<meta property="og:url" content="https://x.nctu.app/">
		<meta property="og:image" content="https://x.nctu.app/logo.png">
		<meta property="og:image:secure_url" content="https://x.nctu.app/logo.png">
		<meta property="og:image:type" content="image/png">
		<meta property="og:image:width" content="640">
		<meta property="og:image:height" content="640">
		<meta property="og:type" content="website">
		<meta property="og:description" content="給您一個沒有偷懶小編的靠北交大">
		<meta property="og:site_name" content="靠交 2.0">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link href="https://www.sean.taipei/assets/css/tocas-ui/tocas.css" rel="stylesheet">
		<link href="/assets/css/style.css" rel="stylesheet">
		<script src="/assets/js/common.js"></script>
	</head>
	<body>
		<nav class="ts basic fluid borderless menu horizontally scrollable">
			<div class="ts container">
				<a class="active item" href=".">首頁</a>
				<a class="item" href="submit">發文</a>
				<a class="item" href="review">審核</a>
				<div class="right fitted item">
<?php if (isset($_SESSION['nctu_id'])) { ?>
					<img class="ts mini circular image" src="https://c.disquscdn.com/uploads/users/20967/622/avatar128.jpg">&nbsp;<b><?= $_SESSION['name'] ?></b>
<?php } else { ?>
					<a class="item" href="/login-nctu">Login</a>
<?php } ?>
				</div>
			</div>
		</nav>
		<header class="ts fluid vertically padded heading slate">
			<div class="ts narrow container">
				<h1 class="ts header">靠交 2.0</h1>
				<div class="description">給您一個沒有偷懶小編的靠北交大</div>
			</div>
		</header>
		<div class="ts container" name="main">
			<h2 class="ts header">社群平台</h2>
			<ul>
				<li><a target="_blank" href="https://t.me/xNCTU"><i class="fa fa-paper-plane"></i> Telegram</a></li>
				<li><a target="_blank" href="https://twitter.com/x_NCTU"><i class="fa fa-twitter"></i> Twitter</a></li>
				<li><a target="_blank" href="https://www.facebook.com/xNCTU"><i class="fa fa-facebook-square"></i> Facebook</a></li>
				<li><a target="_blank" href="https://www.plurk.com/xNCTU"><i class="fa fa-user-plus"></i> Plurk</a></li>
			</ul>
			<!-- Note: repeated in /submit -->
			<h2 class="ts header">發文規則</h2>
			<ol>
				<li>攻擊性投稿內容不能含有姓名、暱稱等各種明顯洩漏對方身分的個人資料，請把關鍵字自行碼掉。
					<ul><li>登入後具名投稿者，不受此條文之限制。</li></ul></li>
				<li>含有性別歧視、種族歧視、人身攻擊、色情內容、不實訊息等文章，將由審核團隊衡量發文尺度。</li>
				<li>如果對文章感到不舒服，請有禮貌的來信審核團隊，如有合理理由將協助刪文。</li>
			</ol>
			<h2 class="ts header">審文規則</h2>
			<p>新版靠交 2.0 採全自動審文，人人皆可申請加入審核團隊，以下是系統判斷方式</p>
			<h4>登入具名發文</h4>
			<p>如在 5 分鐘內無「駁回」，免審核即自動發出</p>
			<h4>交大 IP 位址</h4>
			<p>使用 113 位址發文者，達到以下三個條件即發出</p>
			<ul>
				<li>等待審核至少 10 分鐘</li>
				<li>累積至少 2 個「通過」</li>
				<li>「通過」多於「駁回」</li>
			</ul>
			<h4>使用台灣 IP 位址</h4>
			<ul>
				<li>等待審核至少 30 分鐘</li>
				<li>30 分鐘至 2 小時：達到 5 個「通過」，並且「通過」多於「駁回」</li>
				<li>2 至 6 小時：達到 2 個「通過」，並且「通過」多於「駁回」</li>
				<li>6 至 12 小時：達到 1 個「通過」，並且「通過」多於「駁回」</li>
				<li>12 小時以後：只要「駁回」不多於「通過」即自動發出</li>
			</ul>
			<h4>境外 IP 位址</h4>
			<ul>
				<li>等待審核至少 60 分鐘</li>
				<li>達到 10 個「通過」</li>
				<li>「通過」比「駁回」多兩倍</li>
			</ul>
			<h2 class="ts header">排程發文</h2>
			<p>通過之文章將會進入發文佇列，每 5 分鐘 po 出一篇至各大社群平台，如欲搶先看也可申請加入審核團隊</p>
			<h2 class="ts header">現在開始</h2>
			<div class="ts fluid stackable buttons"><a class="ts massive positive button" href="/submit">我要發文</a><a class="ts massive info button" href="/review">我想審核</a></div>
			<p></p>
		</div>
		<footer class="panel-footer">
			<center>
				<p>由交大資工 112 級 <a target="_blank" href="https://www.sean.taipei/">Sean 韋詠祥</a> 開發設計
				| 聯絡我們：<a href="mailto:x@nctu.app">x@nctu.app</a></p>
			</center>
		</footer>
	</body>
</html>