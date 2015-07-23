<?php
/**
* Load database settings and define constants
*/

include "database.php";

define("AR_FOLDER", "");//Contains the folder name where the project is in, leave blank if it's at the top of the webroot, should always end with a /, unless it's left blank
define("AR_PATH", $_SERVER["HTTP_HOST"] ."/". AR_FOLDER); //Used in the <base/> tag
?>
