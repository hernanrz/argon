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
?>
<div id="sidebar">
	<div id="logo" class="text-center">
		<i class="icon argon"></i>
	</div>
	<div id="forms">
		<div id="login-form">
			<form action="javascript:void(0)" onsubmit="javascript:void(0)">
				<p><?=$STR["have_account_prompt"]?></p>
				<input id="login-username" type="text" placeholder="<?=$STR["username"]?>" />
				<input id="login-password" type="password" placeholder="<?=$STR["password"]?>" />
				<input type="checkbox" id="remember-box" checked="checked" /> <label for="remember-box"> <?=$STR["remember_me"]?></label>

				<button type="submit" id="login-button" class="btn f-right"><?=$STR["login_button"]?></button>
				<a class="f-left" id="register-link" href="javascript:void(0)"><?=$STR["register_link"]?></a>
			</form>
		</div>

		<div id="register-form" class="hidden">
			<p><?=$STR["username"]?></p>
			<input type="text" placeholder="<?=$STR["username"]?>" />
			<p><?=$STR["password"]?></p>
			<input type="password" placeholder="<?=$STR["password"]?>" />
			<p><?=$STR["repeat_password"]?></p>
			<input type="password" placeholder="<?=$STR["password"]?>" />

			<button class="btn f-right"><?=$STR["register_button"]?></button>
			<a id="cancel-register" href="javascript:void(0)" class="f-left">&#8678; <?=$STR["cancel"]?></a>
		</div>
		<br class="clearfix" />
		<div class="status-box clearfix hidden">
			<i class="icon warning"></i><span id="ajax-message"></span>
		</div>
	</div>

	<div id="user_dashboard" class="hidden">
		<p>Hey, <i id="sb_username">poopsmashher</i></p>
		<div id="sb_notes_container">
			<p id="note_list_header">Your notes: </p>
			<ul>
				<li>Things</li>
			</ul>
		</div>
	</div>
</div>

<div class="page-block">
	<input value="<?= (isset($note) ? $note->title : "") ?>" placeholder="Nueva Nota" type="text" id="title" class="input"/>
	<div id="text-content">
		<textarea id="text-area" class="input"><?= (isset($note) ? $note->content : "") ?></textarea>
	</div>
	<div id="bottom-actions">
		<input type="checkbox" id="private-chk" checked="<?php isset($note) ? ($note->private ? "checked" : "") : "" ?>" name="private"><label for="private-chk"> <?=$STR["private_note"]?></label>
		<button class="flat-btn" id="save-btn"><?=$STR["save"]?></button>
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
