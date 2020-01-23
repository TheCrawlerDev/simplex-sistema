<?php

// definimos as rotas

$routes['delete']['agenda'] = array('set'=>'Crawler@deletar','params'=>'agenda/id');
$routes['post']['agenda'] = array('set'=>'Crawler@editar','params'=>'agenda/id');
$routes['get']['agenda'] = array('set'=>'Crawler@selecionar','params'=>'agenda/id');
$routes['get']['alexa'] = array('set'=>'Crawler@alexa','params'=>'alexa');
$routes['get']['lighthouse'] = array('set'=>'Crawler@lighthouse','params'=>'lighthouse');
$routes['get']['pagespeed'] = array('set'=>'Crawler@pagespeed','params'=>'pagespeed');
$routes['get']['robots'] = array('set'=>'Crawler@robots','params'=>'robots');
$routes['get']['seo_crawler'] = array('set'=>'Crawler@seo_crawler','params'=>'seo_crawler');

?>