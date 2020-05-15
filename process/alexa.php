<?php

		include('../app/Model/Model.php');
		include('../app/Model/Crawler.php');
		include('../app/Helpers/helper.php');

		date_default_timezone_set('America/Sao_Paulo');
		$model_generic = new Model();
		$model = new CrawlerModel();
		$links = $model->alexa();
		// $links = array($links[0]);
		// print_r($links);exit();
		foreach($links as $link){
			$valid_date = $model->valid_date_alexa($link['id']);
			var_dump($valid_date);
			if(is_null($valid_date)){
				$api = json_decode(file_get_contents('http://167.172.246.220/cerebro/api/alexa.php?page='.$link['url']),true);
				$i = 0;
				while( count($api['rankData'])==0 || intval($api['time_on_site'])==0 ) {
					sleep(4);
					$i++;
					if($i>30) break;
					$api = json_decode(file_get_contents('http://167.172.246.220/cerebro/api/alexa.php?page='.$link['url']),true);
				}
				try{
					$dados['date'] = stringify_sql(date('Y-m-d H:00:00'));
					$dados['url_id'] = $link['id'];
					$dados['url'] = stringify_sql(url_path($link['url']));
					$dados['global_rank'] = stringify_sql(end($api['rankData']['3mrank']));
					$dados['country_rank'] = stringify_sql(str_replace(',', '', $api['country_rank']));
					$dados['bounce_rate'] = stringify_sql($api['bounce_rating']);
					$dados['page_p_visit'] = stringify_sql($api['visitorPercentage'][0]['pageviews_per_user']);
					$dados['time_on_site'] = stringify_sql($api['time_on_site']);
					$dados['search_visits'] = stringify_sql($api['search_visits']);
					$dados['how_fast'] = 'null';
					//$sql = $model_generic->insert_r('alexa_test',$dados);
					//print_r(carregar('http://167.172.246.220/database2.php', ['query'=>$sql]));
					$dados['unique_key'] = stringify_sql(date('Ymd').$link['id']);
					print_r($dados);
					$result = $model_generic->insert('alexa',$dados);
					$url = $link['url'];
					echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
				}catch(Exception $e){
					print_r($e);
				}
			}
		}


?>
