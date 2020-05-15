<?php

error_reporting(0);

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

function unhtmlentities($string)
{
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
	if($add==true)  $retorno[] = $div->nodeValue;
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
    if($add==true)  $retorno[] = $node_html;
  }
  return $retorno;
}
function is_json($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}
include('craw.php');
include('../app/Helpers/helper.php');
$craw = new Craw();
$req = scrapestack_render_js2($_GET['page']);
$html = $req['html'];
if(is_json($html)){
$req = scrapestack_render_js2($_GET['page']);
$html = $req['html'];
}
$doc = new DOMDocument;
$doc->loadHTML($html);
//var_dump($doc);
if(is_json($html)){
  //echo $html;
  //exit();
}

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

$title = multiexplode(array('|',',','-'),pesquisar($html,'<title>','</title>',false));
$dados['success'] = true;
$dados['status'] = $req['status'];
$dados['nhtml'] = strlen($html);
$dados['canonical'] = stringify_sql(json_encode(tags($doc,'link',['href','canonical'])));
$dados['ntitle'] = count($title);
$dados['title'] = stringify_sql((($title)));
$dados['keywords'] = stringify_sql(($headers['keywords']));
$dados['description'] = stringify_sql(($headers['description']));
$dados['robots'] = stringify_sql(($headers['robots']));
$dados['nlinks'] = count(tags($doc,'a',['href']));
$dados['nimgs'] = count(tags($doc,'img',[]));
$dados['nimgsalt'] = count(tags($doc,'img',['alt']));
// $dados['img'] = tags2($doc,'img',['alt']);
$dados['njavascript'] = count(tags($doc,'script',[]));
$dados['ncss'] = count(tags($doc,'style',[]))+count(tags($doc,'link',[]));
$dados['headers'] = stringify_sql(($req['headers']));
$dados['h1'] = stringify_sql(json_encode((tags($doc,'h1',[]))));
$dados['h2'] = stringify_sql(json_encode((tags($doc,'h2',[]))));
$dados['h3'] = stringify_sql(json_encode((tags($doc,'h3',[]))));
$dados['h4'] = stringify_sql(json_encode((tags($doc,'h4',[]))));
$dados['h5'] = stringify_sql(json_encode((tags($doc,'h5',[]))));
$dados['h6'] = stringify_sql(json_encode((tags($doc,'h6',[]))));
$dados['nh1'] = count(tags($doc,'h1',[]));
$dados['nh2'] = count(tags($doc,'h2',[]));
$dados['nh3'] = count(tags($doc,'h3',[]));
$dados['nh4'] = count(tags($doc,'h4',[]));
$dados['nh5'] = count(tags($doc,'h5',[]));
$dados['nh6'] = count(tags($doc,'h6',[]));
//if($dados['ncss']<3||$dados['njavascript']<3){
//echo '{"success":false,"error":{"code":400,"type":"scrape_request_failed","info":"Your scraping request failed. Please try again or contact technical support."}}';
//exit();
//}
print_r($dados);
?>
