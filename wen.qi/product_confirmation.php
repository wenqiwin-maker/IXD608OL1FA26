<?php
include "lib/php/functions.php";

$page_title = "Order Confirmed";

$order = null;
$methods = shippingMethods();
$countries = ["United States", "Canada", "Mexico"];
$items = getCartItems();

if (count($_POST) > 0 && count($items) > 0) {
	$method = "standard";
	if (isset($_POST["shipping"]) && isset($methods[$_POST["shipping"]])) {
		$method = $_POST["shipping"];
	}
	$country = "United States";
	if (isset($_POST["country"]) && in_array($_POST["country"], $countries)) {
		$country = $_POST["country"];
	}

	$order = (object)[
		"number" => "FF-" . rand(10000, 99999),
		"email" => isset($_POST["email"]) ? $_POST["email"] : "",
		"country" => $country,
		"arrival" => date("F j", strtotime("+" . $methods[$method]->days . " days")),
		"items" => $items,
		"totals" => getTotals($items, $methods[$method]->cost)
	];

	$_SESSION["last_order"] = $order;
	clearCart();
} else if (isset($_SESSION["last_order"])) {
	$order = $_SESSION["last_order"];
}

if ($order) {
	$ordered_ids = array_map(function($item) {
		return (int) $item->product->id;
	}, $order->items);
	$more = fetchAll("SELECT * FROM products WHERE id NOT IN (" . implode(",", $ordered_ids) . ") ORDER BY featured DESC, id LIMIT 3");
}
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">

		<?php if (!$order) { ?>

			<div class="center">
				<h2>Nothing to confirm yet.</h2>
				<p><a href="product_list.php">Browse the collection &rsaquo;</a></p>
			</div>

		<?php } else { ?>

		<div class="center">
			<div class="check-circle">&#10003;</div>
			<p class="eyebrow">Order confirmed</p>
			<h1>Your order is in.</h1>
			<?php if ($order->email != "") { ?>
				<p>Thank you for bringing a piece of the world home. A confirmation has been sent to <?php echo htmlspecialchars($order->email) ?>.</p>
			<?php } ?>
		</div>

		<div class="card hard">
			<div class="grid gap order-strip">
				<div class="col-xs-6 col-md-3"><span class="supporting">Order number</span><br><strong><?php echo $order->number ?></strong></div>
				<div class="col-xs-6 col-md-3"><span class="supporting">Estimated arrival</span><br><strong><?php echo $order->arrival ?></strong></div>
				<div class="col-xs-6 col-md-3"><span class="supporting">Order total</span><br><strong><?php echo money($order->totals->total) ?></strong></div>
				<div class="col-xs-6 col-md-3"><span class="supporting">Delivering to</span><br><strong><?php echo htmlspecialchars($order->country) ?></strong></div>
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

			<?php foreach ($order->items as $item) { ?>
				<div class="cart-item">
					<img src="<?php echo $item->product->image ?>" alt="<?php echo $item->product->name ?>">
					<div class="flex-stretch">
						<strong><?php echo $item->product->name ?></strong>
						<p class="eyebrow"><?php echo $item->product->origin ?></p>
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
					<tr><td>Subtotal</td><td><?php echo money($order->totals->subtotal) ?></td></tr>
					<tr><td>Shipping</td><td><?php echo $order->totals->shipping == 0 ? "Free" : money($order->totals->shipping) ?></td></tr>
					<tr><td>Tax</td><td><?php echo money($order->totals->tax) ?></td></tr>
					<tr><td><strong>Total</strong></td><td><strong><?php echo money($order->totals->total) ?></strong></td></tr>
				</tbody>
			</table>

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
