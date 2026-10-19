<?php
include "lib/php/data.php";
include "lib/php/functions.php";
$product = $products[0];
$tax = 5;
$total = $product->price + $tax;
$page_title = "Checkout";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<p><a href="product_cart.php">&larr; Back to shopping bag</a></p>
		<h1>Checkout</h1>
		<form action="product_confirmation.php" method="post">
			<div class="grid gap">
				<div class="col-xs-12 col-md-8">
					<h3>Contact</h3>
					<div class="form-control">
						<label class="form-label" for="email">Email address</label>
						<input class="form-input" type="email" id="email" name="email">
					</div>
					<h3>Shipping address</h3>
					<div class="form-control">
						<label class="form-label" for="street">Street address</label>
						<input class="form-input" type="text" id="street" name="street">
					</div>
				</div>
				<div class="col-xs-12 col-md-4">
					<div class="card soft">
						<h4>Order summary</h4>
						<p><strong><?php echo $product->name ?></strong> <?php echo money($product->price) ?></p>
						<p>Tax <?php echo money($tax) ?></p>
						<p><strong>Total <?php echo money($total) ?></strong></p>
						<div class="form-control"><button type="submit" class="form-button primary">Place order &rsaquo;</button></div>
					</div>
				</div>
			</div>
		</form>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
