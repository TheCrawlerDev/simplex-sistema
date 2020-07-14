<?php

function new_scan($url){
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, 'https://http-observatory.security.mozilla.org/api/v1/analyze?host='.$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "hidden=false&rescan=false");
	curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

	$headers = array();
	$headers[] = 'Connection: keep-alive';
	$headers[] = 'Pragma: no-cache';
	$headers[] = 'Cache-Control: no-cache';
	$headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
	$headers[] = 'Origin: https://observatory.mozilla.org';
	$headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Mobile Safari/537.36';
	$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
	$headers[] = 'Sec-Fetch-Site: same-site';
	$headers[] = 'Sec-Fetch-Mode: cors';
	$headers[] = 'Referer: https://observatory.mozilla.org/';
	$headers[] = 'Accept-Encoding: gzip, deflate, br';
	$headers[] = 'Accept-Language: pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7';
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	    return 'Error:' . curl_error($ch);
	}
	curl_close($ch);
	return json_decode($result,true);
}

error_reporting(0);

header('Content-Type: application/json');

include('craw.php');

$craw = new Craw();

$data = array();

// $_GET['page'] = $craw->formatarURL(['http://','https://'],$_GET['page']);

$_GET['page'] = $craw->url_path($_GET['page']);

$scan = new_scan($_GET['page']);

$i = 0;

while(intval($scan['scan_id'])==0){

	$scan = new_scan($_GET['page']);

	$i++;

	if($i>5){
		break;
	}

}

$data['r1'] = $craw->carregar('https://http-observatory.security.mozilla.org/api/v1/analyze?host='.$_GET['page']);

$i = 0;

while($data['r1']['state']<>'FINISHED'){

	sleep(5);

	$data['r1'] = $craw->carregar('https://http-observatory.security.mozilla.org/api/v1/analyze?host='.$_GET['page']);
	$i++;

	if($i>5){
		break;
	}

}

$data['r2'] = $craw->carregar('https://hstspreload.org/api/v2/status?domain='.$_GET['page']);

$data['r3'] = $craw->carregar('https://hstspreload.org/api/v2/preloadable?domain='.$_GET['page']);

// $data[] = $craw->carregar('https://www.immuniweb.com/ssl/api/v1/check/1579894205634.html');
https://http-observatory.security.mozilla.org/api/v1/getScanResults?scan=13069196

$data['r4'] = $craw->carregar('https://api.ssllabs.com/api/v2/analyze?publish=off&fromCache=on&maxAge=24&host='.$_GET['page']);
// echo 'https://http-observatory.security.mozilla.org/api/v1/getScanResults?scan='.$data['r1']['scan_id'];
$data['r5'] = $craw->carregar('https://http-observatory.security.mozilla.org/api/v1/getScanResults?scan='.$data['r1']['scan_id']);

$data['r6'] = $craw->carregar('https://http-observatory.security.mozilla.org/api/v1/getHostHistory?host='.$_GET['page']);

$data['r7'] = $craw->carregar('https://tls-observatory.services.mozilla.com/api/v1/results?id='.$_GET['page']);

echo json_encode($data);

?>
