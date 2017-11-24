var lasthashsum = "";
var countrychart = [];
var mappindex = 0;
var manualselection = 0;
function processnext () {
	jQuery.get("?processnext", function (data) {
		data = JSON.parse(data);
		if (typeof data['countrychart'] !== 'undefined') {
			jQuery('#countrylist').html("<div>Loading...</div>");
			var pos = 1;
			newhtml = '';
			if (lasthashsum != data['hashsum']) {
				countrychart = data['countrychart']
				for (i in countrychart) {
					if (typeof countrycapitals[countrychart[i]['pindex']] !== 'undefined') {
						if ((manualselection > 0) && (manualselection == countrychart[i]['pindex'])) {
							mansel = 'mansel';
						} else {
							mansel = '';
						}
						newhtml += '<div class="row country" data-pindex="'+countrychart[i]['pindex']+'" data-countryname="'+countrycapitals[countrychart[i]['pindex']]['CountryName']+'" data-capitalname="'+countrycapitals[countrychart[i]['pindex']]['CapitalName']+'">';
						newhtml += '<div class="col-md-2 col-sm-2 col-xs-2 chart-position">'+pos+'</div>';
						newhtml += '<div class="col-md-4 col-sm-6 col-xs-10 country-capital '+mansel+'">';
						newhtml += '    <div class="block">';
						newhtml += '    <h2>'+countrycapitals[countrychart[i]['pindex']]['CapitalName']+'</h2>';
						newhtml += '    <p>'+countrycapitals[countrychart[i]['pindex']]['CountryName']+' / '+countrycapitals[countrychart[i]['pindex']]['ContinentName']+'</p>';
						newhtml += '    </div>';
						newhtml += '</div>';
						newhtml += '<div class="col-md-4 weather">'+countrychart[i]['temp']+'&deg; C <br>'+countrychart[i]['description']+'</div>';
						newhtml += '<div class="col-md-2 map">';
						newhtml += '      <a class="btn btn-primary flights" href="https://www.google.co.uk/search?q=Flights+to+'+countrycapitals[countrychart[i]['pindex']]['CapitalName']+','+countrycapitals[countrychart[i]['pindex']]['CountryName']+'">Find flights!</a>';
						newhtml += '     <div class="map-button"></div>';
						newhtml += '</div>';
						newhtml += '</div>';
					}
					
					pos++;
					if (pos > 10) {
						break;
					}
				}
			}
			jQuery('#countrylist').html(newhtml);
			if (manualselection == 0) {
				initMap();
			}
		}
		
	});
}
function drawMap (pindex) {
	mappindex = pindex;
	//console.log(countrycapitals[data['countrychart'][i]['pindex']]);
	if (countrycapitals[mappindex]['CapitalLatitude'] != "undefined") {
		
//		console.log(countrycapitals[mappindex])
		
		capLat = parseFloat(countrycapitals[mappindex]['CapitalLatitude']);
		capLng = parseFloat(countrycapitals[mappindex]['CapitalLongitude']);
		console.log(capLat+' - '+capLng);
		
		map = new google.maps.Map(document.getElementById('map-container'), {
			center: {lat: capLat , lng: capLng},
			zoom: 3
		});
	} else {
		alert("No LAT");
	}
}
function initMap() {
    // Create a map object and specify the DOM element for display.
	console.log(mappindex+' != '+countrychart[0]['pindex']);
	if ((typeof countrychart[0] !== 'undefined') && (mappindex != countrychart[0]['pindex'])) {
		drawMap(countrychart[0]['pindex']);
	}
	
}
var countrycapitals = {};
jQuery(document).ready(function() {
	jQuery.get("/json/country-capitals.json", function (data) {
		countrycapitals = data;
		processnext ();
		var worker = setInterval(function () {
			processnext(); 
		}, 3000);
	});
	jQuery('#countrylist').on('click', '.country-capital', function () {
		jQuery('.mansel').removeClass('mansel');
		jQuery(this).addClass('mansel');
		var pindex = jQuery(this).parents('.row').data('pindex');
		console.log(pindex);
		manualselection = pindex;
		drawMap(pindex);
	});
	
});