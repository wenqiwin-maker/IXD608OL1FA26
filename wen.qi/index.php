<?php
include "lib/php/data.php";
include "lib/php/functions.php";

$page_title = "Home";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="view-window" style="background-image: url('img/hero.jpg');">
		<div class="hero-overlay">
			<p class="eyebrow">A world of uncommon objects</p>
			<h2>Made far away.<br>Found for here.</h2>
			<p>Discover meaningful objects shaped by the places, hands, and traditions they come from.</p>
			<a href="product_list.php" class="btn">Explore the collection &rsaquo;</a>
		</div>
	</div>

	<div class="container">
		<p class="eyebrow">Newly found</p>
		<h2>Objects worth discovering</h2>
		<div class="grid gap">
			<div class="col-xs-6 col-md-3"><?php productCard(0, $products[0]) ?></div>
			<div class="col-xs-6 col-md-3"><?php productCard(1, $products[1]) ?></div>
			<div class="col-xs-6 col-md-3"><?php productCard(2, $products[2]) ?></div>
			<div class="col-xs-6 col-md-3"><?php productCard(3, $products[3]) ?></div>
		</div>
		<p><a href="product_list.php">Shop all objects &rsaquo;</a></p>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
