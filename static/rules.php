<?php

$uri = str_replace(PATH, '', $_SERVER['REQUEST_URI']);

$method = strtolower($_SERVER['REQUEST_METHOD']);

$req = explode('/', trim($uri,'/'));

$body = json_decode(file_get_contents('php://input'),true);

$route = explode('@',$routes[$method][$req[0]]['set']);

$request = array_combine(
	explode('/',$routes[$method][$req[0]]['params']),
	$req
);

$controller = $route[0];

$function = $route[1];


?>