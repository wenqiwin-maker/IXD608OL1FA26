<?php
include "lib/php/functions.php";

// The cart is a prototype: it shows the product from ?id= (product 1 if none)
$product = getProductFromUrl(1);
$page_title = "Shopping bag";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<h1>Shopping bag <small class="supporting" id="cart-count">1 item</small></h1>
		<hr>

		<?php if (!$product) { ?>
			<p>Your bag is empty. <a href="product_list.php">Continue shopping &rsaquo;</a></p>
		<?php } else { ?>

		<div id="empty-message" class="hidden">
			<p>Your bag is empty. <a href="product_list.php">Continue shopping &rsaquo;</a></p>
		</div>

		<div class="grid gap" id="cart-grid">
			<div class="col-xs-12 col-md-8">
				<div class="cart-item" data-price="<?php echo $product->price ?>" data-stock="<?php echo $product->stock ?>">
					<a href="product_item.php?id=<?php echo $product->id ?>"><img src="<?php echo $product->image ?>" alt="<?php echo $product->name ?>"></a>
					<div class="flex-stretch">
						<p class="eyebrow"><?php echo $product->origin ?></p>
						<h4><a href="product_item.php?id=<?php echo $product->id ?>"><?php echo $product->name ?></a></h4>
						<p class="supporting">Finish: <?php echo $product->finish ?></p>
						<div class="display-flex flex-align-center">
							<div class="stepper">
								<button type="button" class="qty-minus" aria-label="Fewer">&minus;</button>
								<span class="qty">1</span>
								<button type="button" class="qty-plus" aria-label="More">+</button>
							</div>
							<a href="#" class="remove link-underline">Remove</a>
						</div>
					</div>
					<div class="price line-price"><?php echo money($product->price) ?></div>
				</div>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="card soft">
					<div class="display-flex flex-align-center">
						<h4 class="flex-stretch">Order summary</h4>
						<span class="item-count">1 item</span>
					</div>
					<table class="table">
						<tbody>
							<tr><td>Subtotal</td><td id="subtotal"><?php echo money($product->price) ?></td></tr>
							<tr><td>Shipping</td><td id="shipping"><?php echo money(12) ?></td></tr>
							<tr><td><strong>Estimated total</strong></td><td><strong id="total"><?php echo money($product->price + 12) ?></strong></td></tr>
						</tbody>
					</table>
					<div class="form-control">
						<a href="product_checkout.php?id=<?php echo $product->id ?>" class="form-button primary button-split"><span>Continue to checkout</span><span>&rsaquo;</span></a>
					</div>
					<p class="supporting center">Secure checkout &middot; 30-day returns</p>
				</div>
			</div>
		</div>

		<?php } ?>
	</div>

	<?php include "parts/footer.php" ?>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="js/cart.js?v=<?php echo filemtime("js/cart.js") ?>"></script>
</body>
</html>
