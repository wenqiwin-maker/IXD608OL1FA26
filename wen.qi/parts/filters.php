<?php

function filterMenu($menu, $params) {
	$key = $menu["key"];
	$current = isset($params[$key]) ? $params[$key] : "";

	$label = $menu["label"];
	foreach ($menu["choices"] as $choice) {
		if ($choice["value"] == $current) {
			$label .= ": " . $choice["label"];
		}
	}

	echo '<details class="menu">';
	echo '<summary class="' . ($current != "" ? "pill active" : "pill") . '">' . htmlspecialchars($label) . ' <span class="arrow">&#9662;</span></summary>';
	echo '<div class="menu-list">';
	echo '<a href="' . shopLink($key, "") . '">Any</a>';
	foreach ($menu["choices"] as $choice) {
		echo '<a href="' . shopLink($key, $choice["value"]) . '">' . htmlspecialchars($choice["label"]) . '</a>';
	}
	echo '</div>';
	echo '</details>';
}

$any_filter = count($params) > 2 || $params["search"] != "";
?>
<div class="filter-bar display-flex flex-align-center" id="filter-bar">
	<a href="product_list.php" class="<?php echo $any_filter ? 'pill' : 'pill active' ?>">All</a>
	<?php foreach (getFilterMenus() as $menu) { filterMenu($menu, $params); } ?>
	<div class="flex-stretch"></div>
	<details class="menu menu-right">
		<summary class="pill">Sort <strong><?php echo $sort_label ?></strong> <span class="arrow">&#9662;</span></summary>
		<div class="menu-list">
			<?php foreach (getSortMenu() as $choice) { ?>
				<a href="<?php echo shopLink("sort", $choice["value"]) ?>"><?php echo $choice["label"] ?></a>
			<?php } ?>
		</div>
	</details>
</div>
