<?php
require_once('config.php');
$TITLE = '透明度報告';
$IMG = "https://$DOMAIN/assets/img/og.png";
?>
<!DOCTYPE html>
<html lang="zh-TW">
	<head>
<?php include('includes/head.php'); ?>
	</head>
	<body>
<?php
include('includes/nav.php');
include('includes/header.php');
?>
		<div class="ts container" name="main">
			<p>秉持公開透明原則，除了 <a href="/deleted">已刪投稿</a> 保留完整審核紀錄外，如本站收到來自司法單位、校方、同學、個人的內容移除請求，也將定期於此頁面公開。</p>

			<h2>來自 Facebook 的刪除紀錄</h2>
			<table class="ts striped table">
				<thead>
					<tr>
						<th>日期</th>
						<th>貼文編號</th>
						<th>內容節錄</th>
						<th>理由</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>2021 May 20</td>
						<td><a href="/post/2641" target="_blank">#靠清2641</a></td>
						<td>如果不靈的話送你一拳</td>
						<td>煽動暴力</td>
					</tr>
					<tr>
						<td>2021 May 08</td>
						<td><a href="/post/1990" target="_blank">#靠清1990</a></td>
						<td>清大潑精噁男</td>
						<td>騷擾霸凌</td>
					</tr>
					<tr>
						<td>2020 Sep 10</td>
						<td><a href="/post/155" target="_blank">#靠清155</a></td>
						<td>趁女友環島帶女生回家</td>
						<td>騷擾霸凌</td>
					</tr>
				</tbody>
			</table>

			<h2>因應 Facebook 政策自我審查</h2>
			<p>此清單中的貼文僅會從 Facebook、Instagram 下架，您仍然可以從 Telegram、Twitter、Plurk 看到原文。</p>
			<table class="ts striped table">
				<thead>
					<tr>
						<th>日期</th>
						<th>內容節錄</th>
						<th>貼文編號</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>2021 May 15</td>
						<td>愛麗莎莎照片</td>
						<td><a href="/post/2626" target="_blank">#靠清2626</a></td>
					</tr>
				</tbody>
			</table>

			<h2>申訴處理結果</h2>
			<table class="ts striped table">
				<thead>
					<tr>
						<th>日期</th>
						<th>貼文編號</th>
						<th>處理方式</th>
						<th>理由</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>2021 May 15</td>
						<td><a href="/post/2289" target="_blank">#靠清2289</a></td>
						<td>刪除 Facebook 留言 + 停用留言功能</td>
						<td>侵犯個人隱私</td>
					</tr>
					<tr>
						<td>2021 Mar 23</td>
						<td><a href="/post/1284" target="_blank">#靠清1284</a></td>
						<td>移除各平台貼文</td>
						<td>不實訊息（清大孔子學院）</td>
					</tr>
					<tr>
						<td>2021 Mar 05</td>
						<td><a href="/post/934" target="_blank">#靠清934</a></td>
						<td>移除各平台貼文</td>
						<td>不實訊息（梅竹賽排球隊聲明）</td>
					</tr>
					<tr>
						<td>2020 Nov 26</td>
						<td><a href="/post/570" target="_blank">#靠清570</a></td>
						<td>圖片補馬賽克、移除 Facebook 貼文</td>
						<td>未經提醒/同意拍攝人像，見 <a href="/post/571" target="_blank">#靠清571</a></td>
					</tr>
				</tbody>
			</table>

			<h2>請求刪除紀錄</h2>
			<p>校方定義不限於正式信函通知，包含各處室、教職員工；此處同學僅計算交清在學學生，他校學生列入個人計算。此表格不包含各社群平台檢舉下架貼文。</p>
			<table class="ts striped table">
				<thead>
					<tr>
						<th>月份</th>
						<th>校方請求數</th>
						<th>同學請求數</th>
						<th>個人請求數</th>
						<th>實際受理貼文數</th>
					</tr>
				</thead>
				<tbody>
					<tr class="negative indicated"><td>2021 May</td><td>0</td><td>3</td><td>0</td><td>1</td></tr>
					<tr><td>2021 Apr</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
					<tr class="negative indicated"><td>2021 Mar</td><td>2</td><td>1</td><td>0</td><td>2</td></tr>
					<tr><td>2021 Feb</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
					<tr><td>2021 Jan</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
					<tr><td>2020 Dec</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
					<tr class="negative indicated"><td>2020 Nov</td><td>0</td><td>1</td><td>0</td><td>1</td></tr>
					<tr><td>2020 Oct</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
					<tr class="negative indicated"><td>2020 Sep</td><td>1</td><td>0</td><td>0</td><td>0</td></tr>
					<tr><td>2020 Aug</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
					<tr><td>2020 Jul</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
					<tr><td>2020 Jun</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
					<tr><td>2020 May</td><td>0</td><td>0</td><td>0</td><td>0</td></tr>
				</tbody>
			</table>

			<br>
			<p>收到任何刪除通知將人工更新至此頁面，在不造成二次傷害的前提下，本站會盡可能提供最多資訊，原則上收到請求後會在 7 天內公開揭露。</p>
			<p style="text-align: right;"><i>最後更新日期：2021 May 20</i></p>
		</div>
<?php include('includes/footer.php'); ?>
	</body>
</html>
