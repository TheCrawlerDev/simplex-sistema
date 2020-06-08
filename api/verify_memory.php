<?php

function verify_memory(){
    exec('ps -aux', $processes);
    $retorno = array();
    $cpuUsage = 0;
    $retorno['CPU_valid'] = true;
    $retorno['%CPU'] = 0;
    $retorno['%MEM'] = 0;
    foreach($processes as $process){
        $process = str_replace('  ',' ',$process);
        $cols = explode(' ',$process);
        $cols = array_filter($cols);
        $cols_n = array();
        foreach($cols as $col){
          $cols_n[] = $col;
        }
        $retorno['%CPU'] += floatval($cols_n[2]);
        $retorno['%MEM'] += floatval($cols_n[3]);
    }
    if($retorno['%CPU']>=70){
      $retorno['CPU_valid'] = false;
    }
    return $retorno;
}

while(verify_memory()['CPU_valid']==false){
  sleep(1);
}

?>
