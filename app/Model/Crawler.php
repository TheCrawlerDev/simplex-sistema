<?php

class CrawlerModel extends Model{
	
	function __construct(){
		$this->model = new Model();
	}

	public function alexa(){
		return $this->model->query('select *,(SELECT max(date) FROM `alexa` al WHERE al.url_id = ml.id) as ult_date from monitoring_links ml where Alexa = 1 and active = 1 order by ult_date limit 2;');
	}

	public function valid_date_alexa($url_id){
		return $this->model->query("select max(date) as date from pagespeed where (url_id,device) = (".$url_id.",'desktop') and date >='".date('Y-m-d')."';")[0]['date'];
	}

	public function pagespeed(){
		return $this->model->query('select * from monitoring_links where pagespeed = 1 and active = 1 limit 5;');
	}

	public function valid_date_pagespeed($url_id){
		return count($this->model->query("select max(date) as date from alexa where (url_id) = (".$url_id.") and date >='".date('Y-m-d')."';"))[0]['date'];
	}

	public function lighthouse(){
		return $this->model->query('select * from monitoring_links mon where mon.lighthouse = 1 and mon.active = 1 limit 1;')[0];
	}

	public function valid_date_lighthouse($url_id){
		return count($this->model->query("select max(datetime) from lighthouse where (url_id) = (".$url_id.") and datetime >='".date('Y-m-d h:00:00')."';"));
	}

	public function robots(){
		return $this->model->query('select *,(SELECT max(datetime) FROM `robots` al WHERE al.url_id = mon.id) as ult_date from monitoring_links mon where mon.robots = 1 and mon.active = 1 order by ult_date limit 5;');
	}

	public function valid_date_robots($url_id){
		return $this->model->query("select max(datetime) from robots where url_id = ".$url_id." and datetime >='".date('Y-m-d h:00:00')."';");
	}

	public function robots_old($url_id){
		return $this->model->query("select rules,status from robots where url_id = ".$url_id." order by datetime desc;");
	}

	public function seo_crawler(){
		return $this->model->query('select * from monitoring_links mon where mon.robots = 1 and mon.active = 1 order by rand() limit 2;');
	}

	public function valid_date_seo_crawler($url_id){
		return count($this->model->query("select status from seo_monitoring where url_id = ".$url_id." and datetime >='".date('Y-m-d h:00:00')."';"));
	}

	public function seo_old($url_id){
		return count($this->model->query("select status from seo_monitoring where url_id = ".$url_id." order by url_id desc limit 1;"));
	}

	public function valid_date_observatory($url_id){
		return count($this->model->query("select id from observatory where url_id = ".$url_id." and datetime >='".date('Y-m-d h:00:00')."';"));
	}

	public function observatory(){
		return $this->model->query("select * from monitoring_links mon where mon.observatory = 1 and mon.active = 1 order by id limit 2;");
	}

}

?>