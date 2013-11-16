<?php
/**
 * Plugin Name: Invest Atlanta Geo-incentives
 * Plugin URI: http://github.com/randi2kewl/geo_incentives
 * Description: Allows for Invest Atlanta to add geo-incentive info.
 * Version: 0.1
 * Author: Randi Miller <randi[at]devjunkies[dot]net
 * Author URI: http://devjunkies.net
 * License: Public Domain
 */

/*  Copyright 2013  Randi Miller  (email : Randi[at]devjunkies[dot]net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

 include('admin_geo_incentives.php');

define('MAPS_API_KEY', 'AIzaSyDVCGFuZZWzA2lgRQztNEJExpiRDCzaj0A');

//http://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&sensor=true_or_false
function init_js_and_css_files() {

	wp_register_script( 'google', "https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=true" );
	wp_enqueue_script( 'google' );

	wp_register_script( 'geoxml', plugins_url('geoxml.js', __FILE__) );
	wp_enqueue_script( 'geoxml' );


	wp_register_script( 'geo_incentives', plugins_url('geo_incentives.js', __FILE__) );
	wp_enqueue_script( 'geo_incentives' );


}
add_action('init', 'init_js_and_css_files');


//[geo_incentives]
function start_geo_incentives() {
	$json = array();
	
	//if there are any request params then show the form
	if(isset($_REQUEST['address'])) {
		$files = array(plugins_url("kml/file1.kml", __FILE__));

		foreach($files as $file) {
			$xml = simplexml_load_file($file);
	        $childs = $xml->Document->Folder->children();
	        foreach ($childs as $child)
	        {
	            $coords = $child->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates[0][0];
	            break;
	            
	        }
		}

		$files = json_encode($files);



?>

	<div id='map-canvas' style="display:none;"></div>
	<script>codeAddress(<?php echo "'{$_REQUEST['address']}'"; ?>, <?php echo $files; ?>);</script>

<?php


		//foreach loop through all of the files
			//parse the kml to get the coordinates

			//create the polygon



			//find out if the marker is in the polygon
 			//var markerOut = google.maps.geometry.poly.containsLocation(userMarker.getPosition(), polySector3PillburyPleasant);

			// console.log(markerOut);

			//if inside then stop looking else continue

				//display found result text and link

		//if still not in an area then show not found text and link





	}
	
	//create form to get address
	$current_page_url = get_permalink();
	echo "<form action='{$current_page_url}' method='POST'>"
			."<div><input name='address' type='' placeholder='Address'/><br><br>"
			."<input type='submit' value='submit'/></div>"
		."</form><div id='result_text'></div>";

	if(isset($_REQUEST['address'])) {
		if(strstr($_REQUEST['address'], 'atlanta') || strstr($_REQUEST['address'], 'buckhead')) {
			echo "<br><br><p style=''>Currently there are tax credits available in your.</p>";
			echo "<a href='http://www.investatlanta.com'>More information</a>";
		} else {
			echo "<br><br><p style=''>Currently no tax credits for your area.</p>";
			echo "<a href='http://www.investatlanta.com'>Contact Invest Atlanta</a>";
		}
	}


}

add_shortcode( 'geo_incentives', 'start_geo_incentives' );