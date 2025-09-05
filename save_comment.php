<?php require 'config.php';
$post_id = (int)($_POST['post_id'] ?? 0);
$name = trim($_POST['name'] ?? '');
$body = trim($_POST['body'] ?? '');
if($post_id && $name && $body){
  $stmt = $mysqli->prepare("INSERT INTO comments(post_id,name,body) VALUES(?,?,?)");
  $stmt->bind_param('iss',$post_id,$name,$body);
  $stmt->execute();
  echo "<script>window.location.href='view.php?id=$post_id#comments';</script>"; exit;
}
echo "<script>window.location.href='view.php?id=$post_id&err=".rawurlencode('Please fill both fields')."#comments';</script>";
?>
