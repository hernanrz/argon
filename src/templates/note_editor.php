<?php
if(!isset($note) && isset($_GET["UID"])) {
	$note = new Note($pdo_link, $_GET["UID"]);
}	

if(isset($note) && isset($_GET["key"])) {
	?>
	<script>
	components.UID = "<?php print $note->UID ?>";
	components.key = "<?php print $_GET["key"] ?>";
	</script>		
	<?php
}
?>
<div class="page-block">
	<input value="<?= (isset($note) ? $note->title : "") ?>" placeholder="Nueva Nota" type="text" id="title" class="input"/>
	<div id="text-content">
		<textarea id="text-area" class="input"><?= (isset($note) ? $note->content : "") ?></textarea>			
	</div>
	<div id="bottom-actions">
		<input type="checkbox" id="private-chk" checked="<?php isset($note) ? ($note->private ? "checked" : "") : "" ?>" name="private"><label for="private-chk"> Nota privada</label>
		<button class="flat-btn" id="save-btn">Guardar</button>
	</div>
	<div <?php if(isset($note)) { ?> style="display:block" <?php } ?> id="action-links">
	<a id="delete-link" href="javascript:void(0)">Eliminar</a> 
	| <a id="share-link" href="javascript:void(0)">Compartir</a>
	</div>
	
	<div id="share-url">
		<i class="icon link"></i>
		<input type="text" id="share-url-val" class="input"/>
	</div>
</div>
