<?php

$indices = array();
$valores = array();

$sd_indices = array();
$sd_valores = array();

$sdm_indices = array();
$sdm_valores = array();

$final_date             = date('Y-m-d', strtotime('2018-05-17'));
$initial_date           = date('Y-m-d', strtotime('2018-05-03'));

$mfinal_date            = date('Y-m-d', strtotime('-30 days'));
$minitial_date          = date('Y-m-d', strtotime('-37 days'));

require_once "ge_data.php";
    
    $ga = new GA('ga:browser', 'ga:Users', $initial_date, $final_date );

    foreach ($ga->OutputDataBrowser() as $chart) {
        array_push($indices, "\"".$chart['Navegador']."\"");
        array_push($valores, $chart['Valor']);
    }

     $navegadores = implode(',', $indices);
     $qtd_navegadores = implode(',', $valores);

    //1 Parametro =  Dimensions, 2 Parametro = Metricas, 3 Parametro data inicial, 4 Parametro data final
    $ga2 = new GA('ga:userType',    'ga:Users', $initial_date, $final_date ); // Tipos de usuario


    $users_day          = new GA('ga:date',     'ga:Users', $initial_date, $final_date ); // Tipos de usuario

    $users_day_month    = new GA('ga:date',     'ga:Users', $minitial_date, $mfinal_date); 
    $users_page         = new GA('ga:referralPath',     'ga:Users', $minitial_date, $mfinal_date); 
    
    foreach ($users_day->OutputData() as $users_date) {
        array_push($sd_indices, "\"".date('d/m' , strtotime($users_date['index']))."\"");
        array_push($sd_valores, $users_date['value']);
    }
    $dates      = implode(',', $sd_indices); 
    $users      = implode(',', $sd_valores); 

    foreach ($users_day_month->OutputData() as $musers_date) {
        array_push($sdm_indices, "\"".date('d/m' , strtotime($musers_date['index']))."\"");
        array_push($sdm_valores, $musers_date['value']);
    }

    $mdates      = implode(',', $sdm_indices); 
    $musers      = implode(',', $sdm_valores);   
?>
