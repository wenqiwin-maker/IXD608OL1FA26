<?php
include "lib/php/functions.php";

// Prototype: checks out the product from ?id= (product 1 if none)
$product = getProductFromUrl(1);
$page_title = "Checkout";

if ($product) {
	$tax = round($product->price * 0.07, 2);
}
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<p><a href="product_cart.php<?php echo $product ? '?id=' . $product->id : '' ?>">&larr; Back to shopping bag</a></p>
		<h1>Checkout</h1>

		<?php if (!$product) { ?>
			<p>Your bag is empty. <a href="product_list.php">Continue shopping &rsaquo;</a></p>
		<?php } else { ?>

		<form action="product_confirmation.php" method="post">
			<input type="hidden" name="id" value="<?php echo $product->id ?>">
			<div class="grid gap">
				<div class="col-xs-12 col-md-8">

					<h3>Contact</h3>
					<div class="form-control">
						<label class="form-label" for="email">Email address</label>
						<input class="form-input" type="email" id="email" name="email" required>
					</div>

					<hr>
					<h3>Shipping address</h3>
					<div class="form-control">
						<label class="form-label" for="first_name">First name</label>
						<input class="form-input" type="text" id="first_name" name="first_name" required>
					</div>
					<div class="form-control">
						<label class="form-label" for="last_name">Last name</label>
						<input class="form-input" type="text" id="last_name" name="last_name" required>
					</div>
					<div class="form-control">
						<label class="form-label" for="street">Street address</label>
						<input class="form-input" type="text" id="street" name="street" required>
					</div>
					<div class="form-control">
						<label class="form-label" for="city">City</label>
						<input class="form-input" type="text" id="city" name="city" required>
					</div>
					<div class="form-control">
						<label class="form-label" for="state">State</label>
						<input class="form-input" type="text" id="state" name="state" required>
					</div>
					<div class="form-control">
						<label class="form-label" for="zip">ZIP code</label>
						<input class="form-input" type="text" id="zip" name="zip" required>
					</div>
					<div class="form-control">
						<label class="form-label" for="country">Country</label>
						<div class="form-select">
							<select id="country" name="country">
								<option>United States</option>
								<option>Canada</option>
								<option>Mexico</option>
							</select>
						</div>
					</div>

					<hr>
					<h3>Shipping method</h3>
					<label class="ship-option">
						<input type="radio" name="shipping" value="standard" data-cost="0" checked>
						<span class="flex-stretch"><strong>Standard</strong><br><span class="supporting">Arrives by <?php echo date("D, F j", strtotime("+5 days")) ?></span></span>
						<span>Free</span>
					</label>
					<label class="ship-option">
						<input type="radio" name="shipping" value="express" data-cost="20">
						<span class="flex-stretch"><strong>Express</strong><br><span class="supporting">Arrives by <?php echo date("D, F j", strtotime("+4 days")) ?></span></span>
						<span>$20.00</span>
					</label>
					<label class="ship-option">
						<input type="radio" name="shipping" value="priority" data-cost="30">
						<span class="flex-stretch"><strong>Priority</strong><br><span class="supporting">Arrives by <?php echo date("D, F j", strtotime("+3 days")) ?></span></span>
						<span>$30.00</span>
					</label>

					<hr>
					<h3>Payment</h3>
					<!-- no name on these inputs, so the card details are never sent anywhere -->
					<div class="form-control">
						<label class="form-label" for="card">Card number</label>
						<input class="form-input" type="text" id="card" inputmode="numeric" placeholder="1234 5678 9012 3456" required>
					</div>
					<div class="form-control">
						<label class="form-label" for="expiry">Expiration</label>
						<input class="form-input" type="text" id="expiry" placeholder="MM / YY" required>
					</div>
					<div class="form-control">
						<label class="form-label" for="cvc">Security code</label>
						<input class="form-input" type="text" id="cvc" inputmode="numeric" required>
					</div>
				</div>

				<div class="col-xs-12 col-md-4">
					<div class="card soft" id="summary" data-price="<?php echo $product->price ?>" data-tax="<?php echo $tax ?>">
						<div class="display-flex flex-align-center">
							<h4 class="flex-stretch">Order summary</h4>
							<span>1 item</span>
						</div>
						<div class="cart-item">
							<img src="<?php echo $product->image ?>" alt="<?php echo $product->name ?>">
							<div class="flex-stretch">
								<strong><?php echo $product->name ?></strong>
								<p class="supporting">Qty 1</p>
							</div>
							<div class="price"><?php echo money($product->price) ?></div>
						</div>
						<table class="table">
							<tbody>
								<tr><td>Subtotal</td><td><?php echo money($product->price) ?></td></tr>
								<tr><td>Shipping</td><td id="shipping">Complimentary</td></tr>
								<tr><td>Tax</td><td><?php echo money($tax) ?></td></tr>
								<tr><td><strong>Total</strong></td><td><strong id="total"><?php echo money($product->price + $tax) ?></strong></td></tr>
							</tbody>
						</table>
						<div class="form-control">
							<button type="submit" class="form-button primary button-split"><span>Place order</span><span>&rsaquo;</span></button>
						</div>
						<p class="supporting center">Secure payment &middot; 30-day returns</p>
					</div>
				</div>
			</div>
		</form>

		<?php } ?>
	</div>

	<?php include "parts/footer.php" ?>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="js/checkout.js?v=<?php echo filemtime("js/checkout.js") ?>"></script>
</body>
</html>
