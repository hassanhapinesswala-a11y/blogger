<?php require 'config.php'; require 'helpers.php'; ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Home — Blogger-Style</title>
<style>
  :root{--bg:#0b1220;--card:#0f172a;--muted:#94a3b8;--text:#e5e7eb;--accent:#22d3ee;--accent2:#a78bfa}
  *{box-sizing:border-box}
  body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,'Helvetica Neue',Arial; background:var(--bg); color:var(--text)}
  .container{max-width:1100px;margin:20px auto;padding:0 16px}
  .grid{display:grid;grid-template-columns:1.6fr .8fr;gap:22px}
  .card{background:var(--card);border:1px solid #1f2937;border-radius:20px;box-shadow:0 10px 25px rgba(0,0,0,.25);padding:18px}
  .search{display:flex;gap:8px}
  .search input{flex:1;padding:12px 14px;border-radius:12px;border:1px solid #334155;background:#0b1220;color:#e2e8f0}
  .search button{padding:12px 14px;border-radius:12px;border:0;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#06111a;font-weight:700;cursor:pointer}
  .posts{display:grid;grid-template-columns:repeat(2,1fr);gap:16px}
  .post{padding:16px;border:1px solid #243244;background:#0b1220;border-radius:18px;transition:.2s}
  .post:hover{transform:translateY(-2px);border-color:#334155}
  .post h3{margin:0 0 8px;font-size:20px}
  .meta{font-size:12px;color:var(--muted);display:flex;gap:8px;flex-wrap:wrap}
  .excerpt{color:#cbd5e1;line-height:1.5;margin-top:8px}
  .cat-list a{display:inline-block;margin:6px 6px 0 0;padding:8px 10px;border-radius:999px;background:#0b1220;border:1px solid #334155;color:#cbd5e1;text-decoration:none;font-size:13px}
  .side .card{position:sticky;top:84px}
  @media(max-width:900px){.grid{grid-template-columns:1fr}.posts{grid-template-columns:1fr}}
</style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <div class="grid">
    <main>
      <div class="card">
        <form class="search" method="get" action="index.php">
          <input type="text" name="q" placeholder="Search posts..." value="<?=esc($_GET['q'] ?? '')?>">
          <button type="submit">Search</button>
        </form>
      </div>
      <div class="posts">
        <?php
          $q = trim($_GET['q'] ?? '');
          $where = '1';
          if($q !== ''){
            $like = '%'.$mysqli->real_escape_string($q).'%';
            $where = "(title LIKE '$like' OR author LIKE '$like' OR content LIKE '$like' OR category LIKE '$like')";
          }
          $res = $mysqli->query("SELECT * FROM posts WHERE $where ORDER BY created_at DESC LIMIT 20");
          if($res && $res->num_rows){
            while($p = $res->fetch_assoc()): ?>
              <article class="post">
                <h3><a href="view.php?id=<?=$p['id']?>" style="color:#e2e8f0;text-decoration:none"><?=esc($p['title'])?></a></h3>
                <div class="meta"> 
                  <span>By <?=esc($p['author'])?></span>
                  <span>•</span>
                  <span><?=date('M d, Y', strtotime($p['created_at']))?></span>
                  <span>•</span>
                  <a href="category.php?c=<?=urlencode($p['category'])?>" style="color:#93c5fd;text-decoration:none"><?=esc($p['category'])?></a>
                </div>
                <p class="excerpt"><?=esc(excerpt($p['content']))?></p>
              </article>
            <?php endwhile;
          } else {
            echo '<div class="card" style="grid-column:1/-1">No posts yet. Be the first to <a href="create.php">write</a>!';
            if($q!=='') echo ' No results for <b>'.esc($q).'</b>.';
            echo '</div>';
          }
        ?>
      </div>
    </main>
    <aside class="side">
      <div class="card">
        <h3 style="margin:0 0 8px">Categories</h3>
        <div class="cat-list">
          <?php foreach(categories() as $c): ?>
            <a href="category.php?c=<?=urlencode($c)?>"><?=esc($c)?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="card" style="margin-top:16px">
        <h3 style="margin:0 0 8px">Write something ✍️</h3>
        <p style="color:#cbd5e1">Share your ideas with a beautiful editor.</p>
        <a href="create.php" style="display:inline-block;margin-top:8px;padding:10px 12px;border-radius:12px;background:linear-gradient(135deg,var(--accent),var(--accent2));color:#06111a;text-decoration:none;font-weight:700">Create a Post</a>
      </div>
    </aside>
  </div>
</div>
</body>
</html>
