<?php
include "lib/php/functions.php";

$page_title = "Shop";

$params = cleanParams($_GET);
$products = findProducts($params);
$search = $params["search"];
$sort_label = sortChoices()[$params["sort"]][0];
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

		<?php showMessage() ?>

		<div id="shop">
			<?php include "parts/filters.php" ?>

			<div class="chips" id="active-filters"></div>

			<p class="supporting result-summary" id="result-summary" aria-live="polite">
				<?php echo count($products) ?> <?php echo count($products) == 1 ? "object" : "objects" ?><?php if ($search != "") { ?> for &ldquo;<?php echo htmlspecialchars($search) ?>&rdquo;<?php } ?>
			</p>

			<div id="product-grid">
				<?php if (count($products) == 0) { ?>
					<p>No objects match that search. <a href="product_list.php">See everything &rsaquo;</a></p>
				<?php } else { ?>
					<div class="grid gap">
						<?php foreach ($products as $product) { ?>
							<div class="col-xs-6 col-md-4"><?php productCard($product) ?></div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

	<?php include "parts/footer.php" ?>

	<script src="js/products.js?v=<?php echo filemtime("js/products.js") ?>"></script>
</body>
</html>
