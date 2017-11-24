<?php
define("WEATHERCHART", true);
require("./class.php");

final class ChartTest extends PHPUnit_Framework_TestCase
{
    private $WC;
    
    protected function setUp() {
        require ("./conf.php");
        $this->WC = new WeatherChart($apikey);
    }
    protected function tearDown()
    {
        $this->WC = NULL;
    }
    public function testChartList () {
        $result = $this->WC->getCountryChart();
        $this->assertInternalType('array', $result);
    }
    public function testGeoData () {
        $result = $this->WC->getGeoData();
        $this->assertInternalType('array', $result);
    }
}