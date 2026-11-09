<?php
include "lib/php/functions.php";

// Find the product for ?id= (null if there isn't one)
$product = getProductFromUrl();

if ($product) {
	$page_title = $product->name;

	// the photos column holds file names separated by commas, main photo first
	$images = explode(",", $product->images);
} else {
	$page_title = "Not found";
}
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<p><a href="product_list.php">&larr; Back to collection</a></p>

		<?php if (!$product) { ?>

			<h2>Sorry, we couldn't find that object.</h2>
			<p><a href="product_list.php">Browse the collection &rsaquo;</a></p>

		<?php } else { ?>

		<div class="grid gap">
			<div class="col-xs-12 col-md-7">
				<figure class="figure product item-photo">
					<img id="gallery-big" src="<?php echo $images[0] ?>" alt="<?php echo $product->name ?>">
					<?php if (count($images) > 1) { ?>
						<button type="button" class="gallery-arrow gallery-prev" aria-label="Previous photo">&lsaquo;</button>
						<button type="button" class="gallery-arrow gallery-next" aria-label="Next photo">&rsaquo;</button>
					<?php } ?>
				</figure>
				<?php if (count($images) > 1) { ?>
					<div class="gallery-thumbs">
						<?php foreach ($images as $i => $image) { ?>
							<img class="thumb <?php echo $i == 0 ? 'active' : '' ?>" src="<?php echo $image ?>" alt="Photo <?php echo $i + 1 ?> of <?php echo $product->name ?>">
						<?php } ?>
					</div>
				<?php } ?>
			</div>
			<div class="col-xs-12 col-md-5">
				<p class="eyebrow"><?php echo $product->origin ?> &middot; <?php echo $product->craft ?></p>
				<h1><?php echo $product->name ?></h1>
				<p class="price"><?php echo money($product->price) ?></p>
				<p><?php echo $product->description ?></p>
				<hr>
				<div class="display-flex">
					<strong class="flex-stretch">Finish</strong>
					<span><?php echo $product->finish ?></span>
				</div>
				<p>
					<?php if ($product->stock > 0) { ?>
						<a href="product_cart.php?id=<?php echo $product->id ?>" class="form-button primary button-split"><span>Add to cart</span><span><?php echo money($product->price) ?></span></a>
					<?php } else { ?>
						<span class="form-button primary button-split disabled"><span>Sold out</span></span>
					<?php } ?>
				</p>

				<details class="details" open>
					<summary>Story &amp; origin</summary>
					<p>Made in <?php echo $product->origin ?> using <?php echo strtolower($product->material) ?> and the craft of <?php echo strtolower($product->craft) ?>.</p>
				</details>
				<details class="details">
					<summary>Dimensions &amp; care</summary>
					<p>Wipe clean with a soft, dry cloth. Each piece is handmade, so sizes vary slightly.</p>
				</details>
				<details class="details">
					<summary>Shipping &amp; returns</summary>
					<p>Ships in 2&ndash;4 days. Returns accepted within 30 days.</p>
				</details>
			</div>
		</div>

		<?php } ?>
	</div>

	<?php include "parts/footer.php" ?>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="js/gallery.js?v=<?php echo filemtime("js/gallery.js") ?>"></script>
</body>
</html>
