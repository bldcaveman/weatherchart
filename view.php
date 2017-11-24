<?php 
if (!defined('WEATHERCHART')) {
    die();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php $nameofapp ?></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Shrikhand" rel="stylesheet">
        <link rel="stylesheet" href="assets/css.css">
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
        <script src="assets/js.js"></script>
    </head>
    <body>
    	<div class="container">
    		<div class="row">
    			<div class="col-md-12">
    				<h1><?php echo $nameofapp ?></h1>
    				<p>Escape the winter apocolypse to literally the hottest place in the world right now!</p>
    			</div>
    			<div class="col-md-12" id="map-container">
    			</div>
    		</div>
    	</div>
    	<div class="container" id="countrylist">
    	
   		    <?php 
    		    $x = 1;
    		    foreach ($citylist as $cc) {
    		       echo '
            <div class="row country"  data-countryname="'.$cc->CountryName.'" data-capitalname="'.$cc->CapitalName.'">
                <div class="col-md-2 col-sm-2 col-xs-2 chart-position">
                    
                </div>
                <div class="col-md-4  col-sm-6 col-xs-10 country-capital">
                    <div class="block">
                    <h2>'.$cc->CapitalName.'</h2>
                    <p>'.$cc->CountryName.' / '.$cc->ContinentName.'</p>
                    </div>
                </div>
                <div class="col-md-4 weather">
                   
                </div>
                <div class="col-md-2 map">
                    <a target="_blank" href="https://www.google.co.uk/search?q=Flights+to+'.$cc->CapitalName.','.$cc->CountryName.'">Find flights!</a>
                    <div class="map-button"></div>                    
                </div>
            </div>';        
    		        $x++;
    		        if ($x > 10) {
    		            break;
    		        }
    		    }
    		    ?>
		</div>
		<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $gmapskey ?>&callback=initMap"
    async defer></script>
    </body>
</html>