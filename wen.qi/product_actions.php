<?php
include "lib/php/functions.php";

if ($_SERVER["REQUEST_METHOD"] != "POST") {
	header("Location: product_list.php");
	exit;
}

$action = "";
if (isset($_GET["action"])) {
	$action = $_GET["action"];
}

$next = "product_cart.php";

switch ($action) {
	case "add-to-cart":
		$id = isset($_POST["id"]) ? (int) $_POST["id"] : 0;
		$product = fetchOne("SELECT * FROM products WHERE id = $id");

		if (!$product) {
			setMessage("error", "Sorry, we couldn't find that object.");
			$next = "product_list.php";
			break;
		}

		$choices = cleanChoices($product, isset($_POST["options"]) ? $_POST["options"] : []);
		$amount = isset($_POST["amount"]) ? max(1, (int) $_POST["amount"]) : 1;
		$added = addToCart($product, $choices, $amount);

		if ($added == 0) {
			setMessage("error", "Sorry, there are no more of those available.");
			$next = "product_item.php?id=$id";
		} else if ($added < $amount) {
			setMessage("warning", "Only $added of those were available, so $added " . ($added == 1 ? "was" : "were") . " added to your bag.");
		} else {
			setMessage("success", htmlspecialchars($product->name) . " was added to your bag.");
		}
		break;

	case "update-amount":
		if (isset($_POST["key"]) && is_string($_POST["key"]) && isset($_POST["amount"])) {
			setAmount($_POST["key"], $_POST["amount"]);
		}
		break;

	case "delete-item":
		if (isset($_POST["key"]) && is_string($_POST["key"])) {
			removeFromCart($_POST["key"]);
			setMessage("success", "Removed from your bag.");
		}
		break;

}

header("Location: $next");
exit;
