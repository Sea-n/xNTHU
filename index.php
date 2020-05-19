<?php
session_start(['read_and_close' => true]);
require_once('config.php');

$TITLE = '首頁';
$IMG = "https://$DOMAIN/assets/img/og.png";
?>
<!DOCTYPE html>
<html lang="zh-TW">
	<head>
<?php include('includes/head.php'); ?>
		<script src="/assets/js/index.js"></script>
	</head>
	<body>
<?php include('includes/nav.php'); ?>
		<header class="ts fluid vertically padded heading slate">
			<div class="ts narrow container">
				<h1 class="ts header"><?= SITENAME ?></h1>
				<div class="description">不要問為何沒有人審文，先承認你就是沒有人。</div>
			</div>
		</header>
		<div class="ts container" name="main">
			<h2 class="ts header">社群平台</h2>
			<p>除了本站文章列表外，您可以在以下 2 個社群媒體平台追蹤<?= SITENAME ?> 帳號。</p>
			<div class="icon-row">
				<a id="telegram-icon"  class="ts link tiny rounded image" target="_blank" href="https://t.me/xNTHU"              ><img src="https://image.flaticon.com/icons/svg/2111/2111646.svg"   alt="Telegram" ></a>
				<a id="facebook-icon"  class="ts link tiny rounded image" target="_blank" href="https://www.facebook.com/xNTHU2.0"><img src="https://image.flaticon.com/icons/svg/220/220200.svg"     alt="Facebook" ></a>
			</div>

			<h2 class="ts header">審文機制</h2>
			<div id="review-content" style="height: 320px; overflow-y: hidden;">
				<p>新版<?= SITENAME ?> 採自助式審文，所有交清師生皆可加入審核者的行列，以下是系統判斷標準</p>

				<h4>(A) 具名投稿</h4>
				<p>如在 5 分鐘內無 <button class="ts vote negative button">駁回</button>，免審核即自動發出，詳細判斷條件如下：</p>
				<ul>
					<li>等待審核至少 5 分鐘</li>
					<li><button class="ts vote positive button">通過</button>&nbsp;不少於&nbsp;<button class="ts vote negative button">駁回</button></li>
				</ul>

				<h4>(B) 交清 IP 位址</h4>
				<p>使用 113 或 114 位址投稿者，滿足以下條件即發出</p>
				<ul>
					<li>等待審核至少 10 分鐘</li>
					<li><button class="ts vote positive button">通過</button>&nbsp;比&nbsp;<button class="ts vote negative button">駁回</button>&nbsp;多 3 個</li>
				</ul>
				<h4>(C) 使用台灣 IP 位址</h4>
				<p>詳細判斷條件如下：</p>
				<ul>
					<li>等待審核至少 20 分鐘</li>
					<li>達到 5 個&nbsp;<button class="ts vote positive button">通過</button>&nbsp;且無&nbsp;<button class="ts vote negative button">駁回</button></li>
				</ul>

			</div>
			<div id="hide-box">
				<big onclick="more();" style="cursor: pointer; color: black;">展開完整規則 <i class="dropdown icon"></i></big>
			</div>

			<div class="ts horizontal divider">現在開始</div>
			<div class="ts fluid stackable buttons"><a class="ts massive positive button" href="/submit">我要投稿</a><a class="ts massive info button" href="/review">我想審核</a></div>

<?php if (!isset($USER) || !isset($USER['tg_id'])) { ?>
			<h2 class="ts header">清大學生登入</h2>
			<p>若您尚未通過清大學生認證，請點擊打開此 bot 並依指示驗證身份。</p>
			<script async src="https://telegram.org/js/telegram-widget.js?7" data-telegram-login="xNTHUbot" data-size="large" data-auth-url="https://<?= DOMAIN ?>/login-tg" data-request-access="write"></script>
			<h2 class="ts header">交大學生登入</h2>
			<p>登入 <a href="/login-nctu">NCTU OAuth</a></p>
<?php } else if (!isset($USER['tg_id'])) { ?>
			<h2 class="ts header">使用 Telegram 快速審核</h2>
			<p>點擊下面按鈕即可綁定 Telegram 帳號，讓您收到最即時的投稿通知，並快速通過/駁回貼文。</p>
			<script async src="https://telegram.org/js/telegram-widget.js?7" data-telegram-login="xNTHUbot" data-size="large" data-auth-url="https://<?= DOMAIN ?>/login-tg" data-request-access="write"></script>
<?php } else if ($USER['name'] == $USER['stuid']) { ?>
			<h2 class="ts header">使用 Telegram 快速審核</h2>
			<div class="ts positive message">
				<div class="header">您已連結成功！</div>
				<p>Tips: 使用 /name 指令即可修改您的暱稱</p>
			</div>
<?php } ?>

			<h2 class="ts header">排行榜</h2>
			<p>排名積分會依時間遠近調整權重，正確的駁回 <a href="/deleted">已刪投稿</a> 將大幅提升排名。</p>
			<p>您可以在 <a href="/ranking">這個頁面</a> 查看排行榜。</p>

			<h2 class="ts header">服務聲明</h2>
			<p>感謝您使用「<?= SITENAME ?>」（以下簡稱本網站），本網站之所有文章皆為不特定使用者自行投稿、不特定師生進行審核，並不代表本網站立場。</p>
			<p>如有侵害您權益之貼文，麻煩寄信至服務團隊，將在最短時間協助您撤下貼文或進行澄清。</p>
			<p>投稿者如散播不實訊息而遭司法單位追究，在司法機關提供調取票等充分條件下，本網站將依法提供 IP 位址配合偵辦，切勿以身試法。</p>
		</div>
<?php include('includes/footer.php'); ?>
	</body>
</html>
