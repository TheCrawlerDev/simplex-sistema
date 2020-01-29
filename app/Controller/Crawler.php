<?php

class CrawlerController{
	
	// script @ok
	public function alexa($model,$request,$body){
		$model_generic = new Model();
		$links = $model->alexa();
		foreach($links as $link){
			$valid_date = $model->valid_date_alexa($link['id']);
			var_dump($valid_date);
			if(is_null($valid_date)){
				try{
					$api = json_decode(file_get_contents('http://hibots.com.br/simplex/alexa.php?page='.$link['url']),true);
					$dados['date'] = stringify_sql(date('Y-m-d H:00:00'));
					$dados['url_id'] = $link['id'];
					$dados['url'] = stringify_sql($link['url']);
					$dados['global_rank'] = end($api['rankData']['3mrank']);
					$dados['country_rank'] = str_replace(',', '', $api['country_rank']);
					$dados['bounce_rate'] = $api['bounce_rating'];
					$dados['page_p_visit'] = $api['visitorPercentage'][0]['pageviews_per_user'];
					$dados['time_on_site'] = $api['time_on_site'];
					$dados['search_visits'] = $api['visitorPercentage'][0]['visitors_percent'];
					$dados['how_fast'] = 0;
					print_r($dados);
					$result = $model_generic->insert('alexa',$dados);
					$url = $link['url'];
					echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
				}catch(Exception $e){
					print_r($e);
				}
			}			
		}
	}

	// script @ok
	public function lighthouse($model,$request,$body){
		$model_generic = new Model();
		$link = $model->lighthouse();
		$url = $link['url'];
		print_r($link);
		echo shell_exec("lighthouse $url --output json --output-path ./report.json");
		echo "Coleta dados Lighthouse para o site $url";
		$valid_date = $model->valid_date_lighthouse($link['id']);
		$api = json_decode(file_get_contents('http://simplexteste.kinghost.net/report.json'),true);
		// reformatar valor para as opções do audit_type
		foreach($api['audits'] as $audit){
			if(intval($audit['score']) > 0){
				$dados['datetime'] = stringify_sql(date('Y-m-d H:00:00'));
				$dados['url_id'] = $link['id'];
				$dados['url'] = stringify_sql($link['url']);
				$dados['audit_type'] = stringify_sql($audit['id']);
				$dados['audit'] = stringify_sql($audit['title']);
				$dados['value'] = intval($audit['score']);
				$result = $model_generic->insert('lighthouse',$dados);
				$url = $link['url'];
				echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
			}			
		}
	}

