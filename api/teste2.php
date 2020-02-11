<?php
	
echo 'To enable your free eval account and get CUSTOMER, YOURZONE and '
    .'YOURPASS, please contact sales@luminati.io';
$curl = curl_init('http://lumtest.com/myip.json');
curl_setopt($curl, CURLOPT_PROXY, 'http://zproxy.lum-superproxy.io:22225');
curl_setopt($curl, CURLOPT_PROXYUSERPWD, 'lum-customer-hl_96689a01-zone-static-route_err-pass_dyn-country-al:cztfqszvvoik');
curl_exec($curl);
?>