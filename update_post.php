<?php require 'config.php'; require 'helpers.php';
$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$author = trim($_POST['author'] ?? '');
$category = trim($_POST['category'] ?? '');
$content = trim($_POST['content'] ?? '');
if($id && $title && $author && $category && $content){
  $stmt = $mysqli->prepare("UPDATE posts SET title=?, author=?, category=?, content=?, updated_at=NOW() WHERE id=?");
  $stmt->bind_param('ssssi',$title,$author,$category,$content,$id);
  $stmt->execute();
  echo "<script>window.location.href='view.php?id=$id&msg=".rawurlencode('Post updated!')."';</script>"; exit;
}
echo "<script>window.location.href='edit.php?id=$id&err=".rawurlencode('Please fill all fields')."';</script>";
?>
