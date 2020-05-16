<?php

header("Content-type:application/json");

// $json = shell_exec('node crawler.js "https://www.buscape.com.br/"');

$json = shell_exec('node crawler.js "'.$_GET['page'].'"');

$json = json_decode($json,true);

$json = json_encode($json,JSON_UNESCAPED_UNICODE);

echo $json;

?>
