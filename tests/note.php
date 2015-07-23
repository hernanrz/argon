<?php
include "../src/core.php";

$note = new Note($pdo_link);

$key = "2dc454bf6bf9e7876b0cd35d366d1cd9";
$note->UID = "c6eb5fe44158704397ef5b78b6a7618526ebd4efa";
$data = $note->fetch_data();


var_dump($note, $key, $data);
?>