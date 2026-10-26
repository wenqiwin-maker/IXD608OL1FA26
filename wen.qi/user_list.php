<?php
include "lib/php/functions.php";

$users = file_get_json("data/users.json");

$page_title = "Users";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<p><a href="account.php">&larr; Back to Me</a></p>
		<h1>Users</h1>
		<p><a href="user_add.php">Add user</a></p>

		<?php
		for ($i = 0; $i < count($users); $i++) {
			echo '<div class="card soft">';
			echo '<h4><a href="user_edit.php?id=' . $i . '">' . $users[$i]->name . '</a></h4>';
			echo '<p>' . $users[$i]->type . ' &middot; ' . $users[$i]->email . '</p>';
			echo '</div>';
		}
		?>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
