<?php
include "../src/core.php";

$user = new User($pdo_link);
$user->username = "RottenLife";
$user->password = "tomato";

$result = $user->create();


var_dump($user, $result);
?>