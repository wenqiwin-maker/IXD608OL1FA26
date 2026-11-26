<?php

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

if (!function_exists("array_find")) {
	function array_find($array, $callback) {
		foreach ($array as $key => $value) {
			if ($callback($value, $key)) {
				return $value;
			}
		}
		return null;
	}
}

const TAX_RATE = 0.07;

function shippingMethods() {
	return [
		"standard" => (object)["name" => "Standard", "cost" => 0, "days" => 5],
		"express" => (object)["name" => "Express", "cost" => 20, "days" => 4],
		"priority" => (object)["name" => "Priority", "cost" => 30, "days" => 3]
	];
}

function setMessage($type, $text) {
	$_SESSION["message"] = ["type" => $type, "text" => $text];
}

function takeMessage() {
	if (!isset($_SESSION["message"])) {
		return null;
	}
	$message = $_SESSION["message"];
	unset($_SESSION["message"]);
	return $message;
}

function showMessage() {
	$message = takeMessage();
	if ($message) {
		echo '<div class="message ' . $message["type"] . '">' . $message["text"] . '</div>';
	}
}

function getOptions($product) {
	if (empty($product->options)) {
		return [];
	}
	$options = json_decode($product->options);
	if (!is_array($options)) {
		return [];
	}
	return $options;
}

function cleanChoices($product, $posted) {
	if (!is_array($posted)) {
		$posted = [];
	}

	$choices = [];
	foreach (getOptions($product) as $option) {
		$picked = $option->values[0];
		if (isset($posted[$option->name]) && in_array($posted[$option->name], $option->values, true)) {
			$picked = $posted[$option->name];
		}
		$choices[$option->name] = $picked;
	}
	return $choices;
}

function choicesText($choices) {
	$parts = [];
	foreach ($choices as $name => $value) {
		$parts[] = htmlspecialchars($name . ": " . $value);
	}
	return implode(" &middot; ", $parts);
}

function getCart() {
	if (!isset($_SESSION["cart"])) {
		$_SESSION["cart"] = [];
	}
	return $_SESSION["cart"];
}

function clearCart() {
	$_SESSION["cart"] = [];
}

function cartLineKey($product_id, $choices) {
	return $product_id . "-" . substr(md5(json_encode($choices)), 0, 8);
}

function cartCount() {
	return array_reduce(getCart(), function($total, $line) {
		return $total + $line["amount"];
	}, 0);
}

function amountLeft($product, $skip_key = "") {
	$in_cart = array_reduce(getCart(), function($total, $line) use ($product, $skip_key) {
		if ($line["id"] == $product->id && $line["key"] != $skip_key) {
			return $total + $line["amount"];
		}
		return $total;
	}, 0);
	return max(0, $product->stock - $in_cart);
}

function addToCart($product, $choices, $amount) {
	$key = cartLineKey($product->id, $choices);
	$amount = max(1, (int) $amount);
	$cart = getCart();

	$line = array_find($cart, function($line) use ($key) {
		return $line["key"] == $key;
	});
	$already = $line ? $line["amount"] : 0;

	$new_amount = min($already + $amount, amountLeft($product, $key));
	$added = $new_amount - $already;
	if ($added <= 0) {
		return 0;
	}

	if ($line) {
		$cart = array_map(function($line) use ($key, $new_amount) {
			if ($line["key"] == $key) {
				$line["amount"] = $new_amount;
			}
			return $line;
		}, $cart);
	} else {
		$cart[] = ["key" => $key, "id" => (int) $product->id, "options" => $choices, "amount" => $new_amount];
	}

	$_SESSION["cart"] = $cart;
	return $added;
}

function setAmount($key, $amount) {
	$line = array_find(getCart(), function($line) use ($key) {
		return $line["key"] == $key;
	});
	if (!$line) {
		return;
	}

	$product = fetchOne("SELECT * FROM products WHERE id = " . (int) $line["id"]);
	$most = $product ? amountLeft($product, $key) : 1;
	$amount = max(1, min((int) $amount, $most));

	$_SESSION["cart"] = array_map(function($line) use ($key, $amount) {
		if ($line["key"] == $key) {
			$line["amount"] = $amount;
		}
		return $line;
	}, getCart());
}

function removeFromCart($key) {
	$_SESSION["cart"] = array_values(array_filter(getCart(), function($line) use ($key) {
		return $line["key"] != $key;
	}));
}

function getCartItems() {
	$cart = getCart();
	if (count($cart) == 0) {
		return [];
	}

	$ids = array_unique(array_map(function($line) {
		return (int) $line["id"];
	}, $cart));
	$products = fetchAll("SELECT * FROM products WHERE id IN (" . implode(",", $ids) . ")");

	$items = array_map(function($line) use ($products) {
		$product = array_find($products, function($product) use ($line) {
			return $product->id == $line["id"];
		});
		if (!$product) {
			return null;
		}
		return (object)[
			"key" => $line["key"],
			"product" => $product,
			"choices" => $line["options"],
			"amount" => $line["amount"],
			"total" => $product->price * $line["amount"]
		];
	}, $cart);

	$items = array_values(array_filter($items));

	$found = array_map(function($item) {
		return $item->key;
	}, $items);
	$_SESSION["cart"] = array_values(array_filter($cart, function($line) use ($found) {
		return in_array($line["key"], $found);
	}));

	return $items;
}

function getTotals($items, $shipping = 0) {
	$subtotal = array_reduce($items, function($sum, $item) {
		return $sum + $item->total;
	}, 0);
	$tax = round($subtotal * TAX_RATE, 2);

	return (object)[
		"subtotal" => $subtotal,
		"shipping" => $shipping,
		"tax" => $tax,
		"total" => round($subtotal + $shipping + $tax, 2)
	];
}
