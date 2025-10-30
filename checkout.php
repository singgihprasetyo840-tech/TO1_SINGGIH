<?php
require 'db_connect.php';
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }
$cart = $_SESSION['cart'] ?? [];
if(empty($cart)){ echo "Keranjang kosong."; exit; }

$ids = implode(",", array_keys($cart));
$res = $conn->query("SELECT * FROM products WHERE id IN ($ids)");
$products = []; $total=0;
while($r = $res->fetch_assoc()){ 
  $products[$r['id']]=$r;
}
foreach($cart as $pid=>$qty){
  $total += $products[$pid]['price'] * $qty;
}

if(isset($_POST['confirm'])){
  // simpan order
  $stmt = $conn->prepare("INSERT INTO orders(user_id,total) VALUES(?,?)");
  $stmt->bind_param("id", $_SESSION['user_id'],$total);
  $stmt->execute();
  $order_id = $stmt->insert_id;

  $stmtItem = $conn->prepare("INSERT INTO order_items(order_id,product_id,qty,price) VALUES(?,?,?,?)");
  foreach($cart as $pid=>$qty){
    $price = $products[$pid]['price'];
    $stmtItem->bind_param("iiid",$order_id,$pid,$qty,$price);
    $stmtItem->execute();
  }
  unset($_SESSION['cart']);
  $success = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Checkout</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f5f5f5;
      margin: 0;
      padding: 0;
      color: #333;
    }
    .checkout-container {
      max-width: 900px;
      margin: 30px auto;
      background: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }
    h2 {
      font-size: 24px;
      margin-bottom: 20px;
      color: #ff5722;
      border-bottom: 2px solid #ff5722;
      padding-bottom: 10px;
    }
    .cart-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .cart-table th, .cart-table td {
      padding: 12px;
      border: 1px solid #eee;
      text-align: center;
    }
    .cart-table th {
      background: #fafafa;
    }
    .cart-total-row td {
      font-weight: bold;
      background: #f9f9f9;
    }
    .btn {
      display: inline-block;
      padding: 10px 18px;
      background: #ff5722;
      color: #fff;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      text-decoration: none;
      font-size: 14px;
      margin-top: 10px;
    }
    .btn:hover {
      background: #e64a19;
    }
    .success-box {
      text-align: center;
      padding: 40px 20px;
      background: #f9f9f9;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .success-box h2 {
      color: #28a745;
      font-size: 26px;
      margin-bottom: 15px;
    }
    .success-box p {
      font-size: 16px;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
<div class="checkout-container">
<?php if(!empty($success)): ?>
  <div class="success-box">
    <h2>Pesanan Berhasil!</h2>
    <p>Terima kasih, pesanan kamu sudah disimpan.<br>
    Nomor Order: <b>#<?= $order_id ?></b></p>
    <a href="index.php" class="btn">Kembali ke Beranda</a>
  </div>
<?php else: ?>
  <h2>Checkout</h2>
  <table class="cart-table">
    <tr>
      <th>Produk</th>
      <th>Harga</th>
      <th>Qty</th>
      <th>Subtotal</th>
    </tr>
    <?php foreach($cart as $pid=>$qty): 
      $subtotal = $products[$pid]['price'] * $qty;
    ?>
    <tr>
      <td><?= htmlspecialchars($products[$pid]['name']) ?></td>
      <td>Rp <?= number_format($products[$pid]['price'],0,',','.') ?></td>
      <td><?= $qty ?></td>
      <td>Rp <?= number_format($subtotal,0,',','.') ?></td>
    </tr>
    <?php endforeach; ?>
    <tr class="cart-total-row">
      <td colspan="3">Total</td>
      <td>Rp <?= number_format($total,0,',','.') ?></td>
    </tr>
  </table>
  <form method="post">
    <button type="submit" name="confirm" class="btn">Konfirmasi & Simpan Pesanan</button>
  </form>
<?php endif; ?>
</div>
</body>
</html>
