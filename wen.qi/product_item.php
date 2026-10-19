<?php
include "lib/php/data.php";
include "lib/php/functions.php";
$id = $_GET["id"];
$product = $products[$id];
$page_title = $product->name;
$page_title = "Product";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<p><a href="product_list.php">&larr; Back to collection</a></p>
		<div class="grid gap">
			<div class="col-xs-12 col-md-7">
				<figure class="figure product">
					<img src="img/placeholder.png" alt="<?php echo $product->name ?>">
				</figure>
			</div>
			<div class="col-xs-12 col-md-5">
				<p class="eyebrow"><?php echo $product->origin ?> · <?php echo $product->craft ?></p>
				<h1><?php echo $product->name ?></h1>
				<p class="price"><?php echo money($product->price) ?></p>
				<p><?php echo $product->description ?></p>
				<hr>
				<a href="product_cart.php" class="form-button primary button-split"><span>Add to cart</span><span><?php echo money($product->price) ?></span></a>
			</div>
		</div>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
