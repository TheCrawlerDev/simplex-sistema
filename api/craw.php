<?php
class Craw{

	private $returnPage;
	
	public function scrapestack($url){
		$queryString = http_build_query([
		  'access_key' => 'd3b1a84694cd9bcec0760ae769316abf',
		  'url' => $url,
		  // 'render_js' => 0,
		]);

		$ch = curl_init(sprintf('%s?%s', 'http://api.scrapestack.com/scrape', $queryString));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$website_content = curl_exec($ch);
		curl_close($ch);

		return $website_content;
	}

	public function carregar($url, $post = null, $access_token = null, $action = 'POST'){
        global $TEMPOCARREGAMENTO, $NUMEROCARREGAMENTO;

        $ini = microtime(true);
        $ch = curl_init();
        $head = array();
        curl_setopt($ch, CURLOPT_URL, $url);
        if (!is_null($post)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }
        if (!is_null($access_token)) {
            $head[] = "X-Shopify-Access-Token: $access_token";
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
        $TEMPOCARREGAMENTO = $TEMPOCARREGAMENTO + microtime(true) - $ini;
        $NUMEROCARREGAMENTO++;
        file_put_contents("url.txt", date('c') . " $url " . (@count($respA)) . " " . (@count(current($respA))) . "\r\n", FILE_APPEND);
        //file_put_contents('log.txt', date('c')."$url\t$resp\r\n",FILE_APPEND);
        return $respA;
    }

	public function sendJson($json,$url,$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36',$timeout = 12000){
		$dir = dirname(__FILE__);
		$cookie_file = $dir . '/cookies/' . md5($_SERVER['REMOTE_ADDR']) . '.txt';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($json))
		);
		echo "</br>Enviando Json</br>";
		echo "</br>Protocolos</br>";
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
		// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt($ch, CURLOPT_ENCODING, "" );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_AUTOREFERER, true );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout );
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout );
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10 );
		curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
		curl_setopt($ch, CURLOPT_REFERER, $url);
		// $retorno = curl_exec($ch);
		// $this->returnPage = $retorno;
		print_r(curl_getinfo($ch));
		echo "</br></br>";
		return curl_exec($ch);
	}	
	
	public static function crawlerPage($url,$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36',$timeout = 12000){
		// $useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36';
		// $timeout = 12000;
		$response = self::scrapestack($url);
		if(strlen($response)>50){
			return $response;
		}
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
		// $retorno = curl_exec($ch);
		// $this->returnPage = $retorno;
		return curl_exec($ch);
	}
	public static function sitemap($urls,$explode,$after,$before){
		$retorno = '';		
		foreach($urls as &$url){$retorno .= Craw::crawlerPage($url);}		
		$array = explode($explode,$retorno);
		$retorno = array();
		foreach($array as &$value){array_push($retorno,Craw::pesquisar($value, $after, $before));}
		return array_unique($retorno);
	}
	public static function pesquisarAfter($string, $after,$striptags=true){
		$subresult = '';
		if(strpos($string,$after) !== false) {
			$subresult = substr($string,strpos($string,$after)+strlen($after));		
		}
		$subresult = str_replace('&nbsp;','',$subresult);
		return $striptags===true ? strip_tags($subresult) : $subresult;
	}
	
	public static function pesquisar($string, $after, $before,$striptags=true){
		$subresult = '';
		if(strpos($string,$after) !== false) {
			$subresult = substr($string,strpos($string,$after)+strlen($after));
			$subresult = strchr($subresult,$before,true);		
		}
		$subresult = str_replace('&nbsp;','',$subresult);
		return $striptags===true ? strip_tags(trim($subresult)) : trim($subresult);
	}
	
	public static function pesquisarImagens($imagens,$after,$before){
		$arrayRetorno = array();
		$array = explode("<img",$imagens);
		foreach($array as &$value){
			if(trim($value)<>''){
				$value = self::pesquisar($value, $after, $before);
				array_push($arrayRetorno,$value);
			}
		}
		return $arrayRetorno;
	}
	public static function tirarAcentos($string){
    	return preg_replace(array("/(á|à|ã|â|ä)/","/(ç)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a c A e E i I o O u U n N"),$string);
	}
	public function formatarURL($replace,$url){
		foreach($replace as $r){
			$url = str_replace($r, '', $url);
		}
		// if(strpos($url, 'www')!==false){}
		// else{ $url = 'www.'.$url;}
		if(substr($url, -1, 1)=='/'){ return substr($url, 0, -1);}
		return $url;
	}
	public function url_path($url){
		return parse_url($url)['host'];
	}
}


?>