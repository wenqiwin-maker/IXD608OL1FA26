<?php
include "lib/php/functions.php";

$page_title = "Order Confirmed";

// Nothing is saved: this page only shows what the checkout form just sent.
$product = null;
if (isset($_POST["id"])) {
	$id = (int) $_POST["id"];
	$product = fetchOne("SELECT * FROM products WHERE id = $id");
}

if ($product) {
	// shipping choices: cost and days until it arrives
	$shipping_costs = ["standard" => 0, "express" => 20, "priority" => 30];
	$shipping_days = ["standard" => 5, "express" => 4, "priority" => 3];
	$method = "standard";
	if (isset($_POST["shipping"]) && isset($shipping_costs[$_POST["shipping"]])) {
		$method = $_POST["shipping"];
	}

	$tax = round($product->price * 0.07, 2);
	$total = $product->price + $tax + $shipping_costs[$method];
	$arrival = date("F j", strtotime("+" . $shipping_days[$method] . " days"));
	$country = isset($_POST["country"]) ? $_POST["country"] : "United States";
	$order_number = "FF-" . rand(10000, 99999);

	$more = fetchAll("SELECT * FROM products WHERE id != " . $product->id . " ORDER BY featured DESC, id LIMIT 3");
}
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">

		<?php if (!$product) { ?>

			<div class="center">
				<h2>Nothing to confirm yet.</h2>
				<p><a href="product_list.php">Browse the collection &rsaquo;</a></p>
			</div>

		<?php } else { ?>

		<div class="center">
			<div class="check-circle">&#10003;</div>
			<p class="eyebrow">Order confirmed</p>
			<h1>Your order is in.</h1>
			<?php if (!empty($_POST["email"])) { ?>
				<p>Thank you for bringing a piece of <?php echo $product->origin ?> home. A confirmation has been sent to <?php echo htmlspecialchars($_POST["email"]) ?>.</p>
			<?php } ?>
		</div>

		<div class="card hard">
			<div class="grid gap order-strip">
				<div class="col-xs-6 col-md-3"><span class="supporting">Order number</span><br><strong><?php echo $order_number ?></strong></div>
				<div class="col-xs-6 col-md-3"><span class="supporting">Estimated arrival</span><br><strong><?php echo $arrival ?></strong></div>
				<div class="col-xs-6 col-md-3"><span class="supporting">Order total</span><br><strong><?php echo money($total) ?></strong></div>
				<div class="col-xs-6 col-md-3"><span class="supporting">Delivering to</span><br><strong><?php echo htmlspecialchars($country) ?></strong></div>
			</div>
			<hr>
			<p class="eyebrow">Order progress</p>
			<div class="display-flex flex-align-center">
				<h3 class="flex-stretch">Preparing your objects</h3>
				<span class="supporting">Step 1 of 3</span>
			</div>
			<div class="progress"><div class="progress-bar"></div></div>
			<div class="display-flex progress-labels">
				<strong class="flex-stretch">Order confirmed</strong>
				<span class="flex-stretch center">In transit</span>
				<span class="flex-stretch" style="text-align:right">Delivered</span>
			</div>

			<div class="cart-item">
				<img src="<?php echo $product->image ?>" alt="<?php echo $product->name ?>">
				<div class="flex-stretch">
					<strong><?php echo $product->name ?></strong>
					<p class="eyebrow"><?php echo $product->origin ?></p>
					<p class="supporting">Qty 1</p>
				</div>
				<div class="price"><?php echo money($product->price) ?></div>
			</div>

			<div class="display-flex flex-align-center">
				<a href="product_list.php" class="form-button primary flex-none auto-width">Continue shopping</a>
				<div class="flex-stretch"></div>
				<a href="about.php#shipping" class="link-underline flex-none nowrap">Track shipment &rsaquo;</a>
			</div>
		</div>

		<p class="eyebrow">You may also like</p>
		<div class="display-flex flex-align-center">
			<h2 class="flex-stretch">More objects with a story</h2>
			<a href="product_list.php" class="link-underline nowrap">View all objects &rsaquo;</a>
		</div>
		<div class="grid gap">
			<?php foreach ($more as $item) { ?>
				<div class="col-xs-6 col-md-4"><?php productCard($item) ?></div>
			<?php } ?>
		</div>

		<?php } ?>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
