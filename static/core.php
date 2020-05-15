<?php

// error_reporting(0);

error_reporting(E_ALL);

include(MODEL_DIR.$controller.'.php');

$model_name = $controller.'Model';

$class_model = new $model_name;

include(CONTROLLER_DIR.$controller.'.php');

$controller_name = $controller.'Controller';

$class_controller = new $controller_name;

$data = $class_controller->$function($class_model,$request,$body);

if(isset($data['data_HTML_rendered'])==true){
	include('config/templates.php');
}


?>
