<?php
	include "core.php";

	if (isset($_GET["UID"])) {
		$note = new Note($pdo_link, $_GET["UID"]);
	}

	$view_mode = isset($_GET["UID"]) && isset($_GET["view"]);
?>
<!DOCTYPE html>
<html>
	<head>
		<base href="//<?=AR_PATH?>" />
		<link rel="shortcut icon" href="favicon.ico"> 
		<link href="css/style.css" rel="stylesheet" />
		<link href="css/icons.css" rel="stylesheet" />
		<link href="css/responsive.css" rel="stylesheet" />
		<link href="css/utils.css" rel="stylesheet" />
	    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
		<script src="js/jquery.min.js"></script>
		<script src="js/app.js" type="text/javascript"></script>
	
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1,user-scalable=no" />
		<title><?php print (isset($note) ? htmlentities($note->title)." | " : "") ?>Argón</title>
	</head>
	<body <?php if ($view_mode) { ?>class="view"<?php } ?>>
		<?php
			if($view_mode) {
				include "templates/note_viewer.php";
			}else {
				include "templates/note_editor.php";
			}
		?>
	</body>
</html>
