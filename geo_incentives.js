var map, myParser, userMarker, check;

//when the document is ready, call this initialization function for the map
google.maps.event.addDomListener(window, 'load', initialize);

function initialize() {

  var mapOptions = {
    zoom: 10,
    center: new google.maps.LatLng(33.950488, -84.11590199999999)
  };

  //google maps api
  map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);

  codeAddress();

  if(!check) {
    jQuery('#geo_text').html(no_result);
  }
}

function codeAddress() {

  jQuery.ajax({
    type: "POST",
    url: "http://maps.googleapis.com/maps/api/geocode/json?address="+encodeURIComponent(addy)+"&sensor=true",
    dataType: "json",
    success: function(result){
      var lat = result['results'][0]['geometry']['location']['lat'];
      var lng = result['results'][0]['geometry']['location']['lng'];

      var userLocation = new google.maps.LatLng(lat,lng);

      userMarker = new google.maps.Marker({
          position: userLocation,
          map: map,
          draggable: true
      });

      for(file in files) {
        if(files[file]['url'].length > 0) {
          console.log(files[file]['url']);
          myParser = new geoXML3.parser({map: map, afterParse: test});
          myParser.parse(files[file]['url']);
        }
      }

    }
  });
}

function test(geoxml) {

  if(geoxml[0].bounds.contains(userMarker.position)) {
    jQuery('#geo_text').html('<a href="'+files[file]['link']+'">'+files[file]['text']+'</a>');
    check = true;
  }

}
