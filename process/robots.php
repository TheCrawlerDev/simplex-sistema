<?php

		include('../app/Model/Model.php');
		include('../app/Model/Crawler.php');
		include('../app/Helpers/helper.php');

		date_default_timezone_set('America/Sao_Paulo');
		$model_generic = new Model();
		$model = new CrawlerModel();
		$links = $model->robots();
		foreach($links as $link){
			$valid_date = $model->valid_date_robots($link['id'])[0]['max(datetime)'];
			if(is_null($valid_date)){
				try{
					$url = str_replace('//robots.txt', '/robots.txt', ($link['url'].'/robots.txt'));
					$response = crawlerPage($url,'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.89 Safari/537.36',12000,false);
					// if(intval($response['status'])<>200){
					// 	$response = json_decode(file_get_contents('http://142.93.189.150/cerebro/api/chrome.php?url='.$url),true);
					// }
					$robots = $response['content'];
					$robots_old = $model->robots_old($link['id'])[0]['rules'];
					$dados['datetime'] = stringify_sql(date('Y-m-d H:00:00'));
					$dados['url_id'] = $link['id'];
					$dados['url'] = stringify_sql(url_path($link['url']));
					$dados['status'] = stringify_sql($response['status']);
					$dados['rules'] = stringify_sql($robots);
					$dados['difference'] = ( $robots <> $robots_old ? 'True' : 'False' );
					$dados['unique_key'] = stringify_sql(date('YmdH').$link['id']);
					print_r($dados);
					$result = $model_generic->insert('robots_test',$dados);
					echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
				}catch(Exception $e){
					print_r($e);
				}
			}
		}

?>
