#!/usr/bin/php
<?php
/**
* i18n code generator 0.1
*/

$file_name = $argv[1];
$file_dir = isset($argv[2]) ? $argv[2] : "../i18n/";
$output_dir = "../src/lang/";

if(!$file_name) {
    print "Usage:\n i18n.php -f filename.json -p (optional) path to file\n";
    exit;
}

$contents = file_get_contents($file_dir.$file_name.".json");

$contents = json_decode($contents, true);

print "Now parsing language: ". $contents["lang_human"] ."\n";

$frontend_strings = json_encode($contents["strings"]);

$js_code = <<<JS
Ar.loc = $frontend_strings;
JS;

file_put_contents($output_dir.$contents["lang_code"]."_locale.js", $js_code);

$backend_array = var_export(array_merge($contents["API_strings"], $contents["strings"]), true);

$php_code = <<<EOT
<?php
\$STR = $backend_array;\n
EOT;

$php_code .= '?>';

file_put_contents($output_dir.$contents["lang_code"]."_locale.php", $php_code);

echo "\n* Javascript and PHP language files created in $output_dir\n";
echo "Remember to edit settings.php if you're adding a new language\n";

?>