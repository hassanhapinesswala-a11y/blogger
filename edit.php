<?php require 'config.php'; require 'helpers.php';
$id = (int)($_GET['id'] ?? 0);
$p = null;
if($id){
  $res = $mysqli->query("SELECT * FROM posts WHERE id=$id");
  $p = $res? $res->fetch_assoc() : null;
}
if(!$p){ echo "<script>window.location.href='index.php?err=".rawurlencode('Post not found')."';</script>"; exit; }
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit — <?=esc($p['title'])?></title>
<style>
  :root{--bg:#0b1220;--panel:#0f172a;--muted:#94a3b8;--text:#e5e7eb;--accent:#22d3ee;--accent2:#a78bfa}
  body{margin:0;background:var(--bg);color:var(--text);font-family:system-ui,-apple-system,Segoe UI,Roboto}
  .container{max-width:900px;margin:20px auto;padding:0 16px}
  .card{background:var(--panel);border:1px solid #1f2937;border-radius:20px;box-shadow:0 8px 25px rgba(0,0,0,.25);padding:18px}
  label{display:block;margin:8px 0 6px;color:#cbd5e1}
  input,select{width:100%;padding:12px;border-radius:12px;border:1px solid #334155;background:#0b1220;color:#e2e8f0}
  .toolbar{display:flex;flex-wrap:wrap;gap:6px;margin:10px 0}
  .toolbar button{border:1px solid #334155;background:#0b1220;color:#e2e8f0;padding:8px 10px;border-radius:10px;cursor:pointer}
  .editor{min-height:280px;background:#0b1220;border:1px dashed #334155;border-radius:14px;padding:14px;line-height:1.6}
  .actions{display:flex;gap:10px;margin-top:12px}
  .btn-primary{background:linear-gradient(135deg,var(--accent),var(--accent2));color:#06111a;border:0;font-weight:800}
  .btn{padding:12px 14px;border-radius:12px;border:1px solid #334155;background:#0b1220;color:#e2e8f0;cursor:pointer}
</style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container">
  <form class="card" method="post" action="update_post.php" onsubmit="syncContent()">
    <input type="hidden" name="id" value="<?=$p['id']?>">
    <label>Title</label>
    <input required name="title" value="<?=esc($p['title'])?>" />
    <label>Author</label>
    <input required name="author" value="<?=esc($p['author'])?>" />
    <label>Category</label>
    <select name="category" required>
      <?php foreach(categories() as $c): ?>
        <option value="<?=esc($c)?>" <?=$p['category']===$c?'selected':''?>><?=esc($c)?></option>
      <?php endforeach; ?>
    </select>
 
    <label>Content</label>
    <div class="toolbar">
      <button type="button" onclick="exec('bold')"><b>B</b></button>
      <button type="button" onclick="exec('italic')"><i>I</i></button>
      <button type="button" onclick="exec('underline')"><u>U</u></button>
      <button type="button" onclick="exec('insertUnorderedList')">• List</button>
      <button type="button" onclick="exec('insertOrderedList')">1. List</button>
      <button type="button" onclick="execBlock('H2')">H2</button>
      <button type="button" onclick="execBlock('H3')">H3</button>
      <button type="button" onclick="link()">Link</button>
      <button type="button" onclick="image()">Image</button>
      <button type="button" onclick="exec('formatBlock','blockquote')">Quote</button>
    </div>
    <div id="editor" class="editor" contenteditable="true"><?= $p['content'] ?></div>
    <textarea name="content" id="content" hidden></textarea>
    <div class="actions">
      <button class="btn btn-primary" type="submit">Update</button>
      <a class="btn" href="view.php?id=<?=$p['id']?>">Cancel</a>
    </div>
  </form>
</div>
<script>
  function exec(cmd, val=null){document.execCommand(cmd,false,val)}
  function execBlock(tag){document.execCommand('formatBlock',false,tag)}
  function link(){const url = prompt('Enter URL'); if(url) document.execCommand('createLink', false, url)}
  function image(){const url = prompt('Enter Image URL'); if(url) document.execCommand('insertImage', false, url)}
  function syncContent(){document.getElementById('content').value = document.getElementById('editor').innerHTML.trim()}
</script>
</body>
</html>
