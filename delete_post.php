<?php require 'config.php';
$id = (int)($_GET['id'] ?? 0);
if($id){
  $mysqli->query("DELETE FROM posts WHERE id=$id");
  echo "<script>window.location.href='index.php?msg=".rawurlencode('Post deleted')."';</script>"; exit;
}
echo "<script>window.location.href='index.php?err=".rawurlencode('Invalid post')."';</script>";
?>
