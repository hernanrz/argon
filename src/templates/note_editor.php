<?php
if(!isset($note) && isset($_GET["UID"])) {
	$note = new Note($pdo_link, $_GET["UID"]);
}

if(isset($note) && isset($_GET["key"])) {
	?>
	<script>
	Ar.components.UID = "<?php print $note->UID ?>";
	Ar.components.key = "<?php print $_GET["key"] ?>";
	</script>
	<?php
}

include "sidebar.php";
?>
<div class="icon menu" id="sidebar-toggle">
</div>
<div class="page-block">
	<input value="<?= (isset($note) ? $note->title : "") ?>" placeholder="<?=$STR["new_note"]?>" type="text" id="title" class="input"/>
	<div id="text-content">
		<textarea id="text-area" class="input"><?= (isset($note) ? $note->content : "") ?></textarea>
	</div>
	<div id="bottom-actions">
		<input type="checkbox" id="private-chk" checked="<?php isset($note) ? ($note->private ? "checked" : "") : "" ?>" name="private"><label for="private-chk"> <?=$STR["private_note"]?></label>
		<button class="btn" id="save-btn"><?=$STR["save"]?></button>
	</div>
	<div <?php if(isset($note)) { ?> style="display:block" <?php } ?> id="action-links">
	<a id="delete-link" href="javascript:void(0)"><?=$STR["delete"]?></a>
	| <a id="share-link" href="javascript:void(0)"><?=$STR["share"]?></a>
	</div>

	<div id="share-url">
		<i class="icon link"></i>
		<input type="text" id="share-url-val" class="input"/>
	</div>
</div>
