<?php require 'config.php'; require 'helpers.php';
$id = (int)($_GET['id'] ?? 0);
$p = null;
if($id){
  $res = $mysqli->query("SELECT * FROM posts WHERE id=$id");
  $p = $res? $res->fetch_assoc() : null;
}
if(!$p){
  echo "<script>window.location.href='index.php?err=".rawurlencode('Post not found')."';</script>"; exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?=esc($p['title'])?> — Blogger-Style</title>
<style>
  :root{--bg:#0b1220;--panel:#0f172a;--text:#e5e7eb;--muted:#94a3b8;--accent:#22d3ee;--accent2:#a78bfa}
  body{margin:0;background:var(--bg);color:var(--text);font-family:system-ui,-apple-system,Segoe UI,Roboto}
  .container{max-width:900px;margin:20px auto;padding:0 16px}
  .card{background:var(--panel);border:1px solid #1f2937;border-radius:20px;box-shadow:0 8px 25px rgba(0,0,0,.25);padding:20px}
  .meta{color:var(--muted);font-size:13px;margin-top:8px}
  .content{line-height:1.7;margin-top:14px}
  .content img{max-width:100%;border-radius:12px}
  .actions{display:flex;gap:10px;margin-top:14px}
  .btn{padding:10px 12px;border-radius:12px;border:1px solid #334155;background:#0b1220;color:#e2e8f0;text-decoration:none}
  .btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#06111a;border:0;font-weight:800}
  .comments .item{border-top:1px dashed #334155;padding:12px 0}
  .related{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
  @media(max-width:800px){.related{grid-template-columns:1fr}}
</style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <article class="card">
    <h1 style="margin:0"><?=esc($p['title'])?></h1>
    <div class="meta">
      By <b><?=esc($p['author'])?></b> • <?=date('M d, Y', strtotime($p['created_at']))?> • 
      <a href="category.php?c=<?=urlencode($p['category'])?>" style="color:#93c5fd;text-decoration:none"><?=esc($p['category'])?></a>
    </div>
    <div class="content"><?= $p['content'] ?></div>
    <div class="actions">
      <a class="btn btn-primary" href="edit.php?id=<?=$p['id']?>">Edit</a>
      <a class="btn" href="delete_post.php?id=<?=$p['id']?>" onclick="return confirm('Delete this post?')">Delete</a>
    </div>
  </article>
 
  <section class="card comments" style="margin-top:16px" id="comments">
    <h3 style="margin:0 0 8px">Comments</h3>
    <?php
      $cres = $mysqli->query("SELECT * FROM comments WHERE post_id=".$p['id']." ORDER BY created_at DESC");
      if($cres && $cres->num_rows){
        while($c = $cres->fetch_assoc()): ?>
          <div class="item">
            <div style="font-weight:700"><?=esc($c['name'])?></div>
            <div style="color:#cbd5e1;white-space:pre-wrap"><?=esc($c['body'])?></div>
            <div class="meta"><?=date('M d, Y H:i', strtotime($c['created_at']))?></div>
          </div>
        <?php endwhile;
      } else {
        echo '<p style="color:#cbd5e1">No comments yet. Be the first to share your thoughts!</p>';
      }
    ?>
    <form method="post" action="save_comment.php" style="margin-top:8px">
      <input type="hidden" name="post_id" value="<?=$p['id']?>" />
      <label>Your name</label>
      <input name="name" required placeholder="John Doe" style="width:100%;padding:10px;border-radius:12px;border:1px solid #334155;background:#0b1220;color:#e2e8f0" />
      <label style="display:block;margin-top:8px">Comment</label>
      <textarea name="body" required rows="3" placeholder="Type your comment…" style="width:100%;padding:10px;border-radius:12px;border:1px solid #334155;background:#0b1220;color:#e2e8f0"></textarea>
      <button class="btn btn-primary" type="submit" style="margin-top:8px">Post Comment</button>
    </form>
  </section>
 
  <section class="card" style="margin-top:16px">
    <h3 style="margin:0 0 8px">Related Posts</h3>
    <div class="related">
      <?php
        $rel = $mysqli->prepare("SELECT id,title,created_at FROM posts WHERE category=? AND id<>? ORDER BY created_at DESC LIMIT 4");
        $rel->bind_param('si', $p['category'], $p['id']);
        $rel->execute();
        $r = $rel->get_result();
        if($r && $r->num_rows){
          while($row = $r->fetch_assoc()): ?>
            <a class="btn" style="display:block" href="view.php?id=<?=$row['id']?>">
              <div style="font-weight:700;margin-bottom:6px"><?=esc($row['title'])?></div>
              <div class="meta"><?=date('M d, Y', strtotime($row['created_at']))?></div>
            </a>
          <?php endwhile;
        } else {
          echo '<p style="color:#cbd5e1">No related posts yet.</p>';
        }
      ?>
    </div>
  </section>
</div>
</body>
</html>
