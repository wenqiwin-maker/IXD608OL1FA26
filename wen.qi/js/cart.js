// Fake cart: only changes what you see, nothing is saved.

// 68 => "$68", 4.76 => "$4.76"
function money(amount) {
	if (amount == Math.floor(amount)) {
		return "$" + amount;
	}
	return "$" + amount.toFixed(2);
}

var shipping = 12;

// Work out the numbers from the quantity and write them to the page
function updateCart() {
	var item = $(".cart-item");
	var price = Number(item.data("price"));
	var qty = Number(item.find(".qty").text());
	var subtotal = price * qty;

	item.find(".line-price").text(money(subtotal));
	$("#subtotal").text(money(subtotal));
	$("#total").text(money(subtotal + shipping));

	var words = qty + (qty == 1 ? " item" : " items");
	$("#cart-count").text(words);
	$(".item-count").text(words);
}

$(".qty-plus").on("click", function() {
	var item = $(".cart-item");
	var qty = Number(item.find(".qty").text());
	if (qty < Number(item.data("stock"))) {
		item.find(".qty").text(qty + 1);
		updateCart();
	}
});

$(".qty-minus").on("click", function() {
	var item = $(".cart-item");
	var qty = Number(item.find(".qty").text());
	if (qty > 1) {
		item.find(".qty").text(qty - 1);
		updateCart();
	}
});

// Remove hides the cart and shows the empty message
$(".remove").on("click", function(event) {
	event.preventDefault();
	$("#cart-grid").addClass("hidden");
	$("#cart-count").text("0 items");
	$("#empty-message").removeClass("hidden");
});
