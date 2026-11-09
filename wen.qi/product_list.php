<?php
include "lib/php/functions.php";

$page_title = "Shop";

// Build the WHERE part of the query from the address, one piece per filter
$where = ["1"];

// filter name in the address => column in the table
$columns = ["origin" => "country", "category" => "category", "material" => "material"];
foreach ($columns as $key => $column) {
	if (!empty($_GET[$key])) {
		$where[] = "$column = '" . cleanText($_GET[$key]) . "'";
	}
}

// price: only these three choices are allowed
$price_rules = [
	"under-50" => "price < 50",
	"50-100" => "price BETWEEN 50 AND 100",
	"over-100" => "price > 100"
];
if (isset($_GET["price"]) && isset($price_rules[$_GET["price"]])) {
	$where[] = $price_rules[$_GET["price"]];
}

if (isset($_GET["availability"]) && $_GET["availability"] == "in-stock") {
	$where[] = "stock > 0";
}

// search box in the header
$search = "";
if (!empty($_GET["search"])) {
	$search = $_GET["search"];
	$word = cleanText($search);
	$where[] = "(name LIKE '%$word%' OR origin LIKE '%$word%' OR craft LIKE '%$word%')";
}

// sort: only these choices are allowed, anything else means "featured"
$sort_rules = [
	"featured" => "featured DESC, id",
	"price-asc" => "price ASC",
	"price-desc" => "price DESC",
	"newest" => "created_at DESC"
];
$sort_names = [
	"featured" => "Featured",
	"price-asc" => "Price: Low to high",
	"price-desc" => "Price: High to low",
	"newest" => "Newest"
];
$sort = "featured";
if (isset($_GET["sort"]) && isset($sort_rules[$_GET["sort"]])) {
	$sort = $_GET["sort"];
}
$sort_label = $sort_names[$sort];

$products = fetchAll("SELECT * FROM products WHERE " . implode(" AND ", $where) . " ORDER BY " . $sort_rules[$sort]);
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

		<?php include "parts/filters.php" ?>

		<?php if ($search != "") { ?>
			<p class="supporting">Results for &ldquo;<?php echo htmlspecialchars($search) ?>&rdquo;</p>
		<?php } ?>

		<?php if (count($products) == 0) { ?>
			<p>No objects match that filter. <a href="product_list.php">See everything &rsaquo;</a></p>
		<?php } else { ?>
			<div class="grid gap">
				<?php foreach ($products as $product) { ?>
					<div class="col-xs-6 col-md-4"><?php productCard($product) ?></div>
				<?php } ?>
			</div>
		<?php } ?>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
