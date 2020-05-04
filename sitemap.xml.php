<?php
require_once('database.php');
$db = new MyDB();

header('Content-Type: text/xml');

$posts = $db->getPosts(0);
?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://x.nthu.io/</loc>
    <priority>1.00</priority>
  </url>
  <url>
	<loc>https://x.nthu.io/posts</loc>
    <changefreq>hourly</changefreq>
    <priority>1.00</priority>
  </url>
  <url>
    <loc>https://x.nthu.io/submit</loc>
    <priority>1.00</priority>
  </url>
  <url>
    <loc>https://x.nthu.io/review/DEMO</loc>
  </url>
  <url>
    <loc>https://x.nthu.io/ranking</loc>
    <changefreq>daily</changefreq>
  </url>
  <url>
    <loc>https://x.nthu.io/faq</loc>
  </url>
  <url>
    <loc>https://x.nthu.io/deleted</loc>
  </url>
  <url>
    <loc>https://x.nthu.io/policies</loc>
  </url>

<?php foreach ($posts as $post) { ?>
  <url><loc>https://x.nthu.io/post/<?= $post['id'] ?></loc></url>
<?php } ?>
</urlset>
