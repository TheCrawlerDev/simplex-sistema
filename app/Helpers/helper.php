<?php
	
	function alexa($url){
		$data = curl_proxy('https://www.alexa.com/siteinfo/'.$url);
		try{
			$json['topKeywordsJSON'] = json_decode(pesquisar($data, '<script type="application/json" id="topKeywordsJSON">', '</script>'),true);

			$json['competitorsJSON'] = json_decode(pesquisar($data, '<script type="application/json" id="competitorsJSON">', '</script>'),true);

			$json['visitorPercentage'] = json_decode(pesquisar($data, '<script type="application/json" id="visitorPercentage">', '</script>'),true);

			$json['rankData'] = (array) json_decode(pesquisar($data, '<script type="application/json" id="rankData">', '</script>'),true);

			$country_rank_html = pesquisar($data, '<section class="countryrank">', '</section>',false);

			$json['country_rank'] = pesquisar($country_rank_html, '<li data-value="', '"');

			$bounce_rating_html = pesquisar($data, '<h3>Bounce rate</h3>', '</section>',false);

			$json['bounce_rating'] = pesquisar($bounce_rating_html, '<span class="num purple">', '%</span>');

			$page_views_html = pesquisar($data, '<h3>Engagement</h3>', '</section>',false);

			$json['page_views'] = doubleval(pesquisar($page_views_html, '<p class="small data">', '</span>'));

			$time_on_site = explode(':',pesquisar($data, '<div class="rankmini-daily" style="flex-basis:40%;">', '</div>'));

			$json['time_on_site'] = ($time_on_site[0]*60)+$time_on_site[1];

			$search_visits_html = pesquisar($data, '<p>Percentage of visits to the site that consist of a single pageview.</p>', '</section>',false);

			$json['search_visits'] = doubleval(pesquisar($search_visits_html, '<span class="num purple">', '%</span>'));
			$json['success'] = true;
		}catch(Exception $e){
			$json['success'] = false;
			$json['error'] = $e;
		}

		return $json;
	}
	

	function curl_proxy($url){
		$username = 'lum-customer-hl_96689a01-zone-static-route_err-block';
		$password = 'cztfqszvvoik';
		$port = 22225;
		$user_agent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';
		$session = mt_rand();
		$super_proxy = 'zproxy.lum-superproxy.io';
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_PROXY, "http://$super_proxy:$port");
		curl_setopt($curl, CURLOPT_PROXYUSERPWD, "$username-country-us-dns-remote-session-$session:$password");
		$result = curl_exec($curl);
		curl_close($curl);
		if ($result){
		    return $result;
		}else{
			return null;
		}
	}

	function carregar($url, $post = null, $access_token = null, $action = 'POST'){
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

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
        curl_close($ch);
        //file_put_contents('log.txt', date('c')."$url\t$resp\r\n",FILE_APPEND);
        return ['content'=>$respA,'header_size'=>$header_size,'header'=>$header,'body'=>$body];
    }

	function sendJson($json,$url,$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36',$timeout = 12000){
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
	
	function crawlerPage($url,$useragent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36',$timeout = 12000){
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
		// $retorno = curl_exec($ch);
		// $this->returnPage = $retorno;
		$response = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		$info = curl_getinfo($ch);
		curl_close($ch);
        return ['content'=>$response,'header_size'=>$header_size,'header'=>$header,'body'=>$body,'content_type'=>$content_type,'info'=>$info];
	}
	function sitemap($urls,$explode,$after,$before){
		$retorno = '';		
		foreach($urls as &$url){$retorno .= Craw::crawlerPage($url);}		
		$array = explode($explode,$retorno);
		$retorno = array();
		foreach($array as &$value){array_push($retorno,Craw::pesquisar($value, $after, $before));}
		return array_unique($retorno);
	}
	function pesquisarAfter($string, $after,$striptags=true){
		$subresult = '';
		if(strpos($string,$after) !== false) {
			$subresult = substr($string,strpos($string,$after)+strlen($after));		
		}
		$subresult = str_replace('&nbsp;','',$subresult);
		return $striptags===true ? strip_tags($subresult) : $subresult;
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
	
	function pesquisarImagens($imagens,$after,$before){
		$arrayRetorno = array();
		$array = explode("<img",$imagens);
		foreach($array as &$value){
			if(trim($value)<>''){
				$value = pesquisar($value, $after, $before);
				array_push($arrayRetorno,$value);
			}
		}
		return $arrayRetorno;
	}
	function tirarAcentos($string){
    	return preg_replace(array("/(á|à|ã|â|ä)/","/(ç)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a c A e E i I o O u U n N"),$string);
	}

	function convertData($timestamp) {
	    date_default_timezone_set('America/Sao_Paulo');
	    return date('d/m/Y H:i', $timestamp); // Resultado: 12/03/2009
	}

	function convertDataN($timestamp) {
	    date_default_timezone_set('America/Sao_Paulo');
	    return date('d/m/Y', $timestamp); // Resultado: 12/03/2009
	}

	function random_str($size) {
	    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
	    $string = '';
	    $max = 35; //strlen($characters) - 1;
	    for ($i = 0; $i < $size; $i++) {
	        $string .= $characters[mt_rand(0, $max)];
	    }
	    return $string;
	}

	function codifica($texto) {
	    $Enc = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, CHAVE_ENCRIPT, $texto, MCRYPT_MODE_CBC, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
	    $Enc = rtrim(base64_encode($Enc), '=');
	    return str_replace(array('+', '/'), array('-', '_'), $Enc);
	}

	function decodifica($textoEnc) {
	    $textoEnc = str_replace(array('-', '_'), array('+', '/'), $textoEnc);
	    $textoEnc = base64_decode($textoEnc);
	    return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, CHAVE_ENCRIPT, $textoEnc, MCRYPT_MODE_CBC, "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"), "\0");
	}

	function dataHumanToUnix($data, $fim = true) {
	    $data = str_replace(array("\\", ".", ",", " "), "/", $data);
	    $b = explode("/", $data);
	    if (DATE_DMY == 'd/m/Y') {
	        $im = 1;
	        $id = 0;
	    } else {
	        $im = 0;
	        $id = 1;
	    }
	    if (is_numeric($b[0]) && is_numeric($b[1]) && is_numeric($b[2]) && $b[2] > 1900 && checkdate($b[$im], $b[$id], $b[2]))
	        return strtotime($b[2] . "-" . $b[$im] . "-" . $b[$id] . ' ' . ($fim ? "23:59:59" : '00:00:00'));
	    else
	        return "";
	}

	function iniCache($file, $tempo = 86400) {
	    global $CACHE;
	    $CACHE = array(false, $file);
	    if (!file_exists($file) || ($tempo > 0 && filemtime($file) < time() - $tempo)) {
	        ob_start();
	        $CACHE[0] = true;
	    }
	    return $CACHE[0];
	}

	function endCache($exec = false) {
	    global $CACHE;
		if ($CACHE[0]) {
	        $R = fopen($CACHE[1], "w");
	        $T = ob_get_contents();
	        ob_end_clean();
	        fwrite($R, $T);
	        fclose($R);
	        if ($exec)
	            include($CACHE[1]);
	        else
	            echo $T;
	    }else {
	        include($CACHE[1]);
	    }
	}


	function connect_mongo(){
		$conn = array(
			'host'=>'mongodb://hibots01:supermenu@mongodb.hibots.com.br:27017/hibots01',
            'username'=>'hibots01',
			'password'=>'supermenu'
		);
		$manager = new MongoDB\Driver\Manager($conn['host']);
		return $manager;
	}

	function get_mongo($manager,$database,$filter = [],$options = [],$returnArray = false){
		$query = new MongoDB\Driver\Query($filter, $options);
		$rows = $manager->executeQuery($database, $query);
		if($returnArray === true){
			$retorno = array();
			foreach($rows as $r){
			   array_push($retorno, (array) $r);
			}
			$arrayObject = new ArrayObject($rows);
			$rows = $arrayObject->getArrayCopy();
			return $retorno;
		}
		return $rows;
	}

	function insert_mongo($manager,$database,$doc){
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->insert($doc);
		return $manager->executeBulkWrite($database, $bulk);
	}

	function update_mongo($manager,$database,$doc,$id){
		$bulk = new MongoDB\Driver\BulkWrite;
		$bulk->update(
		    ['_id' => $id],
		    ['$set' => $doc],
		    ['multi' => false, 'upsert' => false]
		);
		return ['op'=>$manager->executeBulkWrite($database, $bulk)];
	}
	
    function ObjectId($id){
		return new MongoDB\BSON\ObjectId($id);
	}

	function generateSubId_mongo($id){
		return hexdec($id)/(7000000000*70000000);
	}

	function get_tags($string, $tagname,$max){
	    $d = new DOMDocument();
	    @$d->loadHTML($string);
	    $return = array();
	    foreach($d->getElementsByTagName($tagname) as $item){
	    	if(strlen($item->textContent)>$max) $item->textContent = substr($item->textContent,0,$max);
	        $return[] = $item->textContent;
	    }
	    return $return;
	}
	function multiexplode($delimiters,$string) {

	    $ready = str_replace($delimiters, $delimiters[0], $string);
	    $launch = explode($delimiters[0], $ready);
	    return  $launch;
	}
	function stringify_sql($string){
		$str = str_replace(['[',']'], '', $string);
		if(strlen($str)>0)	return "'".$string."'";
		else return "'".null."'";
	}

	function stable($va1,$val2,$percent){
		if(($val1)>($val2*$percent)) return $val1;
		return $val2;
	}
	function renderHTML(){
		$dom = new DOMDocument();
		$dom->validateOnParse = true;
		@$dom->loadHTML($file_url);
		return $dom->getElementById("txt")->nodeValue;
	}
	// function renderHtml(function()
 //    {
 //        renderHead(function()
 //        {
 //            renderTitle('Welcome to my new site!');
 //        });
 //        renderBody(function()
 //        {
 //            renderH1('Under Construction');
 //            renderImg('http://www.mysite.com/images/logo.png', 'My Site Ltd.');
 //            renderP(function()
 //            {
 //                echo htmlspecialchars('This site is still being constructed...');
 //                renderBr();
 //                renderA('http://www.mysite.com/', '', function()
 //                {
 //                    echo htmlspecialchars('> Visit My Site's official website!');
 //                });
 //                renderA('http://www.some-other-site.com/', '_blank', function()
 //                {
 //                    echo htmlspecialchars('> Get more information here.');
 //                });
 //            });

 //            // load and render some text dynamically
 //            $textItems = loadTextItems();
 //            foreach ($textItems as $item)
 //            {
 //                renderP(function() use ($item)
 //                {
 //                    echo htmlspecialchars($item);
 //                });
 //            });
 //        });
 //    });
	// }
?>