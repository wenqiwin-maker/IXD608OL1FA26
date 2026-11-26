<?php
chdir(__DIR__ . "/..");
include "lib/php/functions.php";

$products = allProducts();

$page_title = "Products";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "admin/parts/head.php" ?>
</head>
<body>

	<?php include "admin/parts/header.php" ?>

	<div class="container">
		<div class="display-flex flex-align-center admin-title">
			<h2 class="flex-stretch">Products <small class="supporting"><?php echo count($products) ?></small></h2>
			<a href="product.php" class="form-button primary flex-none auto-width">+ Add product</a>
		</div>

		<?php showMessage() ?>

		<div class="card soft admin-table-wrap">
			<table class="table admin-table">
				<thead>
					<tr>
						<th>Photo</th>
						<th>Name</th>
						<th class="wide-only">Category</th>
						<th>Price</th>
						<th>Stock</th>
						<th class="wide-only">Featured</th>
						<th class="wide-only"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($products as $product) { ?>
						<tr>
							<td><img src="../<?php echo htmlspecialchars($product->image) ?>" alt=""></td>
							<td><a href="product.php?id=<?php echo $product->id ?>"><strong><?php echo htmlspecialchars($product->name) ?></strong></a><br><span class="supporting"><?php echo htmlspecialchars($product->origin) ?></span></td>
							<td class="wide-only"><?php echo htmlspecialchars($product->category) ?></td>
							<td><?php echo money($product->price) ?></td>
							<td><?php if ($product->stock == 0) { ?><span class="stock-out">Sold out</span><?php } else { echo $product->stock; } ?></td>
							<td class="wide-only"><?php echo $product->featured ? "&#9733;" : "&ndash;" ?></td>
							<td class="wide-only"><a href="product.php?id=<?php echo $product->id ?>" class="pill">Edit</a></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>

</body>
</html>
