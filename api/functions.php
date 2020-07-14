<?php

function microlink($url){

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.microlink.io?url='.$url.'&data.html.selector=html',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 0,
    CURLOPT_TIMEOUT => 40,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  // $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
  // $headers = substr($response, 0, $header_size);
  // echo $headers;

  curl_close($curl);

  $headers = craw($url)['header'];

  if ($err) {
    return ['headers'=>$headers,'body'=>'cURL Error #:' . $err];
  } else {
    return ['headers'=>$headers,'body'=>json_decode($response,true)];
  }

}

function craw($url,$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36',$timeout = 12000){
  // $useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
  // $timeout = 12000;
  $dir = dirname(__FILE__);
  $cookie_file = $dir . '/cookies/' . md5($_SERVER['REMOTE_ADDR']) . '.txt';
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_FAILONERROR, true);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
  curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
  curl_setopt($ch, CURLOPT_ENCODING, "" );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_AUTOREFERER, true );
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout );
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout );
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
  curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
  curl_setopt($ch, CURLOPT_REFERER, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_VERBOSE, 1);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  // $retorno = curl_exec($ch);
  // $this->returnPage = $retorno;
  $response = curl_exec($ch);
  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
  $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $header = substr($response, 0, $header_size);
  $body = substr($response, $header_size);
  $info = curl_getinfo($ch);
  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  $header = substr($response, 0, $header_size);
  curl_close($ch);
  // if($status<>200) return curl_proxy($url);
      return ['content'=>$response,'header_size'=>$header_size,'header'=>$header,'body'=>$body,'content_type'=>$content_type,'info'=>$info,'status'=>$status];
}

function scrapestack_render_js2($url){
  $queryString = http_build_query([
    // 'access_key' => 'd3b1a84694cd9bcec0760ae769316abf',
    // 'access_key' => 'de313918d57c4ba42563039281fbf772',
    'access_key' => '674d6e8ad1d05458b4dfe17fc5c6d3ab',
    'url' => $url,
    'render_js' => 1,
  ]);
  // alexandre@hibots.com.br h12345678

  $ch = curl_init(sprintf('%s?%s', 'http://api.scrapestack.com/scrape', $queryString));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $website_content = curl_exec($ch);
  curl_close($ch);

  $headers = craw($url)['header'];

$status = craw($url)['status'];

  return ['html'=>$website_content,'headers'=>$headers,'status'=>$status];
}



  function contains($str,$find){
    return boolval(strpos($str, $find));
  }
  function unhtmlentities($string){
      // replace numeric entities
      $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
      $string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
      // replace literal entities
      $trans_tbl = get_html_translation_table(HTML_ENTITIES);
      $trans_tbl = array_flip($trans_tbl);
      return strtr($string, $trans_tbl);
  }
  function tags($doc,$tag,$restrict){
    $divs = $doc->getElementsByTagName($tag);
    $retorno = array();
    foreach($divs as $div){
      $node_html = $div->ownerDocument->saveXML( $div );
      $add = true;
      if(strlen($div->nodeValue)>2){
  	     foreach($restrict as $r){
       		  if(contains($node_html,$r)==false)  $add = false;
        		elseif(strlen($div->nodeValue)<=1) $add = false;
      	 }
  	     if($add==true)  $retorno[] = urldecode(trim($div->nodeValue));
      }
    }
    return $retorno;
  }

  function tags2($doc,$tag,$restrict){
    $divs = $doc->getElementsByTagName($tag);
    $retorno = array();
    foreach($divs as $div){
      $node_html = $div->ownerDocument->saveXML( $div );
      $add = true;
      if( ( strpos($node_html,$restrict[0]) !== false || count($restrict) == 0 ) )  $retorno[] = trim($node_html);
    }
    return $retorno;
  }

  function is_json($string) {
   json_decode($string);
   return (json_last_error() == JSON_ERROR_NONE);
  }

function puppeteer($page){

  $json = shell_exec('node crawler.js "'.$page.'"');

  $json = json_decode($json,true);

  return $json;
}

function verify_memory(){
    exec('ps -aux', $processes);
    $retorno = array();
    $cpuUsage = 0;
    $retorno['CPU_valid'] = true;
    $retorno['%CPU'] = 0;
    $retorno['%MEM'] = 0;
    foreach($processes as $process){
        $process = str_replace('  ',' ',$process);
        $cols = explode(' ',$process);
        $cols = array_filter($cols);
        $cols_n = array();
        foreach($cols as $col){
          $cols_n[] = $col;
        }
        $retorno['%CPU'] += floatval($cols_n[2]);
        $retorno['%MEM'] += floatval($cols_n[3]);
    }
    if($retorno['%CPU']>=70){
      $retorno['CPU_valid'] = false;
    }
    return $retorno;
}

function nullable($str){
  if(strlen(trim($str))>3){
    return trim($str);
  }else{
    return null;
  }
}

function carregar2($url, $post = null, $access_token = null, $action = 'POST',$head = array()){
      global $TEMPOCARREGAMENTO, $NUMEROCARREGAMENTO;

      $ini = microtime(true);
      $ch = curl_init();
      $head = array();
      curl_setopt($ch, CURLOPT_URL, $url);
      if (!is_null($post)) {
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      }
      if (!is_null($post) && is_string($post)) {
          $head[] = "Content-Type: application/json";
      }
      if ($action != 'POST') {
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $action);
      }
      if (count($head) > 0)
          curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      curl_setopt($ch, CURLOPT_TIMEOUT, 90);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


      $resp = curl_exec($ch);
      if ($resp === false) {
          echo curl_error($ch);
      }
  //  echo $resp;
      $respA = json_decode($resp, true);

      curl_close($ch);
      //file_put_contents('log.txt', date('c')."$url\t$resp\r\n",FILE_APPEND);
      return $respA;
  }

  function pesquisar($string, $after, $before,$striptags=true){
		$subresult = '';
		if(strpos($string,$after) !== false) {
			$subresult = substr($string,strpos($string,$after)+strlen($after));
			$subresult = strchr($subresult,$before,true);
		}
		$subresult = str_replace('&nbsp;','',$subresult);
		return $striptags===true ? strip_tags(trim($subresult)) : trim($subresult);
	}


?>
