<?php

function productFields() {
	return [
		"name" => ["Name", 120],
		"origin" => ["Origin (city or region, country)", 120],
		"country" => ["Country", 60],
		"category" => ["Category", 40],
		"craft" => ["Craft", 60],
		"material" => ["Material", 40],
		"finish" => ["Finish", 40]
	];
}

function allProducts() {
	return pdoQuery("SELECT * FROM products ORDER BY id DESC")->fetchAll();
}

function findProductById($id) {
	$product = pdoQuery("SELECT * FROM products WHERE id = ?", [(int) $id])->fetch();
	return $product ? $product : null;
}

function blankForm() {
	$form = ["price" => "", "stock" => "0", "featured" => 0, "description" => "", "images" => "", "options" => ""];
	foreach (productFields() as $key => $field) {
		$form[$key] = "";
	}
	$form["finish"] = "Natural";
	return $form;
}

function formFromProduct($product) {
	$form = blankForm();
	foreach ($form as $key => $value) {
		$form[$key] = (string) $product->$key;
	}
	$form["images"] = implode("\n", explode(",", $product->images));
	$form["options"] = optionsToText($product);
	return $form;
}

function formFromPost($post) {
	$form = blankForm();
	foreach ($form as $key => $value) {
		$form[$key] = isset($post[$key]) && is_string($post[$key]) ? trim($post[$key]) : "";
	}
	$form["featured"] = isset($post["featured"]) ? 1 : 0;
	return $form;
}

function textLines($text) {
	return array_values(array_filter(array_map("trim", explode("\n", $text))));
}

function optionsToText($product) {
	return implode("\n", array_map(function($option) {
		return $option->name . ": " . implode(", ", $option->values);
	}, getOptions($product)));
}

function textToOptions($text) {
	$options = [];
	foreach (textLines($text) as $line) {
		$parts = explode(":", $line, 2);
		if (count($parts) < 2) {
			return null;
		}
		$name = trim($parts[0]);
		$values = array_values(array_filter(array_map("trim", explode(",", $parts[1])), "strlen"));
		if ($name == "" || count($values) == 0) {
			return null;
		}
		$options[] = ["name" => $name, "values" => $values];
	}
	return $options;
}

function checkProduct($form) {
	$errors = [];

	foreach (productFields() as $key => $field) {
		if ($form[$key] == "") {
			$errors[$key] = "Please fill in this field.";
		} else if (mb_strlen($form[$key]) > $field[1]) {
			$errors[$key] = "Use $field[1] characters or fewer.";
		}
	}

	if ($form["description"] == "") {
		$errors["description"] = "Please fill in this field.";
	}

	if (!is_numeric($form["price"]) || $form["price"] <= 0 || $form["price"] > 999999) {
		$errors["price"] = "Enter a price above 0.";
	}

	if (!ctype_digit($form["stock"]) || $form["stock"] > 100000) {
		$errors["stock"] = "Enter a whole number, 0 or more.";
	}

	if (textToOptions($form["options"]) === null) {
		$errors["options"] = "Write each option like this: Size: Small, Medium, Large";
	}

	foreach (textLines($form["images"]) as $path) {
		if (strpos($path, ",") !== false) {
			$errors["images"] = "Put each photo on its own line, without commas.";
		}
	}

	return $errors;
}

function photoTypes() {
	return [IMAGETYPE_JPEG => "jpg", IMAGETYPE_PNG => "png", IMAGETYPE_GIF => "gif", IMAGETYPE_WEBP => "webp"];
}

function uploadedPhotos($files) {
	if (!isset($files["name"]) || !is_array($files["name"])) {
		return [];
	}

	$photos = [];
	foreach ($files["name"] as $i => $name) {
		if ($files["error"][$i] != UPLOAD_ERR_NO_FILE) {
			$photos[] = (object)["name" => $name, "temp" => $files["tmp_name"][$i], "error" => $files["error"][$i]];
		}
	}
	return $photos;
}

function photoType($photo) {
	if ($photo->error != UPLOAD_ERR_OK) {
		return null;
	}
	$info = @getimagesize($photo->temp);
	if (!$info || !isset(photoTypes()[$info[2]])) {
		return null;
	}
	return photoTypes()[$info[2]];
}

function checkPhotos($photos) {
	foreach ($photos as $photo) {
		if ($photo->error == UPLOAD_ERR_INI_SIZE || $photo->error == UPLOAD_ERR_FORM_SIZE) {
			return $photo->name . " is too large. The limit is " . ini_get("upload_max_filesize") . "B per photo.";
		}
		if (!photoType($photo)) {
			return $photo->name . " could not be used. Choose a JPG, PNG, GIF or WebP image.";
		}
	}
	return "";
}

function savePhotos($photos) {
	if (count($photos) > 0 && !is_dir("img/uploads")) {
		@mkdir("img/uploads", 0775);
	}

	$paths = [];
	foreach ($photos as $photo) {
		$path = "img/uploads/" . bin2hex(random_bytes(8)) . "." . photoType($photo);
		if (!@move_uploaded_file($photo->temp, $path)) {
			return null;
		}
		$paths[] = $path;
	}
	return $paths;
}

function productValues($form) {
	$images = textLines($form["images"]);
	if (count($images) == 0) {
		$images = ["img/placeholder.png"];
	}
	$options = textToOptions($form["options"]);

	$values = [];
	foreach (productFields() as $key => $field) {
		$values[$key] = $form[$key];
	}
	$values["price"] = $form["price"];
	$values["stock"] = (int) $form["stock"];
	$values["featured"] = $form["featured"];
	$values["description"] = $form["description"];
	$values["image"] = $images[0];
	$values["images"] = implode(",", $images);
	$values["options"] = count($options) > 0 ? json_encode($options, JSON_UNESCAPED_UNICODE) : null;
	return $values;
}

function createProduct($values) {
	$columns = array_keys($values);
	$marks = implode(", ", array_fill(0, count($columns), "?"));
	pdoQuery("INSERT INTO products (" . implode(", ", $columns) . ") VALUES ($marks)", array_values($values));
	return (int) makePDOConn()->lastInsertId();
}

function updateProduct($id, $values) {
	$sets = array_map(function($column) {
		return "$column = ?";
	}, array_keys($values));
	$params = array_values($values);
	$params[] = (int) $id;
	pdoQuery("UPDATE products SET " . implode(", ", $sets) . " WHERE id = ?", $params);
}

function deleteProduct($id) {
	pdoQuery("DELETE FROM products WHERE id = ?", [(int) $id]);
}

function formInput($key, $label, $form, $errors, $attributes = 'type="text"') {
	echo '<div class="form-control">';
	echo '<label class="form-label" for="' . $key . '">' . $label . '</label>';
	echo '<input class="form-input' . (isset($errors[$key]) ? ' input-error' : '') . '" ' . $attributes . ' id="' . $key . '" name="' . $key . '" value="' . htmlspecialchars($form[$key]) . '">';
	formError($key, $errors);
	echo '</div>';
}

function formTextarea($key, $label, $form, $errors, $rows) {
	echo '<div class="form-control">';
	echo '<label class="form-label" for="' . $key . '">' . $label . '</label>';
	echo '<textarea class="form-input form-textarea' . (isset($errors[$key]) ? ' input-error' : '') . '" id="' . $key . '" name="' . $key . '" rows="' . $rows . '">' . htmlspecialchars($form[$key]) . '</textarea>';
	formError($key, $errors);
	echo '</div>';
}

function formError($key, $errors) {
	if (isset($errors[$key])) {
		echo '<div class="form-error">' . htmlspecialchars($errors[$key]) . '</div>';
	}
}
