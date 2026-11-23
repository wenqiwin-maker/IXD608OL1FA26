<?php
include "lib/php/functions.php";

$items = getCartItems();
if (count($items) == 0) {
	header("Location: product_cart.php");
	exit;
}

$totals = getTotals($items);
$methods = shippingMethods();
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
					<?php foreach ($methods as $value => $method) { ?>
						<label class="ship-option">
							<input type="radio" name="shipping" value="<?php echo $value ?>" data-cost="<?php echo $method->cost ?>" <?php echo $value == "standard" ? "checked" : "" ?>>
							<span class="flex-stretch"><strong><?php echo $method->name ?></strong><br><span class="supporting">Arrives by <?php echo date("D, F j", strtotime("+" . $method->days . " days")) ?></span></span>
							<span><?php echo $method->cost == 0 ? "Free" : money($method->cost) ?></span>
						</label>
					<?php } ?>

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
					<div class="card soft" id="summary" data-subtotal="<?php echo $totals->subtotal ?>" data-tax="<?php echo $totals->tax ?>">
						<div class="display-flex flex-align-center">
							<h4 class="flex-stretch">Order summary</h4>
							<span><?php echo cartCount() ?> <?php echo cartCount() == 1 ? "item" : "items" ?></span>
						</div>
						<?php foreach ($items as $item) { ?>
							<div class="cart-item">
								<img src="<?php echo $item->product->image ?>" alt="<?php echo $item->product->name ?>">
								<div class="flex-stretch">
									<strong><?php echo $item->product->name ?></strong>
									<?php if (count($item->choices) > 0) { ?>
										<p class="supporting"><?php echo choicesText($item->choices) ?></p>
									<?php } ?>
									<p class="supporting">Qty <?php echo $item->amount ?></p>
								</div>
								<div class="price"><?php echo money($item->total) ?></div>
							</div>
						<?php } ?>
						<table class="table">
							<tbody>
								<tr><td>Subtotal</td><td><?php echo money($totals->subtotal) ?></td></tr>
								<tr><td>Shipping</td><td id="shipping">Complimentary</td></tr>
								<tr><td>Tax</td><td><?php echo money($totals->tax) ?></td></tr>
								<tr><td><strong>Total</strong></td><td><strong id="total"><?php echo money($totals->total) ?></strong></td></tr>
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
	</div>

	<?php include "parts/footer.php" ?>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="js/checkout.js?v=<?php echo filemtime("js/checkout.js") ?>"></script>
</body>
</html>
