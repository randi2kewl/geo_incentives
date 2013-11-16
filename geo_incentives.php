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

function load_google_maps_api() {
	echo "<script type='text/javascript'" 
			."src='https://maps.googleapis.com/maps/api/js?key=".MAPS_API_KEY."&sensor=FALSE'>"
		."</script>";
}

add_action( 'muplugins_loaded', 'load_google_maps_api' );

//[geo_incentives]
function start_geo_incentives() {
	
	//if there are any request params then show the form
	if(isset($_REQUEST['address'])) {
		//get lat and lon for address 
		//var userLocation = new google.maps.LatLng(44.928633,-93.298919);

		// var userMarker = new google.maps.Marker({
  //           position: userLocation,
  //           map: map,
  //           title:"Your Location",
  //           draggable: true
  //       });


		//foreach loop through all of the files
			//parse the kml to get the coordinates

			//create the polygon



			//find out if the marker is in the polygon
 			//var markerOut = google.maps.geometry.poly.containsLocation(userMarker.getPosition(), polySector3PillburyPleasant);

			// console.log(markerOut);

			//if inside then stop looking else continue

				//display found result text and link

		//if still not in an area then show not found text and link





	} else {
		//create form to get address
		$current_page_url = get_permalink();
		echo "<form action='{$current_page_url}' method='POST'>"
				."<div><input name='address' type='' placeholder='Address'/><br><br>"
				."<input type='submit' value='submit'/></div>"
			."</form>";
	}

}

add_shortcode( 'geo_incentives', 'start_geo_incentives' );