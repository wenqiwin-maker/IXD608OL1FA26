<header class="site-header">
	<div class="display-flex flex-align-center site-header-inner">
		<a href="index.php" class="logo flex-none">FAR<span>&amp;</span>FOUND</a>
		<nav class="nav flex-none">
			<ul class="display-flex">
				<li><a href="product_list.php">Shop</a></li>
				<li><a href="about.php">About</a></li>
			</ul>
		</nav>
		<form class="hotdog flex-stretch" action="product_list.php" method="get">
			<span class="hotdog-icon"><svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg></span>
			<input type="search" name="search" placeholder="Search objects, places, and makers" aria-label="Search objects, places, and makers" autocomplete="off" enterkeyhint="search" value="<?php echo htmlspecialchars(cleanParams($_GET)["search"]) ?>">
		</form>
		<nav class="nav flex-none">
			<ul class="display-flex">
				<li><a href="product_cart.php">Cart <?php if (cartCount() > 0) { ?><span class="badge"><?php echo cartCount() ?></span><?php } ?></a></li>
				<li><a href="account.php">Me</a></li>
			</ul>
		</nav>
	</div>
</header>
