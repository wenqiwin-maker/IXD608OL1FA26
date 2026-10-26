<?php
include "lib/php/functions.php";

$users = file_get_json("data/users.json");
$id = $_GET["id"];
$user = $users[$id];

$saved = false;
if (count($_POST) > 0) {
	$user->name = $_POST["name"];
	$user->type = $_POST["type"];
	$user->email = $_POST["email"];
	$user->interests = explode(", ", $_POST["interests"]);

	$users[$id] = $user;
	file_put_contents("data/users.json", json_encode($users, JSON_PRETTY_PRINT));
	$saved = true;
}

$page_title = "Edit User";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<p><a href="user_list.php">&larr; Back to users</a></p>
		<h1>Edit <?php echo $user->name ?></h1>

		<?php if ($saved) { ?>
			<div class="message success">Saved.</div>
		<?php } ?>

		<?php showUserEditor("user_edit.php?id=$id", $user) ?>

		<p><a href="user_delete.php?id=<?php echo $id ?>">Delete this user</a></p>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
