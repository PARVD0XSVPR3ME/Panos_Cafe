<?php 
include_once 'header.php';

if (!isset($_SESSION['u_email'])) {
  header("Location: index.php");
  exit();
}
?>

<br>
<div class="container" id="adresspage_container">  
  <div class="address_bg">
    <div class="maps_title">
	  	<div class="row">
	  		<div class="col">
	  			<h3 class="text-light ">Εισαγωγή Διεύθυνσης</h3>
	  		</div>
	  	</div>
	  </div>
    <form action="javascript:void(0);" name="search-form">
      <div id="custom-search-input">
        <div class="input-group col-md-12" id="search_bar">
          <input class="form-control " type="text" placeholder="Εισάγετε τη διεύθυνση σας εδώ" id="map-search-input" name="map-search-input">
          <span class="input-group-append">
            
            <div class="input-group-text bg-transparent" style="color: white;"><i class="fas fa-search"></i></div>  
          </span>
        </div>
      </div>
    </form>
    <br>
    <div class="container" id="map_container">
      <div id="map"></div>   
    </div>
    <br> 
    <div class="row">
			<div class="col">
        <a  class="button" id="checkout_btn" >Ολοκλήρωση Παραγγελίας</a>
			</div>
		</div>
  </div>
</div>

<script> 
  function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: 38.2466, lng: 21.7345},
      zoom: 12
    });
    var input = document.getElementById('map-search-input');
    var searchform = document.getElementById('search-form');
    var autocomplete = new google.maps.places.Autocomplete(input);
    var marker_position;
    var chk_btn = document.getElementById('checkout_btn');
    var lat;
    var lng;
    google.maps.event.addDomListener(input,'keydown',function(e){
      console.log(e.triggered)
      if(e.keyCode===13 && $('.pac-item-selected').length == 0 && !e.triggered){ 
        google.maps.event.trigger(this,'keydown',{keyCode:40}) 
        google.maps.event.trigger(this,'keydown',{keyCode:13,triggered:true}) 
      }
      
    });
    autocomplete.bindTo('bounds', map);
    autocomplete.setOptions({strictBounds: false});
    autocomplete.setTypes(['address']);
    // Set the data fields to return when the user selects a place.
    autocomplete.setFields( [ 'geometry',  'name'] );
    var marker = new google.maps.Marker({
      map: map,
      anchorPoint: new google.maps.Point(0, -29)
    });
    var marker2;
    function addMarker(props) {
      if (marker2) {
        marker2.setPosition(props.coords);
      } else {
          marker2 = new google.maps.Marker({
          position: props.coords,
          map:map,
          draggable: true
        });
      }
      marker_position=marker2.getPosition();
      lat = marker_position.lat();
      lng = marker_position.lng();
    }
    
    autocomplete.addListener('place_changed', function() {
      marker.setVisible(false);
      var place = autocomplete.getPlace();
      if (!place.geometry) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        window.alert("Η διεύθυνση που πληκτρολογήσατε δεν βρέθηκε : '" + place.name + "'\n\nΠαρακαλούμε επιλέξτε χειροκίνητα την διευθυνση σας στο χάρτη!");

        google.maps.event.addListener(map, 'click',function(event) {
          addMarker({coords:event.latLng});
        });
        //return;
      }
      // If the place has a geometry, then present it on a map.
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);  // Why 17? Because it looks good.
      }
      marker.setPosition(place.geometry.location);
      marker.setVisible(true); 
      marker_position=marker.getPosition();
      lat = marker_position.lat();
      lng = marker_position.lng();        
    }); 

    input.addEventListener('click', function(){
      input.value = "";
      marker.setVisible(false);
      marker2.setVisible(false);
    });   
    
    chk_btn.addEventListener('click', function() {
      
       if (lat && lng) { 
        var url = 'includes/map_proc.php?lat=' + lat + '&lng=' + lng;
        downloadUrl(url, function(data, responseCode) { 
          if (responseCode == 200 && data.length <= 2) {
            window.location.replace("checkout.php");
          }
        });
      } else {
            window.alert("Παρακαλώ εισάγετε τη διεύθυνση σας");
      }
      });
  }
  function downloadUrl(url, callback) {
    
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;
            
        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            
            request.onreadystatechange = doNothing;
            callback(request.responseText, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }
      function doNothing () {
      }

</script>

<?php 
    include_once 'footer.php';
?>