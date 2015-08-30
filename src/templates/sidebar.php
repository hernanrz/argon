<div id="sidebar">
	<div id="logo" class="text-center">
		<a href="//<?= AR_PATH ?>"><i class="icon argon"></i></a>
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
			<form id="actual-register-form" action="javascript:void(0)">
				<p><?=$STR["username"]?></p>
				<input id="register_username" required type="text" placeholder="<?=$STR["username"]?>" />
				<p><?=$STR["password"]?></p>
				<input id="register_password" required type="password" placeholder="<?=$STR["password"]?>" />
				<p><?=$STR["repeat_password"]?></p>
				<input id="register_password_check" required type="password" placeholder="<?=$STR["password"]?>" />

				<button type="submit" class="btn f-right" id="register-button"><?=$STR["register_button"]?></button>
				<a id="cancel-register" href="javascript:void(0)" class="f-left">&#8678; <?=$STR["cancel"]?></a>
			</form>
		</div>
		<br class="clearfix" />
		<div class="status-box clearfix hidden">
			<i class="icon warning"></i><span id="ajax-message"></span>
		</div>
	</div>

	<div id="user_dashboard" class="hidden">
		<p><?= $STR["greet"]; ?>, <i id="sb_username"></i>		<a class="f-right" href="javascript:flushSession()">Cerrar sesión</a></p>
		<div id="sb_notes_container">
			<div class="note_list_item text-center" onclick="clearPanel()"><?=$STR["new_note"]?></div>
			<hr />
			<p id="note_list_header"><?=$STR["your_notes"]?>: </p>
			<ul id="note_list">
			</ul>
		</div>
	</div>
</div>
