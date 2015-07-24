<?php 
	$content = htmlentities($note->content);
	$content = nl2br($content);		
?>
<div class="page-block view">
	<h1 class="view" id="title"><?= htmlentities($note->title) ?></h1>
	<div id="text-content">
		<p><?= $content; ?></p>
	</div>
</div>