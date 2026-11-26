<?php

// DATABASE

include_once "lib/php/auth.php";
include_once "lib/php/cart.php";
include_once "lib/php/products.php";
include_once "lib/php/admin.php";

// Connect to the database (stops the page if it can't)
function makeConn() {
	global $db_host, $db_user, $db_pass, $db_name;
	static $conn = null;
	if ($conn === null) {
		$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
		if (!$conn) {
			die("Could not connect to the database");
		}
	}
	return $conn;
}

// Run a SELECT and return all the rows as an array of objects
function fetchAll($sql) {
	$conn = makeConn();
	$result = mysqli_query($conn, $sql);
	$rows = [];
	if ($result) {
		while ($row = mysqli_fetch_object($result)) {
			$rows[] = $row;
		}
	}
	return $rows;
}

// Run a SELECT and return only the first row (or null if there are none)
function fetchOne($sql) {
	$rows = fetchAll($sql);
	if (count($rows) > 0) {
		return $rows[0];
	}
	return null;
}

// Make text safe to put inside a SQL string
function cleanText($text) {
	return mysqli_real_escape_string(makeConn(), $text);
}

function makePDOConn() {
	global $db_host, $db_user, $db_pass, $db_name;
	static $pdo = null;
	if ($pdo === null) {
		try {
			$pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
			]);
		} catch (PDOException $error) {
			die("Could not connect to the database");
		}
	}
	return $pdo;
}

function pdoQuery($sql, $values = []) {
	$statement = makePDOConn()->prepare($sql);
	$statement->execute($values);
	return $statement;
}

// PRODUCTS

// 68 => "$68", 4.76 => "$4.76"
function money($amount) {
	$amount = (float) $amount;
	if ($amount == floor($amount)) {
		return "$" . number_format($amount, 0);
	}
	return "$" . number_format($amount, 2);
}

// Print one product card linking to its item page
function productCard($product) {
	$id = $product->id;
	$name = htmlspecialchars($product->name);
	echo '<figure class="figure product">';
	echo '<div class="image-wrap">';
	echo '<a href="product_item.php?id=' . $id . '"><img src="' . htmlspecialchars($product->image) . '" alt="' . $name . '"></a>';
	echo '<div class="favorite heart-overlay"><input type="checkbox" id="favorite-' . $id . '"><label for="favorite-' . $id . '">&hearts;</label></div>';
	echo '</div>';
	echo '<figcaption>';
	echo '<div class="display-flex"><div class="content-heading flex-stretch">' . $name . '</div><div class="price flex-none">' . money($product->price) . '</div></div>';
	echo '<div class="supporting">' . htmlspecialchars($product->origin) . '</div>';
	echo '</figcaption>';
	echo '</figure>';
}

// Get the product for ?id= in the address. Use $default when there is no valid id.
// Returns null if no product matches.
function getProductFromUrl($default = 0) {
	$id = 0;
	if (isset($_GET["id"])) {
		$id = (int) $_GET["id"];
	}
	if ($id == 0) {
		$id = $default;
	}
	return fetchOne("SELECT * FROM products WHERE id = $id");
}

function getRecommendations($products, $limit = 3) {
	if (count($products) == 0) {
		return [];
	}

	$ids = [];
	$matches = [];
	$values = [];
	foreach ($products as $product) {
		$ids[] = (int) $product->id;
		$matches[] = "(category = ?) * 2 + (material = ?)";
		$values[] = $product->category;
		$values[] = $product->material;
	}

	$marks = implode(", ", array_fill(0, count($ids), "?"));
	$score = "GREATEST(0, " . implode(", ", $matches) . ")";
	$sql = "SELECT * FROM products WHERE stock > 0 AND id NOT IN ($marks) ORDER BY $score DESC, featured DESC, id LIMIT " . (int) $limit;
	return pdoQuery($sql, array_merge($ids, $values))->fetchAll();
}

function showRecommendations($products, $title = "You may also like") {
	$recommended = getRecommendations($products);
	if (count($recommended) > 0) {
		include "parts/recommendations.php";
	}
}

function file_get_json($filename) {
	$json = file_get_contents($filename);
	return json_decode($json);
}

function showUserEditor($action, $user) {
	$name = $user->name;
	$email = $user->email;
	$interests = implode(", ", $user->interests);

	echo <<<HTML
<form action="$action" method="post">
	<div class="form-control">
		<label class="form-label" for="name">Name</label>
		<input class="form-input" type="text" id="name" name="name" value="$name">
	</div>
	<div class="form-control">
		<label class="form-label" for="type">Type</label>
		<input class="form-input" type="text" id="type" name="type" value="$user->type">
	</div>
	<div class="form-control">
		<label class="form-label" for="email">Email</label>
		<input class="form-input" type="text" id="email" name="email" value="$email">
	</div>
	<div class="form-control">
		<label class="form-label" for="interests">Interests (separate with a comma and a space)</label>
		<input class="form-input" type="text" id="interests" name="interests" value="$interests">
	</div>
	<div class="form-control"><button type="submit" class="form-button primary">Save changes</button></div>
</form>
HTML;
}

// Make a shop link that keeps the current filters but changes one thing.
// shopLink("material", "Wood") => product_list.php?material=Wood
// shopLink("material", "")     => removes the material filter
function shopLink($key, $value) {
	$params = $_GET;
	if ($value == "") {
		unset($params[$key]);
	} else {
		$params[$key] = $value;
	}
	return "product_list.php?" . http_build_query($params);
}
