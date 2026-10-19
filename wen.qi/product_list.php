<?php
include "lib/php/data.php";
include "lib/php/functions.php";

$page_title = "Shop";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<p class="eyebrow">The collection</p>
		<h1>Objects with a place, craft, and story.</h1>
		<p>Useful, beautiful things selected from workshops and traditions around the world.</p>

		<div class="grid gap">
			<div class="col-xs-6 col-md-4"><?php productCard(0, $products[0]) ?></div>
			<div class="col-xs-6 col-md-4"><?php productCard(1, $products[1]) ?></div>
			<div class="col-xs-6 col-md-4"><?php productCard(2, $products[2]) ?></div>
			<div class="col-xs-6 col-md-4"><?php productCard(3, $products[3]) ?></div>
			<div class="col-xs-6 col-md-4"><?php productCard(4, $products[4]) ?></div>
			<div class="col-xs-6 col-md-4"><?php productCard(5, $products[5]) ?></div>
		</div>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
