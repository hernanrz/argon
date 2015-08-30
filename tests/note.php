<?php
include "../src/core.php";

$note = new Note($pdo_link);

$note->UID = "a2ce7541d0ff5ac7939d4b93ebe054b617a25e07";
$note->fetch_data();
$note->title = "lalalalala lalaalalalalala";
$result= $note->save_changes("1704c1b299a04897a349115f8481970e");
//$note->fetch_data();
var_dump($note, $note->get_pkey());
?>