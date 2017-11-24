<?php
define("WEATHERCHART", true);

require("conf.php");
require("class.php");

$WC = new WeatherChart($apikey);

if (!isset($_GET['processnext'])) {
    $citylist = $WC->getGeoData();
    include("view.php");
} else {
    $psuccess = $WC->process();
    if ($psuccess) {
        $chart = $WC->getCountryChart();
        $data = array(
            'hashsum'       => md5(serialize($chart)),
            'countrychart'  =>  $chart,
        );
        $WC->jres($data);
    } else {
        $WC->jres(['error'=>true, 'msg'=>'failed to process']);
    }
}
