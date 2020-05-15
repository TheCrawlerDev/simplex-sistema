<?php

		include('../app/Model/Model.php');
		include('../app/Model/Crawler.php');
		include('../app/Helpers/helper.php');

		date_default_timezone_set('America/Sao_Paulo');
		$model_generic = new Model();
		$model = new CrawlerModel();
		$links = $model->observatory(500);
		foreach($links as $link){
			//print_r($link);
			$valid_date = $model->valid_date_observatory($link['id']);
			echo $link['url'];
			echo "</br></br>";
			// var_dump($valid_date);
			if(is_null($valid_date)){
				try{
					$api = json_decode(file_get_contents('http://167.172.246.220/cerebro/api/observatory.php?page='.$link['url']),true);
					//echo 'http://167.172.246.220/cerebro/api/observatory.php?page='.$link['url'];
					//var_dump($api);
						$dados['datetime'] = stringify_sql(date('Y-m-d H:00:00'));
						$dados['url_id'] = $link['id'];
						$dados['url'] = stringify_sql($link['url']);
						$dados['score'] = intval($api['r1']['score']);
						$dados['content_security_policy'] = intval($api['r5']['content-security-policy']['score_modifier']);
						$dados['contribute'] = intval($api['r5']['contribute']['score_modifier']);
						$dados['cookies'] = intval($api['r5']['cookies']['score-modifier']);
						$dados['cross_origin_resource_sharing'] = intval($api['r5']['cross-origin-resource-sharing']['score_modifier']);
						$dados['public_key_pinning'] = intval($api['r5']['public-key-pinning']['score_modifier']);
						$dados['redirection'] = intval($api['r5']['redirection']['score_modifier']);
						$dados['referrer_policy'] = intval($api['r5']['referrer-policy']['score_modifier']);
						$dados['strict_transport_security'] = intval($api['r5']['strict-transport-security']['score_modifier']);
						$dados['subresource_integrity'] = intval($api['r5']['subresource-integrity']['score_modifier']);
						$dados['x_content_type_options'] = intval($api['r5']['x-content-type-options']['score_modifier']);
						$dados['x_frame_options'] = intval($api['r5']['x_frame_options']['score_modifier']);
						$dados['x_xss_protection'] = intval($api['r5']['x_xss_protection']['score_modifier']);
						$dados['details'] = stringify_sql(addslashes(json_encode($api['r5'])));
						$dados['unique_key'] = stringify_sql(date('YmdH').$link['id']);
						//print_r($dados);
						$result = $model_generic->insert('observatory',$dados);
						echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
				}catch(Exception $e){
					print_r($e);
				}
			}else{
				echo "Já Coletado";
			}
			echo "</br></br>";
		}


?>
