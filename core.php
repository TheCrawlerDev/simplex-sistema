<?php

error_reporting(0);

error_reporting(E_ALL);

error_log("You messed up!", 3, "errors-log.log");

include(MODEL_DIR.$controller.'.php');

$model_name = $controller.'Model';

$class_model = new $model_name;

include(CONTROLLER_DIR.$controller.'.php');

$controller_name = $controller.'Controller';

$class_controller = new $controller_name;

$class_controller->$function($class_model,$request,$body);

?>