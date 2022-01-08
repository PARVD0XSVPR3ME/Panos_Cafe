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
    <br>
      <?php 
      
      include 'includes/dbconn.php';
      date_default_timezone_set('Europe/Athens');
      $orders_id = 0;
      $sql = "SELECT lat,lng,id FROM store";  
      $stmt = mysqli_stmt_init($conn);
      if (!mysqli_stmt_prepare($stmt , $sql)) {
      	header("Location: ./checkout.php?preparedstatementfailed");
      	exit();
      }
      else {  
      	mysqli_stmt_execute($stmt);
      	$result = mysqli_stmt_get_result($stmt);	
      	if (mysqli_num_rows($result) < 1) {	
              header("Location: ./checkout.php?store=NotFound");
              exit();
      	} else {
          $lat2 = $_SESSION['address']['lat'] ; 
          $long2 = $_SESSION['address']['long'] ; 
          $loops = 1;
          $id = 0;
          $count = 0;
          $count1 = 0;
          $prod_id = array() ;
          $prod_id_upd = array() ;
          $prod_quantity = array() ; 
          $prod_quantity_upd = array() ; 
          $prod_quantity_cart_upd = array() ;
          $prod_quantity_cart = array() ;
          $dist_var = 0;
          $dist_upd = 0;
          $dist_var2 = 0;
          $id2 = 0;
      	  while($row = mysqli_fetch_assoc($result)) {  
            $lat1 = $row['lat'] ; 
            $long1 = $row['lng'] ;
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&&language=el&key=***REMOVED***";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $response = curl_exec($ch);
            curl_close($ch);
            $response_a = json_decode($response, true);
            $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
            
            if ($loops === 1) {
              $dist_var = $dist;
              $dist_var2 = $dist;
              $id2 = $row['id'];
              $sql1 = "SELECT * FROM stock WHERE store_id= ?";  
              $stmt1 = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt1 , $sql1)) {
              	header("Location: ./checkout.php?preparedstatementfailed");
              	exit();
              }
              else {  
              	mysqli_stmt_bind_param($stmt1 , "s" , $row['id'] );
              	mysqli_stmt_execute($stmt1);
              	$result1 = mysqli_stmt_get_result($stmt1);	
              	if (mysqli_num_rows($result) < 1) {	
                      header("Location: ./checkout.php?stock=NotFound");
                      exit();
              	} else {		
              	  while($row1 = mysqli_fetch_assoc($result1)) {      
                    $key = array_search($row1['product_id'], array_column($_SESSION['shopping_cart'], 'id'));
                    
                    if(is_numeric($key)) {
                      $count1++;
                      
                      if($row1['quantity'] >= $_SESSION['shopping_cart'][$key]['quantity']) {
                        array_push($prod_id, $row1['product_id']);
                        array_push($prod_quantity, $row1['quantity']);
                        array_push($prod_quantity_cart, $_SESSION['shopping_cart'][$key]['quantity']);
                        $count++;
                      }   
                    }
                  } 
                  if ($count === $count1 and $count!==0 and $count1!==0) {
                    $id = $row['id'];
                    $dist_upd = $dist_var;
                    for($x = 0; $x < count($prod_quantity); $x++) {
                      array_push($prod_id_upd, $prod_id[$x]);
                      array_push($prod_quantity_upd, $prod_quantity[$x]-$prod_quantity_cart[$x]);
                      array_push($prod_quantity_cart_upd, $prod_quantity_cart[$x]);
                    }
                  }
                }
              }
            }
            else {
                if ($dist < $dist_var2) {
                  $dist_var2 = $dist;
                  $id2 = $row['id'];
                }    
                unset($prod_id); 
                $prod_id = array();
                unset($prod_quantity); 
                $prod_quantity = array();
                unset($prod_quantity_cart); 
                $prod_quantity_cart = array();
                $count1 = 0;
                $count = 0;
                $sql1 = "SELECT * FROM stock WHERE store_id= ?";  
                $stmt1 = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt1 , $sql1)) {
                	header("Location: ./checkout.php?preparedstatementfailed");
                	exit();
                }
                else {  
                	mysqli_stmt_bind_param($stmt1 , "s" , $row['id'] );
                	mysqli_stmt_execute($stmt1);
                	$result1 = mysqli_stmt_get_result($stmt1);	
                	if (mysqli_num_rows($result) < 1) {	
                        header("Location: ./checkout.php?stock=NotFound");
                        exit();
                	} else {		
                	  while($row1 = mysqli_fetch_assoc($result1)) {
                      $key = array_search($row1['product_id'], array_column($_SESSION['shopping_cart'], 'id'));
                      
                      if(is_numeric($key)) {
                        $count1++;
                        
                        if($row1['quantity'] >= $_SESSION['shopping_cart'][$key]['quantity']) {
                            
                            array_push($prod_id, $row1['product_id']);
                            array_push($prod_quantity, $row1['quantity']);
                            array_push($prod_quantity_cart, $_SESSION['shopping_cart'][$key]['quantity']);
                            $count++;
                        } 
                      }
                    } 
                    if ($count === $count1 and $count!==0 and $count1!==0) {
                      if ($dist < $dist_var) {
                        unset($prod_quantity_upd); 
                        $prod_quantity_upd = array();
                        unset($prod_id_upd); 
                        $prod_id_upd = array();
                        $dist_var = $dist;
                        $dist_upd = $dist_var;
                        $id = $row['id'];
                        for($x = 0; $x < count($prod_quantity); $x++) {
                          array_push($prod_quantity_upd, $prod_quantity[$x]-$prod_quantity_cart[$x]);
                          array_push($prod_id_upd, $prod_id[$x]);
                          array_push($prod_quantity_cart_upd, $prod_quantity_cart[$x]);
                        }
                      }
                    }
                  }
                }
              
            }
            $loops++;  
            
          }
          
          $coffee_count = 0;
          for($x = 0; $x < count(array_column($_SESSION['shopping_cart'], 'id')); $x++) {
            if($_SESSION['shopping_cart'][$x]['id'] <=5) {
              $coffee_count++;
            }
          }
          $snack_count = 0;
          for($x = 0; $x < count(array_column($_SESSION['shopping_cart'], 'id')); $x++) {
            if($_SESSION['shopping_cart'][$x]['id'] > 5) {
              $snack_count++;
            }
          }
          if ($id!==0 and $snack_count!==0 ) {
            
            echo "<h3 style='color:white'>Η παραγγελία σας καταχωρήθηκε ! </h3>";
            $sql4 = "INSERT INTO orders (u_email , store_id , lat ,lng , date_created ,dist_km_usr , delivered , pros_paradwsh) VALUES (? , ? , ? , ?, ? , ? , ? , ?);" ;
            $stmt4 = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt4 , $sql4)) {
              header("Location: ./checkout.php?insert=preparedstatementfailed");
              exit();
            }
            else {
              $date = date('Y-m-d');
              $pros_paradwsh = false;
              $delivered = false;
              $dist_upd_fl = floatval(str_replace(',','.',$dist_upd));
              mysqli_stmt_bind_param($stmt4 , "ssssssss" , $_SESSION['u_email'] , $id , $_SESSION['address']['lat'] , $_SESSION['address']['long'] , $date , $dist_upd_fl , $delivered,$pros_paradwsh);
              mysqli_stmt_execute($stmt4);
            }
            $orders_id = mysqli_insert_id($conn);
            for($x = 0; $x < count($prod_id_upd); $x++) {
                
                $sql2 = "UPDATE stock SET stock.quantity=? WHERE stock.product_id=? AND stock.store_id=? ;" ;
			          $stmt2 = mysqli_stmt_init($conn);
			          if (!mysqli_stmt_prepare($stmt2 , $sql2)) {
			          	header("Location: ./checkout.php?updatestock=preparedstatementfailed");
			          	exit();
			          }
			          else {
			          	mysqli_stmt_bind_param($stmt2 , "sss" , $prod_quantity_upd[$x] , $prod_id_upd[$x] , $id);
			          	mysqli_stmt_execute($stmt2);
                }
                $sql3 = "INSERT INTO orderproducts ( prod_id , quantity ,order_id ) VALUES (? , ? , ? );" ;
			          $stmt3 = mysqli_stmt_init($conn);
			          if (!mysqli_stmt_prepare($stmt3 , $sql3)) {
			          	header("Location: ./checkout.php?insert=preparedstatementfailed");
			          	exit();
			          }
			          else {
			          	mysqli_stmt_bind_param($stmt3 , "sss" , $prod_id_upd[$x] , $prod_quantity_cart_upd[$x] , $orders_id);
			          	mysqli_stmt_execute($stmt3);
			          }
            }
            
            for($x = 0; $x < count(array_column($_SESSION['shopping_cart'], 'id')); $x++) {
              
              if ($_SESSION['shopping_cart'][$x]['id']<=5) {
                
                $sql3 = "INSERT INTO orderproducts ( prod_id , quantity , order_id ) VALUES (? , ? , ? );" ;
			          $stmt3 = mysqli_stmt_init($conn);
			          if (!mysqli_stmt_prepare($stmt3 , $sql3)) {
			          	header("Location: ./checkout.php?insert=preparedstatementfailed");
			          	exit();
			          }
			          else {
			          	mysqli_stmt_bind_param($stmt3 , "sss" , $_SESSION['shopping_cart'][$x]['id'] , $_SESSION['shopping_cart'][$x]['quantity'] , $orders_id);
			          	mysqli_stmt_execute($stmt3);
                }
              }
            }
            
          }
          elseif ($id=== 0 and $snack_count===0 and $coffee_count!==0) {
            
            
              echo "<h3 style='color:white'>Η παραγγελία σας καταχωρήθηκε ! </h3>";
              $sql5 = "INSERT INTO orders (u_email , store_id , lat ,lng , date_created , dist_km_usr , delivered , pros_paradwsh) VALUES (? , ? , ? , ?, ? , ? , ? , ?);" ;
              $stmt5 = mysqli_stmt_init($conn);
              if (!mysqli_stmt_prepare($stmt5 , $sql5)) {
                header("Location: ./checkout.php?insert=preparedstatementfailed");
                exit();
              }
              else {
                $date = date('Y-m-d');
                $pros_paradwsh = false;
                $delivered = false;
                $dist_var2_fl = floatval(str_replace(',','.',$dist_var2));
                mysqli_stmt_bind_param($stmt5 , "ssssssss" , $_SESSION['u_email'] , $id2 , $_SESSION['address']['lat'] , $_SESSION['address']['long'] , $date , $dist_var2_fl , $delivered , $pros_paradwsh);
                mysqli_stmt_execute($stmt5);
              }
              $orders_id = mysqli_insert_id($conn);
              
              for($x = 0; $x < count(array_column($_SESSION['shopping_cart'], 'id')); $x++) {
                
                if ($_SESSION['shopping_cart'][$x]['id']<=5) {
                  
                  $sql6 = "INSERT INTO orderproducts ( prod_id , quantity , order_id ) VALUES (? , ? , ? );" ;
			            $stmt6 = mysqli_stmt_init($conn);
			            if (!mysqli_stmt_prepare($stmt6 , $sql6)) {
			            	header("Location: ./checkout.php?insert=preparedstatementfailed");
			            	exit();
			            }
			            else {

			            	mysqli_stmt_bind_param($stmt6 , "sss" , $_SESSION['shopping_cart'][$x]['id'] , $_SESSION['shopping_cart'][$x]['quantity'] , $orders_id);
			            	mysqli_stmt_execute($stmt6);
                  }
                }
              } 
          } else {
            echo "<h3 style='color:white'>Τα προιοντα που ζητησατε δεν υπαρχουν σε κανενα καταστημα</h3>"; 
          } ?>
          <div class="row">
		        <div class="col">
                  <a href="./index.php" class="button return_btn" >Επιστροφή στην αρχική</a>
		        </div>
	        </div>
  <?php }
      } 
      ?>
    <br>
  </div>
</div>


<?php  
  include_once 'footer.php';
?>