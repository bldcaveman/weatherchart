<?php
if (!defined('WEATHERCHART')) {
    die();
}
class WeatherChart {
    
    var $geodatajsonurl;
    var $geodatajsonloc;
    var $weathdatajsonloc;

    var $openweathermapapikey;
    
    var $geoData;
    var $weatherData;
    var $pindex;
    
    function __construct($openweathermapapikey='') {
        if (empty($openweathermapapikey)) {
            $this->die("Before we kick off - I need an API key.");
        }
        
        $this->geodatajsonurl = "http://techslides.com/demos/country-capitals.json";
        $this->geodatajsonloc = "json/country-capitals.json";
        $this->weathdatajsonloc = "json/weathdata.json";
        $this->openweathermapapikey = $openweathermapapikey;
            
        $this->checkForJson();
        $this->loadStoredWeatherData();
        $this->loadCityList();
        
    }
    
    private function checkForJson () {
        if (!file_exists($this->geodatajsonloc)) {
            $contents = file_get_contents($this->geodatajsonurl);
            $f = fopen($this->geodatajsonloc, "w") or $this->die("Unable to open city data file!");
            fwrite($f, $contents);
        }
        if (!file_exists($this->weathdatajsonloc)) {
            $f = fopen($this->weathdatajsonloc, "w") or $this->die("Unable to open weather data file!");
            fwrite($f, "");
        }
    }
    private function loadCityList () {
        $content = file_get_contents($this->geodatajsonloc);
        if (empty($content)) {
            $this->die("The city data file is empty!");
        }
        $data = json_decode($content);
        if (empty($content)) {
            $this->die("The city data could not be read!");
        }
        foreach ($data as $d) {
            $this->geoData[] = $d;
        }
    }
    private function loadStoredWeatherData () {
        $content = file_get_contents($this->weathdatajsonloc);
        /*
        if (empty($content)) {
            $this->die("The weather data file is empty!");
        }
        */
        $data = json_decode($content);
        if ((!empty($content)) && (empty($data))) {
            $this->die("The weather data could not be read!");
        }
        $this->weatherData = (isset($data->data)) ? (array) $data->data : array();
        $this->pindex = (isset($data->pindex)) ? $data->pindex : 0;
    }
    private function saveWeatherData () {
        $data = array(
            'last-update'=>time(),
            'pindex'=>$this->pindex,
            'data'=> $this->weatherData            
        );
        
        $f = fopen($this->weathdatajsonloc, "w") or $this->die("Unable to open weather data file!");
        fwrite($f, json_encode($data));
    }
    private function getCityWeather () {
        if ((isset($this->geoData[$this->pindex])) && (isset($this->geoData[$this->pindex]->CapitalName))) {
            $url = 'http://api.openweathermap.org/data/2.5/weather?q='.$this->geoData[$this->pindex]->CapitalName.'&APPID='. $this->openweathermapapikey;
            
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $url
            ]
                );
            $result = curl_exec($curl);
            $result = json_decode($result);
            
            $this->weatherData[$this->geoData[$this->pindex]->CapitalName] = array(
                'pindex'=>$this->pindex
            );
            
            if ((isset($result->cod)) && ($result->cod == '404')) {
                $this->weatherData[$this->geoData[$this->pindex]->CapitalName]['result'] = new stdClass();
                return false;
            } else {
                $this->weatherData[$this->geoData[$this->pindex]->CapitalName]['result'] = $result;
                return true;
            }
        } else {
            $this->jres(array('error'=>true,'msg'=>'could not find '.$CountryName));
        }
    }
    
    
    function die($die) {
        die('<p style="color: white; font-weight: bold; background-color: red;">'.$die.'</p>');
    }
    
    function jres ($data) {
        die(json_encode($data));
    }
    function process() {
        if (isset($this->geoData[$this->pindex])) {
            $data = $this->getCityWeather();
            $this->pindex = $this->pindex + 1;
            if ($this->pindex >= count($this->geoData)) {
                $this->pindex = 0;
            }
            $this->saveWeatherData();
            
            if (!isset($data->error)) {
                return true;  
            } else {
                return false;  
            }
        }
    }
        
    function getGeoData () {
        return($this->geoData);
    }
    
    function getCountryChart () {
        $refactored = array();
        $x = 1;
        foreach ($this->weatherData as $CapitalName => $c) {
            if ((!empty($c->result)) && (!empty($c->result->main))) {
                $t = $c->result->main->temp - 273.15;
                $description = $c->result->weather[0]->description;
            } else {
                continue;
            }
            
            
            if (isset($c->pindex)) {
                
                $k = $t + ($x / 1000);
                $refactored["$k"] = array(
                    'pindex'=>$c->pindex,
                    'temp'=>sprintf ("%.1f", $t),
                    'description'=>$description,
                    'CapitalName'=>$CapitalName
                ); 
            }
            $x++;
           
        }
        krsort($refactored);
        $topten = array();
        $x = 1;
        foreach ($refactored as $c) {
            $topten[] = $c;
            $x++;
            if ($x > 10) {
                break;
            }
        }
        
        return($topten);
    }
    
}