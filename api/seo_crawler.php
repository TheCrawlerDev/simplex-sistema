<?php

error_reporting(0);

set_time_limit(20);

// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');
// error_reporting(E_ALL);

header('Content-Type: application/json;charset=utf-8');

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

include('craw.php');
include('../app/Helpers/helper.php');
$craw = new Craw();
// $req = json_decode(file_get_contents('http://142.93.189.150/cerebro/api/chrome.php?url='.$_GET['page']),true);
while(verify_memory()['CPU_valid']==false){
  sleep(1);
}
$_GET['page'] = urlencode($_GET['page']);
// echo 'http://165.227.188.205/puppeteer/pupe.php?page='.$_GET['page'];
// $req = carregar2('http://165.227.188.205/puppeteer/pupe.php?page='.$_GET['page']);
$req = json_decode(file_get_contents('http://165.227.188.205/puppeteer/pupe.php?page='.$_GET['page']),true);
// echo json_encode($req);
// die();

// while(!isset($req['status'])){
//   sleep(2);
//   $req = json_decode(carregar('http://165.227.188.205/puppeteer/pupe.php?page='.$_GET['page']),true);
// }
// $req = puppeteer($_GET['page']);
$html = $req['response'];
$doc = new DOMDocument;
$doc->loadHTML($html);

// extract headers
$head = pesquisar($html,'<head>','</head>',false);
$headers_f = explode('<meta',$head);
$headers = array();
foreach($headers_f as $h){
  $name = pesquisar($h,'name="','"',false);
  $content = pesquisar($h,'content="','"',false);
  if(strlen($name)>0)	$headers[$name] = $content;
}
// extract headers
// echo $html;
if(isset($_GET['api'])){
  $title = multiexplode(array('|',',','-'),pesquisar($html,'<title>','</title>',false));
  $dados['success'] = true;
  $dados['status'] = $req['status'];
  $dados['html'] = $html;
  $dados['nhtml'] = strlen($html);
  $dados['canonical'] = tags2($doc,'link',['canonical']);
  $dados['ntitle'] = count($title);
  $dados['title'] = ((($title)));
  $dados['keywords'] = (($headers['keywords']));
  $dados['description'] = (($headers['description']));
  $dados['robots'] = (($headers['robots']));
  $dados['links'] = tags($doc,'a',['href']);
  $dados['nlinks'] = count(tags($doc,'a',['href']));
  $dados['imgs'] = tags2($doc,'img',[]);
  $dados['imgsalt'] = tags2($doc,'img',['alt']);
  $dados['nimgs'] = count(tags2($doc,'img',[]));
  $dados['nimgsalt'] = count(tags2($doc,'img',['alt']));
  $dados['javascript'] = tags2($doc,'script',[]);
  $dados['css'] = array_merge(tags2($doc,'style',[]),tags2($doc,'link',['stylesheet']));
  $dados['njavascript'] = count(tags2($doc,'script',[]));
  $dados['ncss'] = count(tags2($doc,'style',[])) + count(tags2($doc,'link',['stylesheet']));
  $dados['headers'] = $req['status'];
  $dados['h1'] = (((tags($doc,'h1',[]))));
  $dados['h2'] = (((tags($doc,'h2',[]))));
  $dados['h3'] = (((tags($doc,'h3',[]))));
  $dados['h4'] = (((tags($doc,'h4',[]))));
  $dados['h5'] = (((tags($doc,'h5',[]))));
  $dados['h6'] = (((tags($doc,'h6',[]))));
  $dados['nh1'] = count(tags($doc,'h1',[]));
  $dados['nh2'] = count(tags($doc,'h2',[]));
  $dados['nh3'] = count(tags($doc,'h3',[]));
  $dados['nh4'] = count(tags($doc,'h4',[]));
  $dados['nh5'] = count(tags($doc,'h5',[]));
  $dados['nh6'] = count(tags($doc,'h6',[]));
  echo json_encode($dados,JSON_UNESCAPED_UNICODE);
}else{
  $title = multiexplode(array('|',',','-'),pesquisar($html,'<title>','</title>',false));
  $dados['success'] = true;
  $dados['status'] = $req['status'];
  $dados['html'] = $html;
  $dados['nhtml'] = strlen($html);
  $dados['canonical'] = stringify_sql(tags2($doc,'link',['canonical']));
  $dados['ntitle'] = count($title);
  $dados['title'] = ((($title)));
  $dados['keywords'] = stringify_sql(($headers['keywords']));
  $dados['description'] = stringify_sql(($headers['description']));
  $dados['robots'] = stringify_sql(($headers['robots']));
  $dados['links'] = tags($doc,'a',['href']);
  $dados['nlinks'] = count(tags($doc,'a',['href']));
  $dados['imgs'] = tags2($doc,'img',[]);
  $dados['imgsalt'] = tags2($doc,'img',['alt']);
  $dados['nimgs'] = count(tags2($doc,'img',[]));
  $dados['nimgsalt'] = count(tags2($doc,'img',['alt']));
  $dados['javascript'] = tags2($doc,'script',[]);
  $dados['css'] = array_merge(tags2($doc,'style',[]),tags2($doc,'link',['stylesheet']));
  $dados['njavascript'] = count(tags2($doc,'script',[]));
  $dados['ncss'] = count(tags2($doc,'style',[])) + count(tags2($doc,'link',['stylesheet']));
  $dados['headers'] = $req['status'];
  $dados['h1'] = (((tags($doc,'h1',[]))));
  $dados['h2'] = (((tags($doc,'h2',[]))));
  $dados['h3'] = (((tags($doc,'h3',[]))));
  $dados['h4'] = (((tags($doc,'h4',[]))));
  $dados['h5'] = (((tags($doc,'h5',[]))));
  $dados['h6'] = (((tags($doc,'h6',[]))));
  $dados['nh1'] = count(tags($doc,'h1',[]));
  $dados['nh2'] = count(tags($doc,'h2',[]));
  $dados['nh3'] = count(tags($doc,'h3',[]));
  $dados['nh4'] = count(tags($doc,'h4',[]));
  $dados['nh5'] = count(tags($doc,'h5',[]));
  $dados['nh6'] = count(tags($doc,'h6',[]));
  echo json_encode($dados,JSON_UNESCAPED_UNICODE);
}
?>