	// script @ok
	public function pagespeed($model,$request,$body){
		$model_generic = new Model();
		$links = $model->pagespeed();
		$agents = [
					'desktop'=>'Googlebot/2.1 (+http://www.google.com/bot.html)',
					'mobile'=>'Mozilla/5.0 (Linux; Android 7.0; SM-G930V Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36',
				];
		foreach($links as $link){
			$valid_date = $model->valid_date_pagespeed($link['id']);
			if(is_null($valid_date)){
				foreach(array_keys($agents) as $agent){
					$api = json_decode(crawlerPage('https://www.googleapis.com/pagespeedonline/v4/runPagespeed?locale=br&key=AIzaSyBMVp4oV3YwloLqW_K4sdm02rcDOJhE2Aw&url='.$link['url'],$agents[$agent]),true);
					$dados['date'] = stringify_sql(date('Y-m-d H:00:00'));
					$dados['url_id'] = $link['id'];
					$dados['url'] = stringify_sql($link['url']);
					$dados['device'] = $agent;
					$dados['AvoidLandingPageRedirects'] = $api['formattedResults']['ruleResults']['AvoidLandingPageRedirects']['ruleImpact'];
					$dados['EnableGzipCompression'] = $api['formattedResults']['ruleResults']['EnableGzipCompression']['ruleImpact'];
					$dados['LeverageBrowserCaching'] = $api['formattedResults']['ruleResults']['LeverageBrowserCaching']['ruleImpact'];
					$dados['MainResourceServerResponseTime'] = $api['formattedResults']['ruleResults']['MainResourceServerResponseTime']['ruleImpact'];
					$dados['MinifyCss'] = $api['formattedResults']['ruleResults']['MinifyCss']['ruleImpact'];
					$dados['MinifyHTML'] = $api['formattedResults']['ruleResults']['MinifyHTML']['ruleImpact'];
					$dados['MinifyJavaScript'] = $api['formattedResults']['ruleResults']['MinifyJavaScript']['ruleImpact'];
					$dados['MinimizeRenderBlockingResources'] = $api['formattedResults']['ruleResults']['MinimizeRenderBlockingResources']['ruleImpact'];
					$dados['OptimizeImages'] = $api['formattedResults']['ruleResults']['OptimizeImages']['ruleImpact'];
					$dados['PrioritizeVisibleContent'] = $api['formattedResults']['ruleResults']['PrioritizeVisibleContent']['ruleImpact'];
					$dados['FirstContentfulPaint'] = $api['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['median'];
					$dados['DOMContentLoadedEventFired'] = $api['loadingExperience']['metrics']['DOM_CONTENT_LOADED_EVENT_FIRED_MS']['median'];
					$dados['score'] = $api['ruleGroups']['SPEED']['score'];
					$result = $model_generic->insert('pagespeed',$dados);
					$url = $link['url'];
					echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
				}
			}			
		}
	}

	// script @ok
	public function observatory($model,$request,$body){
		$model_generic = new Model();
		$links = $model->observatory();
		foreach($links as $link){
			$valid_date = $model->valid_date_observatory($links[0]['id']);
			// if(is_null($valid_date)){
				$api = json_decode(file_get_contents('https://hibots.com.br/simplex/observatory.php?page='.$link['url']),true);
				// print_r($api);
				// if(isset($api['r5'])){
					$dados['datetime'] = stringify_sql(date('Y-m-d H:00:00'));
					$dados['url_id'] = $link['id'];
					$dados['url'] = stringify_sql($link['url']);
					$dados['score'] = intval($api['r1']['score']['score_modifier']);
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
					$dados['details'] = stringify_sql(json_encode($api['r5']));
					$result = $model_generic->insert('observatory',$dados);
					echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");	
				// }
			// }				
		}
	}

	// script @ok
	public function robots($model,$request,$body){
		$model_generic = new Model();
		$links = $model->robots();
		foreach($links as $link){
			$valid_date = $model->valid_date_robots($link['id'])[0]['max(datetime)'];
			if(is_null($valid_date)){
				try{
					$url = str_replace('//robots.txt', '/robots.txt', $link['url'].'/robots.txt');
					$response = crawlerPage($url);
					$robots = $response['content'];
					$robots_old = $model->robots_old($link['id'])[0]['rules'];
					$dados['datetime'] = stringify_sql(date('Y-m-d H:00:00'));
					$dados['url_id'] = $link['id'];
					$dados['url'] = stringify_sql($link['url']);
					$dados['status'] = stringify_sql($response['status']);
					$dados['rules'] = stringify_sql($robots);
					$dados['difference'] = ( $robots <> $robots_old ? 'True' : 'False' );
					print_r($dados);
					$result = $model_generic->insert('robots',$dados);
					echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
				}catch(Exception $e){
					$dados['datetime'] = stringify_sql(date('Y-m-d H:00:00'));
					$dados['url_id'] = $link['id'];
					$dados['url'] = stringify_sql($link['url']);
					$dados['status'] = '403';
					$result = $model_generic->insert('robots',$dados);
					print_r($e);
				}
			}				
		}
	}


	// validando
	public function seo_crawler($model,$request,$body){
		$model_generic = new Model();
		$links = $model->seo_crawler();
		$manager = connect_mongo();
		foreach($links as $link){
			$valid_date = $model->valid_date_seo_crawler($link['id']);
			if($valid_date<1){
				$traffic_types = ['Desktop Not Rendered','Desktop Javascript Rendered', 'Mobile Not Rendered','Mobile Javascript Rendered'];
				$agents = [
					'Desktop Not Rendered'=>'Googlebot/2.1 (+http://www.google.com/bot.html)',
					'Desktop Javascript Rendered'=>'Googlebot/2.1 (+http://www.google.com/bot.html)',
					'Mobile Not Rendered'=>'Mozilla/5.0 (Linux; Android 7.0; SM-G930V Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36',
					'Mobile Javascript Rendered'=>'Mozilla/5.0 (Linux; Android 7.0; SM-G930V Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36',
				];
					foreach($traffic_types as $traffic_type){
						// if(iniCache("seocrawler.htm")){
						// 	echo "arquivo criado";
						// }endCache(true);
						$url = $link['url'];
						$response = crawlerPage($link['url'],$agents[$traffic_type]);
						// unset($response['content']);
						// print_r($response);
						$html = $response['content'];
						if(strpos($traffic_type, 'Rendered')!==false){ $html = html_entity_decode($html);}
						$head = pesquisar($html,'<head>','</head>',false);
						$headers_f = explode('<meta',$head);
						$headers = array();
						foreach($headers_f as $h){
							$name = pesquisar($h,'name="','"',false);
							$content = pesquisar($h,'content="','"',false);
							if(strlen($name)>0){
								$headers[$name] = $content;
							}
						}
						$seo_old = $model->seo_old($link['id'])[0]['rules'];
						$dados['datetime'] = "'".date('Y-m-d H:00:00')."'";
						$dados['url_id'] = $link['id'];
						$dados['url'] = stringify_sql($link['url'],255);
						$dados['type'] = stringify_sql($traffic_type,255);
						// $dados['headers'] = stringify_sql(json_encode($headers),255);
						$dados['headers'] = $response['header'];
						$dados['html'] = stringify_sql(
								json_encode(
									insert_mongo($manager,'hibots01.html_reference',['font'=>'seo_crawler','html'=>$html,'traffic_type'=>$traffic_type,'url'=>$dados['url'],'url_id'=>$dados['url_id'],'created'=>date('Y-m-d h:m:s'),'updated'=>date('Y-m-d h:m:s')])
								)
								,255);
						$dados['nhtml'] = strlen($html);
						$dados['nh1'] = stable(substr_count($html,'h1'),$seo_old['nh1'],1.1);
						$dados['nh2'] = stable(substr_count($html,'h2'),$seo_old['nh2'],1.1);
						$dados['nh3'] = stable(substr_count($html,'h3'),$seo_old['nh3'],1.1);
						$dados['nh4'] = stable(substr_count($html,'h4'),$seo_old['nh4'],1.1);
						$dados['nh5'] = stable(substr_count($html,'h5'),$seo_old['nh5'],1.1);
						$dados['nh6'] = stable(substr_count($html,'h6'),$seo_old['nh6'],1.1);
						$dados['h1'] = stringify_sql(json_encode(get_tags($html,'h1',255)));
						$dados['h2'] = stringify_sql(json_encode(get_tags($html,'h2',255)));
						$dados['h3'] = stringify_sql(json_encode(get_tags($html,'h3',255)));
						$dados['h4'] = stringify_sql(json_encode(get_tags($html,'h4',255)));
						$dados['h5'] = stringify_sql(json_encode(get_tags($html,'h5',255)));
						$dados['h6'] = stringify_sql(json_encode(get_tags($html,'h6',255)));
						$title = multiexplode(array('|',',','-'),pesquisar($html,'<title>','</title>',false));
						$dados['ntitle'] = count($title);
						$dados['title'] = stringify_sql(json_encode($title),255);
						$dados['canonical'] = stringify_sql(pesquisar($html,'rel="canonical" href="','"',false),255);
						$dados['keywords'] = stringify_sql($headers['keywords'],255);
						$dados['description'] = stringify_sql($headers['description'],255);
						$dados['robots'] = stringify_sql($headers['robots'],255);
						$dados['nlinks'] = substr_count($html,'href');
						$dados['nimgs'] = substr_count($html,'img');
						$dados['nimgsalt'] = substr_count($html,'img');
						$dados['njavascript'] = substr_count($html,'script');
						$dados['ncss'] = substr_count($html,'<link')+substr_count($html,'<style');
						$changes = array_diff($dados,$seo_old);
						$dados['changes'] = stringify_sql(json_encode(array_keys($changes)),255);
						$dados['nchanges'] = count($changes);
						print_r($dados);
						$result = $model_generic->insert('seo_monitoring',$dados);
						echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
					}
			}				
		}
	}

}

?>