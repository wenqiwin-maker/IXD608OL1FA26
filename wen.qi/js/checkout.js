// Update the shipping and total lines when a shipping method is picked.
// Nothing is saved, this only changes what you see.

function money(amount) {
	if (amount == Math.floor(amount)) {
		return "$" + amount;
	}
	return "$" + amount.toFixed(2);
}

$("input[name=shipping]").on("change", function() {
	var summary = $("#summary");
	var cost = Number($(this).data("cost"));
	var total = Number(summary.data("price")) + Number(summary.data("tax")) + cost;

	$("#shipping").text(cost == 0 ? "Complimentary" : money(cost));
	$("#total").text(money(total));
});
