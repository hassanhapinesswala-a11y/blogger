<?php
// Simple reusable header & nav (keeps internal CSS per page)
?>
<header class="site-header">
  <div class="wrap">
    <a href="index.php" class="brand">Blogger-Style</a>
    <nav class="nav">
      <a href="index.php">Home</a>
      <a href="create.php">Write</a>
      <?php require_once 'helpers.php'; foreach(categories() as $c): ?>
        <a href="category.php?c=<?=urlencode($c)?>"><?=esc($c)?></a>
      <?php endforeach; ?>
    </nav>
  </div>
</header>
<style>
  .site-header{position:sticky;top:0;background:linear-gradient(135deg,#111,#1f2937);color:#fff;z-index:50;border-bottom:1px solid #2d3748}
  .site-header .wrap{max-width:1100px;margin:auto;display:flex;align-items:center;justify-content:space-between;padding:14px 16px}
  .brand{font-weight:800;letter-spacing:.5px;text-decoration:none;color:#fff;font-size:22px}
  .nav a{color:#cbd5e1;text-decoration:none;margin:0 8px;padding:8px 10px;border-radius:12px;transition:.2s}
  .nav a:hover{background:#111827;color:#fff}
</style>
