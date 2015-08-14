<?php
include "../src/core.php";

$user = new User($pdo_link);
$user->username = "RottenLife";
$user->password = "tomato";

$result = $user->auth();
$sess = new Session($pdo_link);
$sess->user = $user;
$sess->grant_token();
var_dump($user, $result, $sess);
?>