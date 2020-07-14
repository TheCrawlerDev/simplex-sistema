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
// $links = array($links[0]);
// print_r($links);
foreach($links as $link){
  $valid_date = $model->valid_date_seo_crawler($link['id']);
  if($valid_date<1){
      $traffic_types = ['Desktop Javascript Rendered'];
      foreach($traffic_types as $traffic_type){
        $url = $link['url'];
        $api = json_decode(file_get_contents('http://165.227.188.205/cerebro/api/seo_craw_api.php?api=seo&page='.$url),true);
        $seo_old = $model->seo_old($link['id'])[0];
        $dados = $api;
        $dados['datetime'] = $datetime;
        $dados['url_id'] = $link['id'];
        $dados['url'] = stringify_sql($link['url']);
        $dados['type'] = stringify_sql($traffic_type);
        $dados['unique_key'] = stringify_sql($date_unique.$link['id']);
        // if($dados['nchanges']>1){
        //   file_put_contents('changes/'.$link['id'].'.txt',json_encode($dados));
        // }
        var_dump($dados);
        echo "</br></br>";
        var_dump($api);
        echo "</br></br>";
        $result = $model_generic->insert('seo_monitoring_test',$dados);
        echo '</br></br>';
        // if($result !== false){
        //   unset($dados['unique_key']);
        //   $dados['nchanges'] = 0;
        //   unset($dados['changes']);
        //   // $result = $model_generic->insert('seo_monitoring',$dados);
        //   echo '</br></br>';
        // }elseif($result == false) {
        //   $seo_old['unique_key'] = stringify_sql($date_unique.$link['id']);
        //   unset($seo_old['changes']);
        //   unset($seo_old['nchanges']);
        //   $result = $model_generic->insert('seo_monitoring_test',$seo_old);
        //   echo '</br></br>';
        //   if($result !== false){
        //     unset($dados['unique_key']);
        //     $dados['nchanges'] = 0;
        //     unset($dados['changes']);
        //     // $result = $model_generic->insert('seo_monitoring',$dados);
        //     echo '</br></br>';
        //   }
        // }
        echo ($result == false ? "Link $url não atualizado!</br>" : "Link $url atualizado!</br>");
      }
  }
}

?>
