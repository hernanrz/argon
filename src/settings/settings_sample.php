<?php
/**
* Load database settings and define constants
*/

include "database.php";


//Used for includes
define("AR_ROOT",  dirname(__FILE__));

//Contains the folder name where the project is in, leave blank if it's at the top of the webroot, should always end with a /, unless it's left blank
define("AR_FOLDER", "argon/src/");

//Used in the <base/> tag
define("AR_PATH", $_SERVER["HTTP_HOST"] ."/". AR_FOLDER);

$AR_AVAILABLE_LANGS = [
  'es' => 'Español'
];

//Defines the language the program is going to use
//Default is spanish, for now
$AR_LANG = "es";

if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
  $lang = substr($_SERVER["HTTP_ACCEPT_LANGUAGE"], 0, 2);
  if(isset($AR_AVAILABLE_LANGS[$lang])) {
    $AR_LANG = $lang;
  }
}

require AR_ROOT. "/../lang/{$AR_LANG}_locale.php";
?>