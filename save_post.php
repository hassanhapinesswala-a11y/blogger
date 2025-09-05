<?php require 'config.php'; require 'helpers.php';
$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? '');
$category = trim($_POST['category'] ?? '');
$content = trim($_POST['content'] ?? '');
if($title && $author && $category && $content){
  $slug = make_slug($title);
  $stmt = $mysqli->prepare("INSERT INTO posts(title,slug,author,category,content) VALUES(?,?,?,?,?)");
  $stmt->bind_param('sssss',$title,$slug,$author,$category,$content);
  $stmt->execute();
  $id = $stmt->insert_id;
  echo "<script>window.location.href='view.php?id=$id&msg=".rawurlencode('Post published!')."';</script>";
  exit;
}
echo "<script>window.location.href='create.php?err=".rawurlencode('Please fill all fields')."';</script>";
?>
