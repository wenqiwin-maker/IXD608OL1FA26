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
