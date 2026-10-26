<?php
include "lib/php/functions.php";

$users = file_get_json("data/users.json");
$id = $_GET["id"];
$user = $users[$id];

$deleted = false;
if (count($_POST) > 0) {
	$remaining = [];
	for ($i = 0; $i < count($users); $i++) {
		if ($i != $id) {
			$remaining[] = $users[$i];
		}
	}

	file_put_contents("data/users.json", json_encode($remaining, JSON_PRETTY_PRINT));
	$deleted = true;
}

$page_title = "Delete User";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<?php if ($deleted) { ?>
			<h1>User deleted</h1>
			<div class="message success">The user was deleted.</div>
			<p><a href="user_list.php">&larr; Back to users</a></p>
		<?php } else { ?>
			<p><a href="user_edit.php?id=<?php echo $id ?>">&larr; Back to user</a></p>
			<h1>Delete <?php echo $user->name ?>?</h1>
			<p>This removes the user from the list.</p>
			<form action="user_delete.php?id=<?php echo $id ?>" method="post">
				<input type="hidden" name="confirm" value="yes">
				<div class="form-control"><button type="submit" class="form-button primary">Delete user</button></div>
			</form>
		<?php } ?>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
