<?php

error_reporting(0);

header('Content-Type: application/json');

include('craw.php');

$craw = new Craw();

// $_GET['page'] = $craw->formatarURL(['http://','https://'],$_GET['page']);

$_GET['page'] = $craw->url_path($_GET['page']);

$data = $craw->crawlerPage('https://www.alexa.com/siteinfo/'.$_GET['page']);

try{
	$json['topKeywordsJSON'] = json_decode($craw->pesquisar($data, '<script type="application/json" id="topKeywordsJSON">', '</script>'),true);

	$json['competitorsJSON'] = json_decode($craw->pesquisar($data, '<script type="application/json" id="competitorsJSON">', '</script>'),true);

	$json['visitorPercentage'] = json_decode($craw->pesquisar($data, '<script type="application/json" id="visitorPercentage">', '</script>'),true);

	$json['rankData'] = json_decode($craw->pesquisar($data, '<script type="application/json" id="rankData">', '</script>'),true);

	$country_rank_html = $craw->pesquisar($data, '<section class="countryrank">', '</section>',false);

	if(strlen($country_rank_html)<1){
                $json['country_rank'] = null;
        }else{
                $json['country_rank'] = $craw->pesquisar($country_rank_html, '<li data-value="', '"');
        }

	//$json['country_rank'] = $craw->pesquisar($country_rank_html, '<li data-value="', '"');

	$bounce_rating_html = $craw->pesquisar($data, '<h3>Bounce rate</h3>', '</section>',false);

	$bounce_rating = $craw->pesquisar($bounce_rating_html, '<span class="num purple">', '%</span>');

	$bounce_rating_html2 = $craw->pesquisar($data,'<div id="CompoundTooltips_timeonsite" class="ALightbox" style="font-size:13px; display: none;">',
	'<h3>Bounce rate</h3>',false);

        $bounce_rating2 = $craw->pesquisar($bounce_rating_html2, '<p class="small data">', '%');

	if(strlen($bounce_rating)>1){
                $json['bounce_rating'] = $bounce_rating;
        }elseif(strlen($bounce_rating2>1)){
		$json['bounce_rating'] = $bounce_rating2;
	}else{
                $json['bounce_rating'] = null;
        }

	//$json['bounce_rating'] = $craw->pesquisar($bounce_rating_html, '<span class="num purple">', '%</span>');

	$page_views_html = $craw->pesquisar($data, '<h3>Engagement</h3>', '</section>',false);

	$page_views = $craw->pesquisar($page_views_html, '<p class="small data">', '</span>');

	if(strlen($page_views)<1){
		$json['page_views'] = null;
	}else{
		$json['page_views'] = doubleval($craw->pesquisar($page_views_html, '<p class="small data">', '</span>'));
	}
	
	//$json['page_views'] = doubleval($craw->pesquisar($page_views_html, '<p class="small data">', '</span>'));

	$time_on_site = $craw->pesquisar($data, '<div class="rankmini-daily" style="flex-basis:40%;">', '</div>');

	$time_on_site2_html = $craw->pesquisar($data, '<p class="title">Daily Pageviews per Visitor</p>', '</span>', false);

	$time_on_site2 = $craw->pesquisar($time_on_site2_html, '<p class="small data">', '</p>');

	if(strlen($time_on_site)>1){
		$times_on_site = explode(':',$time_on_site);
                $json['time_on_site'] = ($times_on_site[0]*60)+$times_on_site[1];
        }elseif(strlen($time_on_site2)>1){
		$times_on_site = explode(':',$time_on_site2);
                $json['time_on_site'] = ($times_on_site[0]*60)+$times_on_site[1];
        }else{
                $json['time_on_site'] = null;
        }

	//$json['time_on_site'] = ($time_on_site[0]*60)+$time_on_site[1];

	$search_visits_html = $craw->pesquisar($data, '<p>Percentage of visits to the site that consist of a single pageview.</p>', '</section>',false);

	$search_visits = $craw->pesquisar($search_visits_html, '<span class="num purple">', '%</span>');
	
	if(strlen($search_visits)<1 || intval($search_visits)==0 ){
                $json['search_visits'] = null;
        }else{
                $json['search_visits'] = doubleval($search_visits);
        }

	//$json['search_visits'] = doubleval($craw->pesquisar($search_visits_html, '<span class="num purple">', '%</span>'));

	$comparison_metrics_html = $craw->pesquisar($data, '<p><strong>Comparison Metrics</strong></p>', '</section>',false);

	if(strlen($comparison_metrics_html)<1){
                $json['comparison_metrics_html'] = null;
        }else{
                $json['comparison_metrics_html'] = doubleval($craw->pesquisar($comparison_metrics_html, '<span class="num purple">', '%</span>'));
        }

	//$json['comparison_metrics'] = doubleval($craw->pesquisar($comparison_metrics_html, '<span class="num purple">', '%</span>'));

	$json['success'] = true;
}catch(Exception $e){
	$json['success'] = false;
	$json['error'] = $e;
}

echo json_encode($json);

?>
