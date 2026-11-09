// Photo slideshow on the product page.
// Clicking a small photo, or the arrows, changes the big photo.

var thumbs = $(".thumb");

// Show photo number n (0 is the first one)
function showPhoto(n) {
	// loop around: after the last photo comes the first
	if (n < 0) {
		n = thumbs.length - 1;
	}
	if (n >= thumbs.length) {
		n = 0;
	}

	thumbs.removeClass("active");
	thumbs.eq(n).addClass("active");
	$("#gallery-big").attr("src", thumbs.eq(n).attr("src"));
}

// Find which photo is showing now
function currentPhoto() {
	return thumbs.index($(".thumb.active"));
}

thumbs.on("click", function() {
	showPhoto(thumbs.index(this));
});

$(".gallery-next").on("click", function() {
	showPhoto(currentPhoto() + 1);
});

$(".gallery-prev").on("click", function() {
	showPhoto(currentPhoto() - 1);
});
