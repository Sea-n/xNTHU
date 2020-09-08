<?php
require_once('config.php');
$TITLE = '常見問答';
$IMG = "https://$DOMAIN/assets/img/og.png";
?>
<!DOCTYPE html>
<html lang="zh-TW">
	<head>
<?php include('includes/head.php'); ?>
	</head>
	<body>
<?php include('includes/nav.php'); ?>
		<header class="ts fluid vertically padded heading slate">
			<div class="ts narrow container">
				<h1 class="ts header">常見問答</h1>
				<div class="description"><?= SITENAME ?></div>
			</div>
		</header>
		<div class="ts container" name="main">
			<p>下面列出了幾個關於此服務的問題，如有疏漏可聯絡開發團隊，將儘快答覆您。</p>

			<div class="faq-anchor" id="modify-name"></div><h2 class="ts header">Q：如何更改暱稱</h2>
			<p>目前此功能僅實作於 Telegram bot 中，請點擊首頁下方按鈕連結 Telegram 帳號。</p>
			<p>於 Telegram 使用 /name 指令即可更改您的暱稱，所有過往的投稿、投票也會一起修正。</p>

			<div class="faq-anchor" id="length-limit"></div><h2 class="ts header">Q：字數上限是多少</h2>
			<p>純文字投稿的字數上限為 3,600 字、附圖投稿為 870 字。</p>
			<p>遊走字數上限發文時請注意，最好在發出前自行備份，避免因伺服器判斷誤差造成投稿失敗。</p>

			<div class="faq-anchor" id="link-preview"></div><h2 class="ts header">Q：怎麼在 Facebook 貼文顯示連結預覽</h2>
			<p>請將連結獨立放在投稿的最後一行文字，系統將會自動為您產生預覽。</p>
			<p>另外，如果是 Facebook 貼文連結的話，因為臉書的限制無法自動產生預覽，將由維護團隊手動補上。</p>

			<div class="faq-anchor" id="post-schedule"></div><h2 class="ts header">Q：投稿什麼時候會發出</h2>
			<p>通過審核之文章將會進入發文佇列，由系統<b>每 5 分鐘</b> po 出一篇至各大社群平台，如欲搶先看也可申請加入審核團隊。</p>
			<p>所謂無人 <button class="ts vote negative button">駁回</button> 門檻意指 <button class="ts vote positive button">通過</button> - <button class="ts vote negative button">駁回</button> * 2，以降低個人誤觸影響。</p>

			<div class="faq-anchor" id="deleted-submissions"></div><h2 class="ts header">Q：被駁回的機制是什麼</h2>
			<p>當投稿被多數人駁回，或是放了很久卻達不到通過標準，就會被系統自動清理。</p>
			<p>詳細判斷標準如下：</p>
			<ul>
				<li>1 小時以內：達到 5 個&nbsp;<button class="ts vote negative button">駁回</button></li>
				<li>1 小時至 12 小時：達到 3 個&nbsp;<button class="ts vote negative button">駁回</button></li>
				<li>12 小時以後：不論條件，全數回收</li>
			</ul>
			<p>使用境外 IP 位址發文者，達到 2 個 <button class="ts vote negative button">駁回</button> 即刪除。</p>

			<div class="faq-anchor" id="deleted-submissions"></div><h2 class="ts header">Q：可以去哪找到被黑箱的投稿</h2>
			<p>如果達到上述駁回條件，或是管理團隊覺得投稿不適合發出，就會放到 <a href="/deleted">已刪投稿</a> 頁面。</p>
			<p>目前此區域限制只有已登入交清帳號的使用者才能檢閱，可見篇數將依審文數量而定，預設會顯示最近 3 篇被駁回的投稿。</p>

			<div class="faq-anchor" id="ip-mask"></div><h2 class="ts header">Q：隱藏 IP 位址的機制是什麼</h2>
			<p>所有已登入的交大人、清大人都看得到匿名發文者的部分 IP 位址，一方面知道幾篇文是同一個人發的可能性，另一方面又保留匿名性。</p>
			<p>對於大部分的位址，會使用 140.114.***.*87 (IPv4) 或 2001:288:e001:***:1234 (IPv6) 的格式，在無法追溯個人的前提下，盡可能提供最多資訊。</p>
			<p>另外，對於公用網路或是境外投稿者將會揭露完整 IP 位址，供審核者們自行判斷意圖。</p>

			<div class="faq-anchor" id="rate-limit"></div><h2 class="ts header">Q：發文速率有限制嗎</h2>
			<p>由於遭到部分校外人士濫用，目前針對匿名發文有限制發文速率</p>
			<ul>
				<li>交清 IP 位址：每 10 分鐘最多 5 篇</li>
				<li>台灣 IP 位址：<b>每 3 小時最多 5 篇</b></li>
				<li>境外 IP 位址：每天最多 1 篇</li>
			</ul>
		</div>
<?php include('includes/footer.php'); ?>
	</body>
</html>
