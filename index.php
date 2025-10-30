<?php
require 'db_connect.php';
$res = $conn->query("SELECT * FROM products");
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>MyShop - Daftar Produk</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
  <h1><a href="index.php">MyShop</a></h1>
  <nav>
    <?php if(isset($_SESSION['username'])): ?>
      Halo, <?= htmlspecialchars($_SESSION['username']) ?> | 
      <a href="cart.php">Keranjang</a> | 
      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a> | 
      <a href="register.php">Register</a>
    <?php endif; ?>
  </nav>
</header>

<div class="container">
  <h2>Daftar Produk</h2>
  <div class="products">
    <?php while($p = $res->fetch_assoc()): ?>
      <div class="product">
        <img src="uploads/<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p>Rp <?= number_format($p['price'],0,',','.') ?></p>
        <form method="post" action="cart.php">
          <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
          <input type="number" name="qty" value="1" min="1">
          <button name="add">Tambah ke Keranjang</button>
        </form>
      </div>
    <?php endwhile; ?>
  </div>
</div>

</body>
</html>
