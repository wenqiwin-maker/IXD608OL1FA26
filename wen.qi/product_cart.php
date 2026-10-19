<?php
include "lib/php/data.php";
include "lib/php/functions.php";
$product = $products[0];
$qty = 1;
$subtotal = $product->price * $qty;
$total = $subtotal + 12;
$page_title = "Shopping bag";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<h1>Shopping bag</h1>
		<hr>
		<div class="grid gap">
			<div class="col-xs-12 col-md-8">
				<div class="cart-item">
					<a href="product_item.php?id=0"><img src="img/placeholder.png" alt="<?php echo $product->name ?>"></a>
					<div class="flex-stretch">
						<p class="eyebrow"><?php echo $product->origin ?></p>
						<h4><a href="product_item.php?id=0"><?php echo $product->name ?></a></h4>
						<p class="supporting">Qty: <?php echo $qty ?></p>
					</div>
					<div class="price"><?php echo money($subtotal) ?></div>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="card soft">
					<h4>Order summary</h4>
					<table class="table">
						<tbody>
							<tr><td>Subtotal</td><td><?php echo money($subtotal) ?></td></tr>
							<tr><td>Shipping</td><td><?php echo money(12) ?></td></tr>
							<tr><td><strong>Estimated total</strong></td><td><strong><?php echo money($total) ?></strong></td></tr>
						</tbody>
					</table>
					<div class="form-control">
						<a href="product_checkout.php" class="form-button primary">Continue to checkout &rsaquo;</a>
					</div>
					<p class="supporting center">Secure checkout · 30-day returns</p>
				</div>
			</div>
		</div>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
