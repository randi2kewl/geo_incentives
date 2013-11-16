function codeAddress(addy, files) {
  jQuery.ajax({
    type: "POST",
    url: "http://maps.googleapis.com/maps/api/geocode/json?address="+encodeURIComponent(addy)+"&sensor=true",
    dataType: "json",
    success: function(result){
      var lat = result['results'][0]['geometry']['location']['lat'];
      var lng = result['results'][0]['geometry']['location']['lng'];

      var userLocation = new google.maps.LatLng(lat,lng);

      var mapOptions = {
          center: new google.maps.LatLng(userLocation),
          zoom: 5,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          disableDefaultUI: true,
        };

      //google maps api
      var map = new google.maps.Map(document.getElementById("map-canvas"),
            mapOptions);

      var userMarker = new google.maps.Marker({
          position: userLocation,
          map: map,
          title:"Your Location",
          draggable: true
      });

      // for(file in files) {
      //   var kmlLayer = new google.maps.KmlLayer(files[file]);
      //   kmlLayer.setMap(map);

      //   var myParser = new geoXML3.parser({afterParse: useTheData});
      //   myParser.parse(files[file]);

      //   function useTheData(doc) {
      //     // Geodata handling goes here, using JSON properties of the doc object
      //     for (var i = 0; i < doc[0].placemarks.length; i++) {
      //       var markerOut = google.maps.geometry.poly.containsLocation(userMarker.getPosition(), myParser.createPolygon(doc[0].placemarks[i], doc[0]));
            
      //       if(markerOut) {
      //         console.log(markerOut);
      //       }
      //     }
      //   };
      // }

    }
  });
}
