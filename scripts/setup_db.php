#!/usr/bin/php
<?php
/**
*	Sets database info and stores it in src/settings/database.php
*/
$variables = [];
$output_file = __DIR__ . "/../src/settings/database.php";

echo "Database name: ";
$variables["db_name"] = readline();

echo "\nDatabase username: ";
$variables["db_user"] = readline();

echo "\nDatabase password: ";
$variables["db_password"] = readline();

echo "\nDatabase hostname: ";
$variables["db_host"] = readline();

$script_code = "<?php\n";

foreach($variables as $k=>$v) {
	$script_code .= "$".$k.' = "'.$v."\";\n";
}

$script_code .= '?>';

file_put_contents($output_file, $script_code);
?>