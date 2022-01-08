<?php 
include_once 'header.php';
if (!isset($_SESSION['e_uname'])) {
  header("Location: index.php");
  exit();
}  
$_SESSION['count']+=1;
include 'includes/dbconn.php';
include 'includes/deliv_page_order.php';
include 'includes/deliv_page_order_info.php';

if (isset($_GET['order_deliv_btn'])) {
  
  $_SESSION['count']=1;
  include 'includes/deliv_page_compl_order.php';
  header("Location: ./deliv_page.php");
}
?>   
<div class="container" id="adresspage_container">  
  <div class="address_bg">
    <?php
    
     if ($_SESSION['status']) {   
    if ($_SESSION['availability']) {
    ?>
    <div class="row">
        <div class="col">
          <form action="deliv_page_liksh_vardias.php" method="post" name="status_form_active2">
            <input type="submit"  class="btn btn-success btn-lg btn-block" name="status_btn_ac2" value="Ενεργός" /> 
          </form>
        </div>
    </div>
    <br>
    <?php } ?>
    <div class="maps_title">
        <div class="row">
            <div class="col">
                <h4 class="text-light "> Λεπτομέρειες Παραλαβής</h4>
                <?php if ($_SESSION['order_info']) { ?>
                <ul class="list-group list-group-flush text-light text-center">
                  <li class="list-group-item order_store_summary"><u>Κατάστημα παραλαβής:</u><br><?php echo $_SESSION['order_info']['store_name']; ?></li>
                  <li class="list-group-item order_store_summary"><u>Διεύθυνση:</u><br><?php echo $_SESSION['order_info']['address']; ?></li>
                  <li class="list-group-item order_store_summary"><u>Τηλέφωνο:</u><br><?php echo $_SESSION['order_info']['store_phone']; ?></li>
                </ul>
                <?php } ?>
            </div>
        </div>
    </div>
    <br>
    <div class="container" id="map_container">
    <div class="maps_title">
    <h4 class="text-light "> Λεπτομέρειες Πελάτη</h4>

<?php if ($_SESSION['order_info']) { ?>
    </div>  
      <div id="map"></div>   
    </div>
    <br>
    <div class="row">
		  <div class="col">
        <form action="deliv_page.php" method="GET" name="order_delivered">
          <input type="submit"  class="btn btn-info btn-lg btn-block" name="order_deliv_btn" value="Ολοκλήρωση Παράδοσης" /> 
        </form>
		  </div>
	  </div> 
    <?php } 
} ?>
  </div>
</div>

<script> 
  function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: 38.2466, lng: 21.7345},
      zoom: 12
    });
    
    
    var marker = new google.maps.Marker({
      map: map,
      position:{lat:<?php echo $_SESSION['order_info']['user_lat']; ?>,lng:<?php echo $_SESSION['order_info']['user_lng']; ?>},
      
    });
    map.setCenter(marker.getPosition());
    map.setZoom(17);  
    
    var contentString = '<div id="content">'+
            '<h5><u>Λεπτομέρειες Πελάτη: </u></h5>'+
            '<p class="text-center font-weight-normal"><font size="4"><?php echo $_SESSION['order_info']['u_email']; ?></font><br>' +
            '<font size="3"><?php echo $_SESSION['order_info']['users_phone']; ?></font></p>'+
            '</div>';
    var infowindow = new google.maps.InfoWindow({
      content: contentString
    });
    marker.addListener('click', function() {
      infowindow.open(map, marker);
    });

  }
</script>

<?php  
  include_once 'footer.php';
?>