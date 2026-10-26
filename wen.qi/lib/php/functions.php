<?php

// 68 => "$68"
function money($amount) {
	return "$" . $amount;
}

// Print one product card linking to its item page
function productCard($id, $product) {
	echo '<figure class="figure product">';
	echo '<div class="image-wrap">';
	echo '<a href="product_item.php?id=' . $id . '"><img src="img/placeholder.png" alt="' . $product->name . '"></a>';
	echo '<div class="favorite heart-overlay"><input type="checkbox" id="favorite-' . $id . '"><label for="favorite-' . $id . '">&hearts;</label></div>';
	echo '</div>';
	echo '<figcaption>';
	echo '<div class="display-flex"><div class="content-heading flex-stretch">' . $product->name . '</div><div class="price flex-none">' . money($product->price) . '</div></div>';
	echo '<div class="supporting">' . $product->origin . '</div>';
	echo '</figcaption>';
	echo '</figure>';
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
