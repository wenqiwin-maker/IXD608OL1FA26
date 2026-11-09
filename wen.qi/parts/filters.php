<?php
// Needs: $active (the current filter values from the address)

// Print one dropdown pill. $choices is a list of [value, label] pairs.
function filterMenu($label, $key, $choices) {
	$current = "";
	if (isset($_GET[$key])) {
		$current = $_GET[$key];
	}
	$open_class = $current != "" ? "pill active" : "pill";

	echo '<details class="menu">';
	echo '<summary class="' . $open_class . '">' . $label . ' <span class="arrow">&#9662;</span></summary>';
	echo '<div class="menu-list">';
	echo '<a href="' . shopLink($key, "") . '">Any</a>';
	foreach ($choices as $choice) {
		echo '<a href="' . shopLink($key, $choice[0]) . '">' . $choice[1] . '</a>';
	}
	echo '</div>';
	echo '</details>';
}

// Turn rows from the database into [value, label] pairs
function choicesFrom($column) {
	$rows = fetchAll("SELECT DISTINCT $column FROM products ORDER BY $column");
	$choices = [];
	foreach ($rows as $row) {
		$choices[] = [$row->$column, $row->$column];
	}
	return $choices;
}

$any_filter = false;
foreach (["origin", "category", "price", "material", "availability", "search"] as $key) {
	if (!empty($_GET[$key])) {
		$any_filter = true;
	}
}
?>
<div class="filter-bar display-flex flex-align-center">
	<a href="product_list.php" class="<?php echo $any_filter ? 'pill' : 'pill active' ?>">All</a>
	<?php
	filterMenu("Origin", "origin", choicesFrom("country"));
	filterMenu("Category", "category", choicesFrom("category"));
	filterMenu("Price", "price", [["under-50", "Under $50"], ["50-100", "$50 &ndash; $100"], ["over-100", "Over $100"]]);
	filterMenu("Material", "material", choicesFrom("material"));
	filterMenu("Availability", "availability", [["in-stock", "In stock"]]);
	?>
	<div class="flex-stretch"></div>
	<details class="menu menu-right">
		<summary class="pill">Sort <strong><?php echo $sort_label ?></strong> <span class="arrow">&#9662;</span></summary>
		<div class="menu-list">
			<a href="<?php echo shopLink("sort", "") ?>">Featured</a>
			<a href="<?php echo shopLink("sort", "price-asc") ?>">Price: Low to high</a>
			<a href="<?php echo shopLink("sort", "price-desc") ?>">Price: High to low</a>
			<a href="<?php echo shopLink("sort", "newest") ?>">Newest</a>
		</div>
	</details>
</div>
