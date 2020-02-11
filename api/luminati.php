<?php
// echo 'To enable your free eval account and get CUSTOMER, YOURZONE and '
//     .'YOURPASS, please contact sales@luminati.io';
// $username = 'lum-customer-hl_96689a01-zone-fr1-route_err-pass_dyn';
// $password = '5k1qyanxawch';
// $port = 22225;
// $user_agent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';
// $session = mt_rand();
// $super_proxy = 'zproxy.lum-superproxy.io';
// $url = 'www.google.com';
// $curl = curl_init($url);
// curl_setopt($curl, CURLOPT_FAILONERROR, true);
// curl_setopt($curl, CURLOPT_HEADER, 0);
// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true );
// curl_setopt($curl, CURLOPT_ENCODING, "" );
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, true );
// curl_setopt($curl, CURLOPT_AUTOREFERER, true );
// curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 12000 );
// curl_setopt($curl, CURLOPT_TIMEOUT, 12000 );
// curl_setopt($curl, CURLOPT_MAXREDIRS, 10 );
// curl_setopt($curl, CURLOPT_REFERER, $url);
// curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
// curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// curl_setopt($curl, CURLOPT_PROXY, "http://$super_proxy:$port");
// curl_setopt($curl, CURLOPT_PROXYUSERPWD, "$username-session-$session:$password");
// $result = curl_exec($curl);
// curl_close($curl);
// var_dump($result);

$curl = curl_init('https://www.buscape.com.br');
curl_setopt($curl, CURLOPT_PROXY, 'http://zproxy.lum-superproxy.io:22225');
curl_setopt($curl, CURLOPT_PROXYUSERPWD, 'lum-customer-hl_96689a01-zone-br1:11jvaj818zqf');
var_dump(curl_exec($curl));

?>