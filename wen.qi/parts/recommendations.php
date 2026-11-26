<section class="recommendations">
	<hr>
	<div class="display-flex flex-align-center">
		<h2 class="flex-stretch"><?php echo $title ?></h2>
		<a href="product_list.php" class="link-underline nowrap">View all objects &rsaquo;</a>
	</div>
	<div class="grid gap">
		<?php foreach ($recommended as $suggestion) { ?>
			<div class="col-xs-6 col-md-4"><?php productCard($suggestion) ?></div>
		<?php } ?>
	</div>
</section>
