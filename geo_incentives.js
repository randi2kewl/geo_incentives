var map;

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

      var userMarker = new google.maps.Marker({
          position: userLocation,
          map: map,
          title:"Your Location",
          draggable: true
      });

      for(file in files) {
        var kmlLayer = new google.maps.KmlLayer("https://dl.dropboxusercontent.com/s/x7k7ckm51e244zj/file1.kml?dl=1&token_hash=AAEFsp1590_nM0BwWpwzJ2zTq78u9O7Iw3JjMYuLyKliNA");
        kmlLayer.setMap(map);

        var markerOut = google.maps.geometry.poly.containsLocation(userMarker.getPosition(), kmlLayer);
        //console.log(markerOut);
      }

    }
  });
}
