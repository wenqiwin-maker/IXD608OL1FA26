<?php
include "lib/php/functions.php";

$items = getCartItems();

$totals = getTotals($items);

$page_title = "Shopping bag";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<h1>Shopping bag <?php if (count($items) > 0) { ?><small class="supporting"><?php echo cartCount() ?> <?php echo cartCount() == 1 ? "item" : "items" ?></small><?php } ?></h1>
		<hr>

		<?php showMessage() ?>

		<?php if (count($items) == 0) { ?>

			<p>Your bag is empty. <a href="product_list.php">Continue shopping &rsaquo;</a></p>

		<?php } else { ?>

		<div class="grid gap">
			<div class="col-xs-12 col-md-8">
				<?php foreach ($items as $item) { ?>
					<?php $product = $item->product ?>
					<div class="cart-item">
						<a href="product_item.php?id=<?php echo $product->id ?>"><img src="<?php echo $product->image ?>" alt="<?php echo $product->name ?>"></a>
						<div class="flex-stretch">
							<p class="eyebrow"><?php echo $product->origin ?></p>
							<h4><a href="product_item.php?id=<?php echo $product->id ?>"><?php echo $product->name ?></a></h4>
							<p class="supporting">
								<?php if (count($item->choices) > 0) { ?>
									<?php echo choicesText($item->choices) ?>
								<?php } else { ?>
									Finish: <?php echo $product->finish ?>
								<?php } ?>
							</p>
							<div class="display-flex flex-align-center">
								<form action="product_actions.php?action=update-amount" method="post" class="stepper">
									<input type="hidden" name="key" value="<?php echo $item->key ?>">
									<button type="submit" name="amount" value="<?php echo $item->amount - 1 ?>" aria-label="Fewer" <?php echo $item->amount <= 1 ? "disabled" : "" ?>>&minus;</button>
									<span><?php echo $item->amount ?></span>
									<button type="submit" name="amount" value="<?php echo $item->amount + 1 ?>" aria-label="More" <?php echo amountLeft($product, $item->key) <= $item->amount ? "disabled" : "" ?>>+</button>
								</form>
								<form action="product_actions.php?action=delete-item" method="post">
									<input type="hidden" name="key" value="<?php echo $item->key ?>">
									<button type="submit" class="link-button link-underline">Remove</button>
								</form>
							</div>
						</div>
						<div class="price"><?php echo money($item->total) ?></div>
					</div>
				<?php } ?>
			</div>
			<div class="col-xs-12 col-md-4">
				<div class="card soft">
					<div class="display-flex flex-align-center">
						<h4 class="flex-stretch">Order summary</h4>
						<span><?php echo cartCount() ?> <?php echo cartCount() == 1 ? "item" : "items" ?></span>
					</div>
					<table class="table">
						<tbody>
							<tr><td>Subtotal</td><td><?php echo money($totals->subtotal) ?></td></tr>
							<tr><td>Shipping</td><td>Free</td></tr>
							<tr><td>Estimated tax</td><td><?php echo money($totals->tax) ?></td></tr>
							<tr><td><strong>Estimated total</strong></td><td><strong><?php echo money($totals->total) ?></strong></td></tr>
						</tbody>
					</table>
					<div class="form-control">
						<a href="product_checkout.php" class="form-button primary button-split"><span>Continue to checkout</span><span>&rsaquo;</span></a>
					</div>
					<p class="supporting center">Secure checkout &middot; 30-day returns</p>
				</div>
			</div>
		</div>

		<?php } ?>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
