<?php

header("Content-type:application/json");

include('sites_headers.php');

include('verify_memory.php');

echo "node crawler.js '".$_GET['page']."' '".json_encode($headers)."'";

// $json = shell_exec('node crawler.js "https://www.buscape.com.br/" "'.json_encode($headers).'"');

// $json = shell_exec('node crawler.js "'.$_GET['page'].'"');

// $json = json_decode($json,true);

// $json = json_encode($json,JSON_UNESCAPED_UNICODE);

// echo $json;

?>
