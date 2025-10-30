<?php
session_start();
require 'db_connect.php';

// Pastikan cart ada
if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// tambah produk ke keranjang
if(isset($_POST['add'])){
  $pid = (int)$_POST['product_id'];
  $qty = (int)$_POST['qty'];
  if(isset($_SESSION['cart'][$pid])) {
    $_SESSION['cart'][$pid] += $qty;
  } else {
    $_SESSION['cart'][$pid] = $qty;
  }
}

// update cart
if(isset($_POST['update'])){
  foreach($_POST['qty'] as $pid=>$qty){
    if($qty <= 0){
      unset($_SESSION['cart'][$pid]);
    } else {
      $_SESSION['cart'][$pid] = $qty;
    }
  }
}

// hapus produk tertentu
if(isset($_POST['remove'])){
  $pid = (int)$_POST['remove'];
  unset($_SESSION['cart'][$pid]);
}

// ambil produk di keranjang
$cart = $_SESSION['cart'];
if(empty($cart)){ 
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Kosong</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .empty-cart {
      background: white;
      padding: 40px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    }
    .empty-cart h2 {
      margin-bottom: 15px;
      color: #555;
    }
    .empty-cart a {
      display: inline-block;
      background: #ee4d2d;
      color: white;
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }
    .empty-cart a:hover {
      background: #d44126;
    }
  </style>
</head>
<body>
  <div class="empty-cart">
    <h2>Keranjang kamu masih kosong</h2>
    <a href="index.php">Mulai Belanja</a>
  </div>
</body>
</html>
<?php
  exit; 
}

$ids = implode(",", array_keys($cart));
$res = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
$products = [];
while($r = $res->fetch_assoc()) $products[$r['id']]=$r;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 0;
    }
    header {
      background: #ee4d2d;
      color: white;
      padding: 15px;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
    }
    .container {
      width: 80%;
      margin: 20px auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    table th, table td {
      border-bottom: 1px solid #ddd;
      padding: 12px;
      text-align: center;
    }
    table th {
      background: #fafafa;
    }
    input[type="number"] {
      width: 60px;
      padding: 5px;
      text-align: center;
    }
    .total {
      text-align: right;
      font-size: 18px;
      font-weight: bold;
      padding: 10px;
    }
    .actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    button {
      background: #ee4d2d;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 13px;
    }
    button:hover {
      background: #d44126;
    }
    .btn-remove {
      background: #e74c3c;
    }
    .btn-remove:hover {
      background: #c0392b;
    }
    a.checkout {
      background: #2ecc71;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 5px;
    }
    a.checkout:hover {
      background: #27ae60;
    }
  </style>
</head>
<body>
<header>Keranjang Belanja</header>
<div class="container">
  <form method="post">
    <table>
      <tr>
        <th>Produk</th>
        <th>Harga</th>
        <th>Qty</th>
        <th>Subtotal</th>
        <th>Aksi</th>
      </tr>
      <?php $total=0; foreach($cart as $pid=>$qty): 
        $p=$products[$pid]; 
        $sub=$p['price']*$qty; 
        $total+=$sub; ?>
      <tr>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td>Rp <?= number_format($p['price'],0,',','.') ?></td>
        <td><input type="number" name="qty[<?= $pid ?>]" value="<?= $qty ?>" min="1"></td>
        <td>Rp <?= number_format($sub,0,',','.') ?></td>
        <td>
          <button type="submit" name="remove" value="<?= $pid ?>" class="btn-remove">Hapus</button>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <div class="total">Total: Rp <?= number_format($total,0,',','.') ?></div>
    <div class="actions">
      <button type="submit" name="update">Update Keranjang</button>
      <a href="checkout.php" class="checkout">Checkout</a>
    </div>
  </form>
</div>
</body>
</html>
