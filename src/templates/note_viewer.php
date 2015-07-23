<?php
	$note = new Note($pdo_link, $_GET["UID"]);
?>
<div class="page-block">
	<h1 class="view" id="title"><?= $note->title ?></h1>
	<div id="text-content">
		<p><?= $note->content ?></p>
	</div>
</div>