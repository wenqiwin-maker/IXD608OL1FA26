<?php
include "lib/php/functions.php";

$users = file_get_json("data/users.json");

$added = false;
if (count($_POST) > 0) {
	$users[] = (object)[
		"name" => $_POST["name"],
		"type" => $_POST["type"],
		"email" => $_POST["email"],
		"interests" => explode(", ", $_POST["interests"])
	];

	file_put_contents("data/users.json", json_encode($users, JSON_PRETTY_PRINT));
	$added = true;
}

$user = (object)[
	"name" => "",
	"type" => "",
	"email" => "",
	"interests" => []
];

$page_title = "Add User";
?><!DOCTYPE html>
<html lang="en">
<head>
	<?php include "parts/head.php" ?>
</head>
<body>

	<?php include "parts/header.php" ?>

	<div class="container">
		<p><a href="user_list.php">&larr; Back to users</a></p>
		<h1>Add user</h1>

		<?php if ($added) { ?>
			<div class="message success">User added.</div>
		<?php } ?>

		<?php showUserEditor("user_add.php", $user) ?>
	</div>

	<?php include "parts/footer.php" ?>

</body>
</html>
