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
<div id="sidebar">
	<div id="logo" class="text-center">
		<i class="icon argon"></i>
	</div>

	<div id="login-form">
		<p>¿Tienes una cuenta?</p>
		<input type="text" placeholder="Usuario" />
		<input type="password" placeholder="Contraseña" />
		<input type="checkbox" id="remember-box" checked="checked" /> <label for="remember-box"> Recordarme</label>

		<button class="btn f-right">Entrar</button>
		<a class="f-left" id="register-link" href="javascript:void(0)">Crear una cuenta</a>
	</div>

	<div id="register-form" class="hidden">
		<p>Nombre de usuario</p>
		<input type="text" placeholder="Nombre de usuario" />
		<p>Contraseña</p>
		<input type="password" placeholder="Contraseña" />
		<p>Repite tu contraseña</p>
		<input type="password" placeholder="Contraseña, de nuevo" />

		<button class="btn f-right">Registrar</button>
		<a id="cancel-register" href="javascript:void(0)" class="f-left">&#8678; Cancelar</a>
	</div>
	<br class="clearfix" />
	<div class="status-box clearfix hidden">
		<i class="icon warning"></i><span>We are yet to bleed, all of the time and energy. Half awake and almost dead. Keeping empty beds elsewhere</span>
	</div>

</div>

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
