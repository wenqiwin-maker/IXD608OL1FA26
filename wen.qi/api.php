<?php
include "lib/php/functions.php";

header("Content-Type: application/json; charset=utf-8");

function sendJson($data, $status = 200) {
	http_response_code($status);
	echo json_encode($data);
	exit;
}

$action = "products";
if (isset($_GET["action"]) && is_string($_GET["action"])) {
	$action = $_GET["action"];
}

switch ($action) {
	case "products":
		$params = cleanParams($_GET);
		$products = findProducts($params);

		$cards = array_map(function($product) {
			return [
				"id" => (int) $product->id,
				"name" => $product->name,
				"origin" => $product->origin,
				"price" => (float) $product->price,
				"image" => $product->image,
				"stock" => (int) $product->stock
			];
		}, $products);

		sendJson([
			"count" => count($cards),
			"params" => $params,
			"menus" => getFilterMenus(),
			"sorts" => getSortMenu(),
			"products" => $cards
		]);
		break;

	default:
		sendJson(["error" => "Unknown action"], 400);

}
