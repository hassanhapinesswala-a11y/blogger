<?php require 'config.php'; require 'helpers.php';
$c = trim($_GET['c'] ?? '');
if(!in_array($c, categories(), true)){
  echo "<script>window.location.href='index.php?err=".rawurlencode('Unknown category')."';</script>"; exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?=esc($c)?> — Blogger-Style</title>
<style>
  :root{--bg:#0b1220;--card:#0f172a;--muted:#94a3b8;--text:#e5e7eb}
  body{margin:0;background:var(--bg);color:var(--text);font-family:system-ui,-apple-system,Segoe UI,Roboto}
  .container{max-width:1000px;margin:20px auto;padding:0 16px}
  .post{padding:16px;border:1px solid #243244;background:#0b1220;border-radius:18px;margin:10px 0}
  .post h3{margin:0 0 8px}
  .meta{font-size:12px;color:var(--muted)}
</style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <h2 style="margin:0 0 10px">Category: <?=esc($c)?></h2>
  <?php
    $stmt = $mysqli->prepare("SELECT * FROM posts WHERE category=? ORDER BY created_at DESC");
    $stmt->bind_param('s',$c);
    $stmt->execute();
    $res = $stmt->get_result();
    if($res && $res->num_rows){
      while($p = $res->fetch_assoc()): ?>
        <article class="post">
          <h3><a href="view.php?id=<?=$p['id']?>" style="color:#e2e8f0;text-decoration:none"><?=esc($p['title'])?></a></h3>
          <div class="meta">By <?=esc($p['author'])?> • <?=date('M d, Y', strtotime($p['created_at']))?></div>
        </article>
      <?php endwhile;
    } else { echo '<p>No posts in this category yet.</p>'; }
  ?>
</div>
</body>
</html>
 
