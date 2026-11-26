<?php
chdir(__DIR__ . "/..");
include "lib/php/functions.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$product = $id ? findProductById($id) : null;

if ($id && !$product) {
	setMessage("error", "That product no longer exists.");
	header("Location: index.php");
	exit;
}

$form = $product ? formFromProduct($product) : blankForm();
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (count($_POST) == 0) {
		$errors["upload"] = "Those photos are too large to send together. Add fewer or smaller photos.";
	} else {
		$form = formFromPost($_POST);
		$errors = checkProduct($form);

		$photos = uploadedPhotos(isset($_FILES["photos"]) ? $_FILES["photos"] : []);
		$problem = checkPhotos($photos);
		if ($problem != "") {
			$errors["upload"] = $problem;
		}

		if (count($errors) == 0) {
			$paths = savePhotos($photos);
			if ($paths === null) {
				$errors["upload"] = "The server could not save the photo.";
			} else {
				$form["images"] = implode("\n", array_merge(textLines($form["images"]), $paths));
				$values = productValues($form);

				if ($product) {
					updateProduct($id, $values);
					setMessage("success", "Saved " . htmlspecialchars($form["name"]) . ".");
				} else {
					$id = createProduct($values);
					setMessage("success", "Added " . htmlspecialchars($form["name"]) . ".");
				}

				header("Location: product.php?id=$id");
				exit;
			}
		}

		if (count($photos) > 0 && !isset($errors["upload"])) {
			$errors["upload"] = "Choose your new photos again before saving.";
		}
	}
}

$page_title = $product ? "Edit " . $product->name : "Add product";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "admin/parts/head.php" ?>
</head>
<body>

	<?php include "admin/parts/header.php" ?>

	<div class="container">
		<p><a href="index.php">&larr; Back to products</a></p>
		<div class="display-flex flex-align-center admin-title">
			<h2 class="flex-stretch"><?php echo htmlspecialchars($page_title) ?></h2>
			<?php if ($product) { ?>
				<a href="../product_item.php?id=<?php echo $id ?>" class="pill flex-none">View in shop</a>
			<?php } ?>
		</div>

		<?php showMessage() ?>

		<?php if (count($errors) > 0) { ?>
			<div class="message error">Nothing was saved. Please check the fields marked below.</div>
		<?php } ?>

		<form action="product.php<?php echo $product ? "?id=$id" : "" ?>" method="post" enctype="multipart/form-data" novalidate>
			<div class="grid gap">
				<div class="col-xs-12 col-md-7">
					<div class="card soft admin-card">
						<h4>Basics</h4>
						<?php
						formInput("name", "Name", $form, $errors);
						formTextarea("description", "Description", $form, $errors, 4);
						?>
					</div>

					<div class="card soft admin-card">
						<h4>Origin &amp; craft</h4>
						<?php
						foreach (productFields() as $key => $field) {
							if ($key != "name") {
								formInput($key, $field[0], $form, $errors);
							}
						}
						?>
					</div>
				</div>

				<div class="col-xs-12 col-md-5">
					<div class="card soft admin-card">
						<h4>Price &amp; stock</h4>
						<?php
						formInput("price", "Price (USD)", $form, $errors, 'type="number" step="0.01" min="0"');
						formInput("stock", "In stock", $form, $errors, 'type="number" step="1" min="0"');
						?>
						<div class="form-control">
							<label><input type="checkbox" name="featured" value="1" <?php echo $form["featured"] ? "checked" : "" ?>> Feature on the home page</label>
						</div>
					</div>

					<div class="card soft admin-card">
						<h4>Photos</h4>
						<?php if (count(textLines($form["images"])) > 0) { ?>
							<div class="photo-previews">
								<?php foreach (textLines($form["images"]) as $i => $path) { ?>
									<figure>
										<img src="../<?php echo htmlspecialchars($path) ?>" alt="Photo <?php echo $i + 1 ?>">
										<?php if ($i == 0) { ?><figcaption>Main</figcaption><?php } ?>
									</figure>
								<?php } ?>
							</div>
						<?php } ?>
						<div class="form-control">
							<label class="form-label" for="photos">Upload photos</label>
							<input type="file" id="photos" name="photos[]" accept="image/jpeg,image/png,image/gif,image/webp" multiple>
							<?php formError("upload", $errors) ?>
						</div>
						<?php formTextarea("images", "Photo list", $form, $errors, 4) ?>
						<p class="supporting">One photo per line. The first line is the main photo. Delete a line to remove that photo, or move lines to reorder them.</p>
					</div>

					<div class="card soft admin-card">
						<h4>Options</h4>
						<?php formTextarea("options", "Choices shoppers can pick", $form, $errors, 3) ?>
						<p class="supporting">One option per line, like <em>Size: Small, Medium, Large</em>. Leave empty if there are none.</p>
					</div>
				</div>
			</div>

			<div class="display-flex flex-align-center admin-save">
				<button type="submit" class="form-button primary flex-none auto-width"><?php echo $product ? "Save changes" : "Add product" ?></button>
				<a href="index.php" class="link-underline flex-none">Cancel</a>
				<div class="flex-stretch"></div>
				<?php if ($product) { ?>
					<a href="delete.php?id=<?php echo $id ?>" class="link-underline danger flex-none">Delete product</a>
				<?php } ?>
			</div>
		</form>
	</div>

</body>
</html>
