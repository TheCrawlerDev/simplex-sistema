<?php

		include('../app/Model/Model.php');
		include('../app/Model/Crawler.php');
		include('../app/Helpers/helper.php');

		date_default_timezone_set('America/Sao_Paulo');
		$model_generic = new Model();
		$model = new CrawlerModel();
		$datetime = stringify_sql(date('Y-m-d H:00:00'));
		$date_unique = date('YmdH');
		$links = $model->lighthouse();
		if($_GET['order']==1){
			$links = array_reverse($links);
		}elseif($_GET['order']==2){
			$links = array_semireverse($links);
		}elseif($_GET['order']==3){
			$links = $links;
		}else{
			//do nothing
		}
		foreach($links as $link){
			$valid_date = $model->valid_date_lighthouse($link['id']);
			if(is_null($valid_date)){
				$url = $link['url'];
				echo "Coleta dados Lighthouse para o site $url </br></br>";
				$api = json_decode(file_get_contents('http://142.93.189.150/cerebro/api/lighthouse.php?page='.$link['url']),true);
				$perf_audits = [       'first-contentful-paint',
               					       'first-meaningful-paint',
					               'speed-index',
					               'interactive',
					               'first-cpu-idle',
					               'estimated-input-latency',
					               'render-blocking-resources',
					               'uses-responsive-images',
					               'offscreen-images',
					               'unminified-css',
					               'unminified-javascript',
					               'unused-css-rules',
					               'uses-optimized-images',
					               'uses-webp-images',
					               'uses-text-compression',
					               'uses-rel-preconnect',
					               'time-to-first-byte',
					               'redirects',
					               'uses-rel-preload',
					               'efficient-animated-content',
					               'total-byte-weight',
					               'uses-long-cache-ttl',
					               'dom-size',
					               'critical-request-chains',
					               'user-timings',
					               'bootup-time',
					               'mainthread-work-breakdown',
					               'network-requests',
					               'screenshot-thumbnails',
					              'final-screenshot',
               			];
				$auds = array();
				foreach($api['lhrSlim'] as $audit){
					if(!in_array($audit['title'],$auds)){
						try{
							$dados['`datetime`'] = $datetime;
							$dados['`url_id`'] = $link['id'];
							$dados['`url`'] = stringify_sql($link['url']);
							$dados['`audit_type`'] = stringify_sql($audit['title']);
							$dados['`audit`'] = stringify_sql('score');
							$dados['`value`'] = doubleval($audit['score'])*100;
							$auds[] = $audit['title'];
							$dados['unique_key'] = stringify_sql($date_unique.$audit['title'].$audit['id'].$link['id']);
							//print_r($dados);
							$result = $model_generic->insert('lighthouse_temp',$dados);
							$url = $link['url'];
							if($result!=false){
								unset($dados['unique_key']);
								$result = $model_generic->insert('lighthouse',$dados);
							}
                            echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
						}catch(Exception $e){
							print_r($e);
						}
					}
				}
				foreach($api['lhr']['categories'] as $audit){
                                        foreach($audit['auditRefs'] as $aud){
                                                try{
							                                          $dados['`datetime`'] = $datetime;
                                                        $dados['`url_id`'] = $link['id'];
                                                        $dados['`url`'] = stringify_sql($link['url']);
                                                        $dados['`audit_type`'] = stringify_sql($audit['title']);
                                                        $dados['`audit`'] = stringify_sql($aud['id']);
                                                        $dados['`value`'] = $aud['weight'];
                                                        $auds[] = $audit['title'];
                                                        $dados['unique_key'] = stringify_sql($date_unique.$audit['title'].$aud['id'].$link['id']);
                                                        //print_r($dados);
                                                        $result = $model_generic->insert('lighthouse_temp',$dados);
														$url = $link['url'];
														if($result!=false){
															unset($dados['unique_key']);
															$result = $model_generic->insert('lighthouse',$dados);
														}
                                                        echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
                                                }catch(Exception $e){
                                                        print_r($e);
                                                }
					 }
                                }
				$audis = array();
				foreach($api['lhr']['audits'] as $audit){
					if(!in_array($audit['id'],$audis)){
						try{
							if(audit_type($audit['id'])<>null){
								$dados['`datetime`'] = $datetime;
								$dados['`url_id`'] = $link['id'];
								$dados['`url`'] = stringify_sql($link['url']);
								$dados['`audit_type`'] = stringify_sql(audit_type($audit['id']));
								$dados['`audit`'] = stringify_sql($audit['id']);
								$dados['`value`'] = search_audit($audit);
								$audis[] = $audit['id'];
								$dados['unique_key'] = stringify_sql($date_unique.audit_type($audit['id']).$audit['id'].$link['id']);
								$result = $model_generic->insert('lighthouse_temp',$dados);
								$url = $link['url'];
								if($result!=false){
									unset($dados['unique_key']);
									$result = $model_generic->insert('lighthouse',$dados);
								}
                echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
							}
						}catch(Exception $e){
							print_r($e);
						}
					}
				}
				$performance = array();
				$performance['`datetime`'] = $datetime;
				$performance['`url_id`'] = $link['id'];
				$performance['`url`'] = stringify_sql($link['url']);
				$performance['`score`'] = doubleval($api['lhrSlim'][0]['score'])*100;
				$performance_save = false;
				if(isset($api['lhrSlim'][0]['score'])){
					$performance_save = true;
				}
				unset($perf_audits[28]);
				unset($perf_audits[29]);
				$perf_audits[] = 'metrics';
				foreach($perf_audits as $perf_audit){
					$perf_audit_name = $perf_audit;
					$perf_audit = str_replace('-', '_', $perf_audit);
					if(isset($api['lhr']['audits'][$perf_audit_name]['numericValue'])){
						$performance['`'.$perf_audit.'`'] = $api['lhr']['audits'][$perf_audit_name]['numericValue'];
					}else if(in_array($perf_audit_name, ['critical-request-chains','resource-summary'])){
						$performance['`'.$perf_audit.'`'] = explode(' ', $api['lhr']['audits'][$perf_audit_name]['displayValue'])[0];
					}else if(in_array($perf_audit_name, ['user-timings', 'performance-budget', 'diagnostics'])){
						if($api['lhr']['audits'][$perf_audit_name]['score']==null){
							$performance['`'.$perf_audit.'`'] = 0;
						}else if($api['lhr']['audits'][$perf_audit_name]['score']==true){
							$performance['`'.$perf_audit.'`'] = 1;
						}
					}
					if(strlen($performance['`'.$perf_audit.'`'])==0){
							$performance['`'.$perf_audit.'`'] = 'null';
					}
				}
					$performance['first_input_delay_ms'] = $api['crux']['loadingExperience']['metrics']['FIRST_INPUT_DELAY_MS']['percentile'];
					$performance['cumulative_layout_shift_score'] = $api['crux']['loadingExperience']['metrics']['CUMULATIVE_LAYOUT_SHIFT_SCORE']['percentile'];
					$performance['largest_contentful_paint_ms'] = $api['crux']['loadingExperience']['metrics']['LARGEST_CONTENTFUL_PAINT_MS']['percentile'];
					$performance['unique_key'] = stringify_sql($date_unique.$link['id']);
					print_r($performance);
					$valid_date_performance = $model->valid_date_performance($link['id']);
					if(is_null($valid_date_performance) && $performance_save == true){
						$result = $model_generic->insert('performance',$performance);
						$url = $link['url'];
						echo ($result == false ? "Performance $url não atualizado!</br>" : "Performance $url atualizado!</br>");
					}

			}else{
				echo "</br>Dado ja coletado";
			}
		}

?>
