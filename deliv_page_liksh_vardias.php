<?php 
include_once 'header.php';
if (!isset($_SESSION['e_uname'])) {
  header("Location: index.php");
  exit();
}
date_default_timezone_set('Europe/Athens'); 


include 'includes/dbconn.php';
if (isset($_POST['status_btn_ac2'])) {
    $_SESSION['status'] = 0;
    $_SESSION['availability'] = 0;
    $_SESSION['end_time'] = date("H:i:s");
    $t1 = strtotime($_SESSION['start_time']);
    $t2 = strtotime($_SESSION['end_time']);
    $_SESSION['daily_payment'] = round( ( ((($t2 - $t1) / 3600)*5) + $_SESSION['daily_km']*0.10) , 2 );
    $sql3 = "UPDATE delivery_order SET del_availability=? WHERE delivery_afm=?" ;
    $stmt3 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt3 , $sql3)) {
      header("Location: ./deliv_page_liksh_vardias.php?update=preparedstatementfailed");
      exit();
    }
    else {
    
      mysqli_stmt_bind_param($stmt3 , "ss" , $_SESSION['availability'] , $_SESSION['afm']);
      mysqli_stmt_execute($stmt3);
    }
    
    $sql4 = "INSERT INTO delivery_daily_payment (del_afm , payment_date , amount ) VALUES (? , ? , ?);" ;
    $stmt4 = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt4 , $sql4)) {
      header("Location: ./deliv_page_liksh_vardias.php?insert=preparedstatementfailed");
      exit();
    }
    else {
      $payment_date = date('Y-m-d');
      mysqli_stmt_bind_param($stmt4 , "sss" , $_SESSION['afm'] , $payment_date , $_SESSION['daily_payment']);
      mysqli_stmt_execute($stmt4);
    }
}

?>
  
  
  
<div class="container" id="adresspage_container">  
  <div class="address_bg">
    <?php if (!$_SESSION['status']) { 
            
    ?>
    <div class="row">
		<div class="col">
            <a  class="btn btn-danger btn-lg btn-block" id="del_in_btn">Ανενεργός (Μη Διαθέσιμος)</a>
		</div>
	</div>
    <div class="maps_title">
        <div class="row">
            <div class="col">
                <h4 class="text-light "><?php echo "Σύνολο χρημάτων : " . $_SESSION['daily_payment'];?></h4>
            </div>
        </div>
    </div>
    
    <div class="maps_title">
        <div class="row">
            <div class="col">
            
                <h4 class="text-light "><?php echo "Πλήθος διαδρομών : " . $_SESSION['diadromes'];?></h4>
            </div>
        </div>
    </div>
    
    <div class="maps_title">
        <div class="row">
            <div class="col">
            
                <h4 class="text-light "><?php echo "Συνολικά χιλιόμετρα : " . $_SESSION['daily_km'];?></h4>
            </div>
        </div>
    </div>
    <br>
    <?php } ?>
  </div>
</div>



<?php  
  include_once 'footer.php';
?>