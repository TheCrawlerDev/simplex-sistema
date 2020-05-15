<?php

error_reporting(0);

header('Content-Type: application/json;charset=utf-8');

function microlink($url){

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.microlink.io?url='.$url.'&data.html.selector=body',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 0,
    CURLOPT_TIMEOUT => 40,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_AUTOREFERER => true,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
    CURLOPT_HEADER => 0,
  ));
  
  $response = curl_exec($curl);

  $err = curl_error($curl);

  curl_close($curl);

  return json_decode($response,true);

}

include('craw.php');
include('../app/Helpers/helper.php');
$craw = new Craw();
$robots_page = $_GET['page'].'/robots.txt';
$robots_page = str_replace('//','/',$robots_page);
$req = microlink($robots_page);
$html = $req['html'];
$dados['success'] = true;
$dados['status'] = $req['status'];
$dados['text'] = strip_tags($req['data']['html']);
print_r($dados);
// echo json_encode($dados,JSON_UNESCAPED_SLASHES,JSON_UNESCAPED_UNICODE);
?>
