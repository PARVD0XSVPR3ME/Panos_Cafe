<?php 

session_start();

if (!isset($_SESSION['e_uname'])) {
    header("Location: ../index.php");
    exit();
}

include 'dbconn.php';
$afm = $_SESSION['afm'] ;
$orders_array = array() ;
$lat2 = 0 ; 
$long2 = 0 ;

$sql8 = "SELECT orders.id,orders.u_email,users.phone,store.lat,store.lng,orders.dist_km_usr,orders.pros_paradwsh FROM orders INNER JOIN users ON users.email_address=orders.u_email INNER JOIN store ON orders.store_id=store.id WHERE store.store_manager =? and orders.delivered = ?";  
$stmt8 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt8 , $sql8)) {
header("Location: ../index.php?preparedstatementfailed");
exit();
}
else {
	$delivered = false;	
	mysqli_stmt_bind_param($stmt8 , "ss" , $afm , $delivered   );
	mysqli_stmt_execute($stmt8);
	$result8 = mysqli_stmt_get_result($stmt8);
	
		$count=0;	

		while($row8 = mysqli_fetch_assoc($result8)) {  
			$lat2 = $row8['lat'] ; 
      $long2 = $row8['lng'] ;
			if ($row8['pros_paradwsh'] === 0) {
				$sql10 = "SELECT lat,lng,delivery_afm FROM delivery_order WHERE del_availability = ? ";  
      	$stmt10 = mysqli_stmt_init($conn);
      	if (!mysqli_stmt_prepare($stmt10 , $sql10)) {
      	  header("Location: ../index.php?preparedstatementfailed");
      	  exit();
      	}
      	else {
				
      	  $del_avail = true;
				
      	  mysqli_stmt_bind_param($stmt10 , "s" , $del_avail ); 
      	  mysqli_stmt_execute($stmt10);
      	  $result10 = mysqli_stmt_get_result($stmt10);	
      	  if (mysqli_num_rows($result10) > 0) {	
      	    $dist_var = 0;
      	    $loops = 1;
      	    $del_afm="";
      	    while($row10 = mysqli_fetch_assoc($result10)) {  
      	      $lat1 = $row10['lat'] ; 
      	      $long1 = $row10['lng'] ;
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
      	        $del_afm = $row10['delivery_afm'];
      	      } else {
      	        if ($dist < $dist_var) {
      	          $dist_var = $dist;
      	          $del_afm = $row10['delivery_afm'];
      	        }
      	      }
      	      $loops++; 
      	    }
					
      	    if (isset($del_afm) && !empty($del_afm)) {
      	      $sql11 = "UPDATE delivery_order SET order_id=? , del_availability=?  WHERE delivery_afm=?" ;
      	      $stmt11 = mysqli_stmt_init($conn);
      	      if (!mysqli_stmt_prepare($stmt11 , $sql11)) {
      	        header("Location: ../index.php?update=preparedstatementfailed");
      	        exit();
      	      }
      	      else {
      	        $del_avail_2=0;
      	        mysqli_stmt_bind_param($stmt11 , "sss" , $row8['id'] , $del_avail_2 , $del_afm);
      	        mysqli_stmt_execute($stmt11);
      	      }

							$sql12 = "UPDATE orders SET pros_paradwsh=? , dist_km_store=?  WHERE id=?" ;
      	      $stmt12 = mysqli_stmt_init($conn);
      	      if (!mysqli_stmt_prepare($stmt12 , $sql12)) {
      	        header("Location: ../index.php?update=preparedstatementfailed");
      	        exit();
      	      }
      	      else {
								$dist_km_store = floatval(str_replace(',','.',$dist_var));
								
      	        $pros_paradwsh=1;
      	        mysqli_stmt_bind_param($stmt12 , "sss" , $pros_paradwsh , $dist_km_store , $row8['id']);
      	        mysqli_stmt_execute($stmt12);
      	      }
      	    }
      	  }
      	}
			}
			$orders_array[++$count] = array(
				'email' => $row8['u_email'],
       	'phone' => $row8['phone']
			); 
			
			$sql9 = "SELECT orderproducts.quantity,product.product_name FROM orderproducts INNER JOIN product ON orderproducts.prod_id=product.id WHERE orderproducts.order_id = ? ";  
			$stmt9 = mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($stmt9 , $sql9)) {
			header("Location: ../index.php?preparedstatementfailed");
			exit();
			}
			else {	
				mysqli_stmt_bind_param($stmt9 , "s" , $row8['id']   );
				mysqli_stmt_execute($stmt9);
				$result9 = mysqli_stmt_get_result($stmt9);
				
					$count1 = 0;		
					while($row9 = mysqli_fetch_assoc($result9)) {  
						
						$orders_array[$count]['shopping_cart'][++$count1] = array(
							'product_name' => $row9['product_name'],
			       			'quantity' => $row9['quantity']
						); 
						
					}
				  
			}
		}	
	  
}
 



echo json_encode($orders_array);

?>