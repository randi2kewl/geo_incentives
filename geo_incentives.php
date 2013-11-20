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

/*
	Initialize  JS and Google Maps 

*/
function init_js_and_css_files() {

	wp_register_script( 'google', "https://maps.googleapis.com/maps/api/js?libraries=geometry,visualization&sensor=false&v=3&key=AIzaSyDVCGFuZZWzA2lgRQztNEJExpiRDCzaj0A" );
	wp_enqueue_script( 'google' );

	wp_register_script( 'jquery', "//code.jquery.com/jquery-1.10.2.min.js" );
	wp_enqueue_script( 'jquery' );

	wp_register_script( 'geo_incentives', plugins_url('geo_incentives.js', __FILE__) );
	wp_enqueue_script( 'geo_incentives' );
} 

add_action('init', 'init_js_and_css_files');


/*
	Starts the plugin 
*/
function start_geo_incentives() {
	$json = array();
	
	//if there are any request params then show the form
	if(isset($_REQUEST['address'])) {
		$files = array(plugins_url("kml/file1.kml", __FILE__));

		foreach($files as $file) {
			$xml = simplexml_load_file($file);
	        $childs = $xml->Document->Folder->children();

	        // Cycle through each of the polygon containers
	        foreach ($childs as $child)
	        {
	            $coords = $child->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates[0][0];
	            var_dump($coords);
	            break;
	            
	        }
		}

		$files = json_encode($files);
?>

	<div id='map-canvas' style="width: 800px; height: 800px;"></div>
	<script>addy=<?php echo "'{$_REQUEST['address']}'"; ?>; files=<?php echo $files; ?>;</script>

<?php

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