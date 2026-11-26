<?php
chdir(__DIR__ . "/..");
include "lib/php/functions.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;
$product = findProductById($id);

if (!$product) {
	setMessage("error", "That product no longer exists.");
	header("Location: index.php");
	exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	deleteProduct($id);
	setMessage("success", "Deleted " . htmlspecialchars($product->name) . ".");
	header("Location: index.php");
	exit;
}

$page_title = "Delete " . $product->name;
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "admin/parts/head.php" ?>
</head>
<body>

	<?php include "admin/parts/header.php" ?>

	<div class="container">
		<p><a href="product.php?id=<?php echo $id ?>">&larr; Back to product</a></p>
		<div class="card soft admin-card admin-confirm">
			<h3>Delete <?php echo htmlspecialchars($product->name) ?>?</h3>
			<img src="../<?php echo htmlspecialchars($product->image) ?>" alt="">
			<p>This removes the product from the shop. You can't undo it.</p>
			<form action="delete.php?id=<?php echo $id ?>" method="post">
				<div class="display-flex flex-align-center admin-save">
					<button type="submit" class="form-button primary danger-button flex-none auto-width">Delete product</button>
					<a href="product.php?id=<?php echo $id ?>" class="link-underline flex-none">Keep it</a>
				</div>
			</form>
		</div>
	</div>

</body>
</html>
