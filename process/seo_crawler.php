<?php

include('../app/Model/Model.php');
include('../app/Model/Crawler.php');
include('../app/Helpers/helper.php');

date_default_timezone_set('America/Sao_Paulo');
$model_generic = new Model();
$model = new CrawlerModel();
$datetime = stringify_sql(date('Y-m-d H:00:00'));
$date_unique = date('YmdH');
$links = $model->seo_crawler();
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
  $valid_date = $model->valid_date_seo_crawler($link['id']);
  if($valid_date<1){
      $traffic_types = ['Desktop Javascript Rendered'];
      $campos = ['headers','content','prev','next','canonical','amp','ntitle','title','nh1','nh2','nh3','nh4','nh5','nh6','description','keywords',
      'robots','nlinks','nimgs','nimgsalt','njavascript','ncss'];
      $campos_json = ['h1','h2','h3','h4','h5','h6'];
      foreach($traffic_types as $traffic_type){
        $url = $link['url'];
        $api = json_decode(file_get_contents('http://142.93.189.150/cerebro/api/seo_crawler.php?page='.$link['url']),true);
        foreach($campos as $campo){
          $dados[$campo] = stringify_sql(addslashes($api[$campo]));
        }
        foreach($campos_json as $campo){
          $dados[$campo] = stringify_sql(json_encode($api[$campo]));
        }
        $seo_old = $model->seo_old($link['id'])[0]['rules'];
        $dados['datetime'] = stringify_sql(intval($api['status']));
        $dados['datetime'] = $datetime;
        $dados['url_id'] = $link['id'];
        $dados['url'] = stringify_sql($link['url'],255);
        $dados['type'] = stringify_sql($traffic_type,255);
        $changes = array_diff($dados,$seo_old);
        $dados['changes'] = stringify_sql(json_encode(array_keys($changes)),255);
        $dados['nchanges'] = count($changes);
        $result = $model_generic->insert('seo_monitoring_test',$dados);
        echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
      }
  }
}

?>
