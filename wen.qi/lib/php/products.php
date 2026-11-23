<?php

function filterColumns() {
	return ["origin" => "country", "category" => "category", "material" => "material"];
}

function priceChoices() {
	return [
		"under-50" => ["Under $50", "price < 50"],
		"50-100" => ["$50 – $100", "price BETWEEN 50 AND 100"],
		"over-100" => ["Over $100", "price > 100"]
	];
}

function sortChoices() {
	return [
		"featured" => ["Featured", "featured DESC, id"],
		"price-asc" => ["Price: Low to high", "price ASC, id"],
		"price-desc" => ["Price: High to low", "price DESC, id"],
		"newest" => ["Newest", "created_at DESC, id DESC"]
	];
}

function searchColumns() {
	return ["name", "origin", "country", "craft", "material", "category", "description"];
}

function cleanParams($params) {
	$clean = ["search" => "", "sort" => "featured"];

	if (isset($params["search"]) && is_string($params["search"])) {
		$clean["search"] = mb_substr(trim($params["search"]), 0, 60);
	}

	foreach (filterColumns() as $key => $column) {
		if (isset($params[$key]) && is_string($params[$key]) && $params[$key] != "") {
			$clean[$key] = $params[$key];
		}
	}

	if (isset($params["price"]) && is_string($params["price"]) && isset(priceChoices()[$params["price"]])) {
		$clean["price"] = $params["price"];
	}

	if (isset($params["availability"]) && $params["availability"] === "in-stock") {
		$clean["availability"] = "in-stock";
	}

	if (isset($params["sort"]) && is_string($params["sort"]) && isset(sortChoices()[$params["sort"]])) {
		$clean["sort"] = $params["sort"];
	}

	return $clean;
}

function searchWords($search) {
	$words = preg_split("/\s+/", trim($search), -1, PREG_SPLIT_NO_EMPTY);
	return array_slice($words, 0, 6);
}

function findProducts($params) {
	$clean = cleanParams($params);
	$where = ["1"];

	foreach (filterColumns() as $key => $column) {
		if (isset($clean[$key])) {
			$where[] = "$column = '" . cleanText($clean[$key]) . "'";
		}
	}

	if (isset($clean["price"])) {
		$where[] = priceChoices()[$clean["price"]][1];
	}

	if (isset($clean["availability"])) {
		$where[] = "stock > 0";
	}

	foreach (searchWords($clean["search"]) as $word) {
		$word = cleanText(addcslashes($word, "%_\\"));
		$any_column = [];
		foreach (searchColumns() as $column) {
			$any_column[] = "$column LIKE '%$word%'";
		}
		$where[] = "(" . implode(" OR ", $any_column) . ")";
	}

	$order = sortChoices()[$clean["sort"]][1];
	return fetchAll("SELECT * FROM products WHERE " . implode(" AND ", $where) . " ORDER BY $order");
}

function choicesFrom($column) {
	$rows = fetchAll("SELECT DISTINCT $column FROM products ORDER BY $column");
	$choices = [];
	foreach ($rows as $row) {
		$choices[] = ["value" => $row->$column, "label" => $row->$column];
	}
	return $choices;
}

function getFilterMenus() {
	$prices = [];
	foreach (priceChoices() as $value => $choice) {
		$prices[] = ["value" => $value, "label" => $choice[0]];
	}

	return [
		["key" => "origin", "label" => "Origin", "choices" => choicesFrom("country")],
		["key" => "category", "label" => "Category", "choices" => choicesFrom("category")],
		["key" => "price", "label" => "Price", "choices" => $prices],
		["key" => "material", "label" => "Material", "choices" => choicesFrom("material")],
		["key" => "availability", "label" => "Availability", "choices" => [["value" => "in-stock", "label" => "In stock"]]]
	];
}

function getSortMenu() {
	$choices = [];
	foreach (sortChoices() as $value => $choice) {
		$choices[] = ["value" => $value, "label" => $choice[0]];
	}
	return $choices;
}
