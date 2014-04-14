var mapLoader = $('<img>').addClass('loader').attr('src', '{{ThemePath}}/img/loader.gif').attr('alt', 'Chargement...');
var mapLoaded = false;
function initMap(mapId) {
	// -- Prepare the Map Center Position (same as marker position)
	// The location of our marker and popup. We usually think in geographic
	// coordinates ('EPSG:4326'), but the map is projected ('EPSG:3857').
	var myLocation = new OpenLayers.Geometry.Point(2.493628, 48.790005)
	.transform('EPSG:4326', 'EPSG:3857');
	
	//	-- Prepare the marker
	var marker = new OpenLayers.Layer.Vector('Overlay', {
		styleMap: new OpenLayers.StyleMap({
			externalGraphic: '{{ThemePath}}/img/marker.png',
			graphicWidth: 20, graphicHeight: 24, graphicYOffset: -24
		})
	});
	marker.addFeatures([new OpenLayers.Feature.Vector(myLocation)]);

	// -- Create the map
	map = new OpenLayers.Map({
		div: mapId, projection: "EPSG:3857",
		layers: [new OpenLayers.Layer.OSM(), marker],
		center: myLocation.getBounds().getCenterLonLat(), zoom: 17
	});
}

$(document).ready(function(){
	$('#mapHandler').show();
	$('#mapHandler').click(function() {
		// - Display the map
		if ($('#mapWrapper').is(':hidden')) {
			$(this).html('Cacher le plan interactif');
			$('#mapWrapper').slideDown('slow');
			// Load the map
			if (!mapLoaded) {
				mapLoader.insertBefore('#map');
				$.getScript('{{ThemePath}}/js/openlayers.js', function() {
					initMap('map');
					mapLoader.hide();
				}, function() {
					mapLoader.hide();
				});
			}
			mapLoaded = true;
		}
		// - Hide the map
		else {
			$('#mapWrapper').slideUp('slow');
			$(this).html('Afficher le plan interactif');
		}
	});
});