<?php

// header('Content-Type: application/json;charset=utf-8');

// error_reporting(0);

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

$sites = array(
  array(
    'base'=>'www.centauro.com.br',
    'ua'=>null,
    'headers'=>'Cookie: nfabr=100; nfabv=B; ulocation=eyAiY291bnRyeV9jb2RlIiA6ICJCUiIsICJyZWdpb25fY29kZSIgOiAiQ0UiLCAiY2l0eSIgOiAiRk9SVEFMRVpBIiwgIm5ldHdvcmtfdHlwZSIgOiAiIiwgImlzX21vYmlsZSIgOiAidHJ1ZSIsICJpc190YWJsZXQiIDogImZhbHNlIiwgImJyYW5kX25hbWUiIDogIkdvb2dsZSIgfQ==; favoriteButtonB=fora do teste; buyBox=piloto; AKA_A2=A; _pk_ses.1.a619=*; chaordic_browserId=0-uMVKePAR5xnC7efLQG2Gsl8nzwMxDpn1cKty15907776777624366; chaordic_anonymousUserId=anon-0-uMVKePAR5xnC7efLQG2Gsl8nzwMxDpn1cKty15907776777624366; chaordic_session=1590777677848-0.5931212024878965; chaordic_testGroup=%7B%22experiment%22%3A%22CENTAURO_TRENDING_PURCHASE_2019-06-11%22%2C%22group%22%3A%22D%22%2C%22testCode%22%3A%22CENTAURO_TRENDING_PURCHASE_2019-06-11_D%22%2C%22code%22%3A%22CENTAURO_TRENDING_PURCHASE_2019-06-11_D%2FXixN1M7FPJXjrxxuk4M5JcKkfMuyIiv4%22%2C%22session%22%3A%22XixN1M7FPJXjrxxuk4M5JcKkfMuyIiv4%22%7D; bm_mi=99E465A67A93E8611B232FF476500924~VsUnJT9XShVYccXh4zdfqn6f8KLzoR9WCdO7nnG328l4OcRSWnnJB5mMKgE5eVCLVuUb2AVHkcZbCsjbf3TyCtbztukIS5BcpQC640m5GCQd3GQ95k/iC1aQq+cIac+9hM63/e5LzGBuYbJXJkCtjVLb/RM4MNy9nf9zg95F8hRJDEMOXrjU+THsuB9CjfJPZ8Y55z3HbuuuSHOp+WjCVZ4a8nM4fo08+bcLj82I10k=; bm_sv=4AA8ACBA35AA7E634F4D2FC42310094B~wv4O8EMBm2b5lH+4GJdHY+EWTHA6rLlWr3wUl+5o9HXh7goMcD3lFy5ug97JkwBsZy5OdHRsxEK54TLZcwQkhrGh6RjMoscRO+pr6POxUqryNj5VS3CFqji7sy7kc7Pe7uZGo5hvjQUv7y9O7H5qv+2MbqA3i9Q1XyAIwwJ7tz8=; _pk_id.1.a619=5f9f36fcd29aa776.1590777676.1.1590777861.1590777676.; ak_bmsc=F32872451043511C183276A793D7D3F8C99F9F4CB41100004B57D15EF5B12274~plpuwEqwfiBSkVDgSmlPNuQ4P+/4RtrCfG+6jBKwzQFlEsgowqo9fZzrhFizGGGPkpjOwLlQ4g1/h6883LDGU289Epo8Xokp8TlkzLYUuMD57D9QybY0I7YG9xT/mZ9ESCLSDkXiKS3hIktSZ39h4U4Onu/MsspwqfbBjhI12jYzftyfh2KW1oEO3CRwsemJxdLS4gdnyQJLU2wSEjsgt9UAggfm+T8ZJeY04Ch+9LPzF4jhYRSPC/ZwtfzPZXOcHbtnhxxiKN0Lu4n9u6FT+ncg=='
  ),
  array(
    'base'=>'www.pontofrio.com.br',
    'ua'=>null,
    'headers'=>'Cookie: dtCookie=A44D836AC0B8B9C4203FC718B4181735|X2RlZmF1bHR8MQ; IPI-PontoFrio.com=UsuarioGUID=82bf8f80-4cfa-4a90-a336-53057762f81d; ISS-PontoFrio.com=TesteAB=B; lx_sales_channel=%5B%22desktop%22%5D; IPSI-PontoFrio.com=; SGTS-PontoFrio.com=IndiceSegmentoHashCookie=2833B898E9D40610E2C68940627692F4'
  ),
  array(
    'base'=>'www.centauro.com.br',
    'ua'=>null,
    'headers'=>'Cookie: SPSI=766fd50c69f0838676622a216ffffc72; nfabr=100; nfabv=B; ulocation=eyAiY291bnRyeV9jb2RlIiA6ICJCUiIsICJyZWdpb25fY29kZSIgOiAiQ0UiLCAiY2l0eSIgOiAiRk9SVEFMRVpBIiwgIm5ldHdvcmtfdHlwZSIgOiAiIiwgImlzX21vYmlsZSIgOiAiZmFsc2UiLCAiaXNfdGFibGV0IiA6ICJmYWxzZSIsICJicmFuZF9uYW1lIiA6ICJDaHJvbWUiIH0=; Secure; favoriteButtonB=fora do teste; buyBox=piloto; continuar.comprando=; _pk_id..0101=9863214e4cf07d4e.1590617550.1.1590617613.1590617550.; AKA_A2=A; _pk_id.1.a619=5f9f36fcd29aa776.1590777676.1.1590777676.1590777676.; _pk_ses.1.a619=*; chaordic_browserId=0-uMVKePAR5xnC7efLQG2Gsl8nzwMxDpn1cKty15907776777624366; chaordic_anonymousUserId=anon-0-uMVKePAR5xnC7efLQG2Gsl8nzwMxDpn1cKty15907776777624366; chaordic_session=1590777677848-0.5931212024878965; chaordic_testGroup=%7B%22experiment%22%3A%22CENTAURO_TRENDING_PURCHASE_2019-06-11%22%2C%22group%22%3A%22D%22%2C%22testCode%22%3A%22CENTAURO_TRENDING_PURCHASE_2019-06-11_D%22%2C%22code%22%3A%22CENTAURO_TRENDING_PURCHASE_2019-06-11_D%2FXixN1M7FPJXjrxxuk4M5JcKkfMuyIiv4%22%2C%22session%22%3A%22XixN1M7FPJXjrxxuk4M5JcKkfMuyIiv4%22%7D; ak_bmsc=F32872451043511C183276A793D7D3F8C99F9F4CB41100004B57D15EF5B12274~pl19vJ55aLOIEtBVkdjbdaPhe/k9w2E+p30+MJhKTaru2tq5Hlv/WftIvuP25SJoS+qjmk6yG7Y2kXWTkB6RAd7bSEr1qAIrG7pmQPJRBJO5j+J+Py7F+M1+ogCd77tWk8G2ReBKJQ0Gdb7XSFxKP0y8iUNRPW4V7bc4CyuEWJAx0WfIupBV7Q6fwYw6wdfxCClBpOQoY91bF3L/b9ESUJ+9ZIUoQMr9LOSKIpvg8QuftIOByuIuOrQvX6fDpFrwzv'
  ),
  array(
    'base'=>'www.leroymerlin.fr',
    'ua'=>null,
    'headers'=>'Cookie: JSESSIONID=13HYYiLkEFZZJ0zc1LBBJV28; LMFRVIP2=5ccba3d8f88a99283f7b46a075dc49fce05c5a95d6d8aaf19c4693ecdbdd20e91fc43b5f; lm-csrf=qN8CV4C1LbNgPearMpzFavHps+SYDcmZOkX+8t+TtME=.1590777535959.dL5UJ8+y0qtdIzDPaZmtgZj4QwTeujHFrimXi1V4Qhk=; ss=ls_0.14_NaN_ss_0.01_NaN; 2af4bd368d43f7849ecea79b443038dc=df6d7ab1fc47e6294b02190a1d278bfb; datadome=3oxRAZ.fYx3sO2z5yT3kAEG0vY~A8mvsc2~ifQ~KHgVf82~ByQO1dnuvcJxScXS0L..ZNzfMqu9KEbO8Aa6aFDOihO64Kfn_ceqQh9q~WM; toky=1; AB_TEST_SOYOOZ=soyooz_on; AB_TEST_Iadvize=8'
  ),
  array(
    'base'=>'simplex.live',
    'ua'=>null,
    'headers'=>'Cookie: pll_language=br; SPSI=9e330c20c06a2d1079e20388c3779b2e; spcsrf=e82413b7b5c8a1113cde9bd203e006d7; sp_lit=IgIs2Dm685YKVhq/WsHReg==; PRLST=Rz; UTGv2=h4beed16df5058263c2e666ec88722b43512; adOtr=03YY903c260'
  ),
  array(
    'base'=>'www.shoptime.com.br',
    'ua'=>null,
    'headers'=>'Cookie: MobileOptOut=1; b2wDevice=eyJvcyI6IkxpbnV4Iiwib3NWZXJzaW9uIjoieDg2XzY0IiwidmVuZG9yIjoiQ2hyb21lIiwidHlwZSI6ImRlc2t0b3AiLCJta3ROYW1lIjoiQ2hyb21lIDc4IiwibW9kZWwiOiI3OCIsIm1vYmlsZU9wdE91dCI6ImZhbHNlIn0=; b2wDeviceType=desktop; b2wChannel=INTERNET; B2W-IU=false; B2W-PID=1590617631682.0.6560007203036999; searchTestAB=out; catalogTestAB=new; ak_bmsc=40811CEE042B211521D6EF74716B72C3BAD76F57B47600008759D15E069BBE17~pl8p1V0owUTUwQoftBmkaGeeY2R1q2Bjl4dVSyW19a/D5PsBn24hs1bmbGh6LSx4D8STf9glBF15rFZXL6HOGZKuBpvyAmUdcnYmPoy0XzazcONhqsZ9ae5SdLSYGAcyheEgf1DlFIzrL+0G9tfawPFR3+PTOs2ECWATzoTx8VB2OxYY29SFZgVOOAetS95gnbL8qto6lfn7dUzP75veE8InGkhqsSRkHc9CtQmxprQW4='
  ),
  array(
    'base'=>'www.webmotors.com.br',
    'ua'=>null,
    'headers'=>'Cookie: check=true; AMCVS_3ADD33055666F1A47F000101%40AdobeOrg=1; WebMotorsVisitor=1; mboxEdgeCluster=34; AMCV_3ADD33055666F1A47F000101%40AdobeOrg=-432600572%7CMCIDTS%7C18412%7CMCMID%7C46225895817680797140047895506218127565%7CMCOPTOUT-1590787546s%7CNONE%7CvVersion%7C4.5.2; mbox=session#59ec7f5c38944c0393001499392c295a#1590782206|PC#59ec7f5c38944c0393001499392c295a.34_0#1654025148'
  ),
  array(
    'base'=>'www.lorealparisusa.com',
    'ua'=>null,
    'headers'=>'Cookie: __cfduid=da39643eda7531c1a9c7538bc912e223b1590780452; AWSELB=81D51FF714FE9CE1FCAA484475F3B52068370C29E65F5D53EBCE2C62537364D2CC3BF169287FE01A52CD4A835D6243297B47D530FC1C530E44C0F53E8BC7C5505403A0767C'
  ),
  array(
    'base'=>'www.kiehls.com',
    'ua'=>null,
    'headers'=>'Cookie: __cfduid=d86c53f881d2cddb3c182884885e4c8ec1590780500'
  ),
  array(
    'base'=>'www.shoptime.com.br',
    'ua'=>null,
    'headers'=>'Cookie: b2wDevice=eyJvcyI6IkxpbnV4Iiwib3NWZXJzaW9uIjoieDg2XzY0IiwidmVuZG9yIjoiQ2hyb21lIiwidHlwZSI6ImRlc2t0b3AiLCJta3ROYW1lIjoiQ2hyb21lIDc4IiwibW9kZWwiOiI3OCIsIm1vYmlsZU9wdE91dCI6ImZhbHNlIn0=; b2wDeviceType=desktop; b2wChannel=INTERNET; B2W-IU=false; B2W-PID=1590617631682.0.6560007203036999; searchTestAB=out; catalogTestAB=new; ak_bmsc=40811CEE042B211521D6EF74716B72C3BAD76F57B47600008759D15E069BBE17~plTLjHdNP/JxE9Dn7F3NAGUJyhEQLCJTxGNGgiFo3JtRc4PHsHyTtrev1U9eCRReLxd09Y6eeO+32mmOdWxyOiELd3kZzIR/vDjiUTIEO8o1NJpnX5SAQb0lzUPTnbyyf8g1uVJ5VGz7kem7Mw/LQRtNVG3pYozpaya8sz7S4JxqvLXD5YWNQIuy5pPnZo0dPKFguVlPuxeAH+xIjfBVVW7A3da0PPEqZoGwDpBMn1MoFC9gtahPe3vBh043J6KRa8; bm_mi=A03541F719377E935DD0691FF8833446~1YeRYDv1w2XzYZv5+R3l3HrRTe5Fsj0828WKN6geFjiXicAMSeIeHHkjCwpyUICCWUK2yIbKGmZb5XQjCAxAQ59tdd0DCA1J2/MY/Q+OHfAmp6vzm2vzvbtJ9V+t8vh5l33u9pEIzG9D4rn9HObjX+9K5ihBsbQYTTrvOLAtWRTWgMjHEYhbDIhInazceQGN+rQvfNciLjYpMdV0cWHOAfMK5CneBpEdKUULOmnB+ps=; bm_sv=B7D1B6E995AA3CB64E028E3FAC56F041~7aFbgkrRQ/LeD+R2X4zVO2jE+p8c8K22UKnKRKg/3m25wJaOIBir3ILsf+ZLnIVhWLvdkBDhNR6tQF2juI9nMdI5YQ/UO1M5Rv2Qj/1zwLrdmBrf3Jej6XTHZ8DepvYuSaVm2iSmnKFm/ZtwJ+y5O7MOujb6EZTj3iHCMqJ8mQ8=; MobileOptOut=1'
  ),
  array(
    'base'=>'www.carrefour.com.br',
    'ua'=>'simplex-c4',
    'headers'=>'',
  ),
);
//
function headers($base,$cookie,$ua){
  $headers[] = 'Referer: https://www.google.com/';
  $headers[] = 'Authority: '.$base;
  $headers[] = 'Cache-Control: max-age=0';
  $headers[] = 'Upgrade-Insecure-Requests: 1';
  if(is_null($ua)){
    $headers[] = 'User-Agent: Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Mobile Safari/537.36';
  }else{
    $headers[] = 'User-Agent: '.$ua;
  }
  $headers[] = 'Sec-Fetch-User: ?1';
  $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
  $headers[] = 'Sec-Fetch-Site: none';
  $headers[] = 'Sec-Fetch-Mode: navigate';
  $headers[] = 'Accept-Encoding: gzip, deflate, br';
  $headers[] = 'Accept-Language: en-US,en;q=0.5';
  if(strlen($cookie)>2){
    $headers[] = $cookie;
  }
  return $headers;
}

$headers = array();

foreach($sites as $site){
  if(strpos($_GET['page'], $site['base'])!==false){
    $headers = headers($site['base'],$site['headers'],$site['ua']);
  }else{
    $headers = headers($_GET['page'],'',null);
  }
}

// echo json_encode($headers);



?>
