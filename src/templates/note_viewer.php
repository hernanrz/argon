<?php 
	$content = htmlentities($note->content);
	$content = nl2br($content);		
?>
<div class="page-block view">
	<h1 class="view" id="title"><?= $note->title ?></h1>
	<div id="text-content">
		<p><?= $content; ?></p>
	</div>
</div>