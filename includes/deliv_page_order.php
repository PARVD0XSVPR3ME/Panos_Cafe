<?php 
if (!isset($_SESSION['e_uname'])) {
    header("Location: index.php");
    exit();
}
if ($_SESSION['count'] === 1 ) {
$sql = "SELECT orders.id,orders.dist_km_usr,store.lat,store.lng,orders.dist_km_store FROM orders 
INNER JOIN store ON orders.store_id=store.id WHERE pros_paradwsh=? and delivered=? ORDER BY orders.id ASC LIMIT 1;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt , $sql)) {
    header("Location: ../deliverypage.php?signup=preparedstatementfailed");
    exit();
}
else {
    $delivered=0;
    $pros_paradwsh = 0;
    mysqli_stmt_bind_param($stmt , "ss" , $pros_paradwsh , $delivered);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultCheck = mysqli_num_rows($result);
    if ($resultCheck < 1) {
      if ($_SESSION['count'] === 1 ) {
        $sql4 ="INSERT INTO delivery_order (delivery_afm , del_availability , lat , lng ) VALUES(? , ? , ? , ?) 
        ON DUPLICATE KEY UPDATE del_availability=? , lat=? , lng=? " ;
        $stmt4 = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt4 , $sql4)) {
          header("Location: ../deliverypage.php?update=preparedstatementfailed");
          exit();
        }
        else {
          
          mysqli_stmt_bind_param($stmt4 , "sssssss" ,$_SESSION['afm'], $_SESSION['availability'] ,$_SESSION['location']['lat'] , $_SESSION['location']['long'] , $_SESSION['availability'] ,$_SESSION['location']['lat'] , $_SESSION['location']['long']);
          mysqli_stmt_execute($stmt4);
        }
      }
      
    }
    else {
        if ($row = mysqli_fetch_assoc($result)) {
          $lat1 = $_SESSION['location']['lat'];
          $long1 = $_SESSION['location']['long'];
          $lat2 = $row['lat'];
          $long2 = $row['lng'];
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
            $dist_km_store = floatval(str_replace(',','.',$dist));
            $sql2 ="INSERT INTO delivery_order (delivery_afm , del_availability , lat , lng , order_id) VALUES(? , ? , ? , ? , ? ) 
            ON DUPLICATE KEY UPDATE del_availability=? , lat=? , lng=? , order_id=?" ;
            $stmt2 = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt2 , $sql2)) {
              header("Location: ../deliverypage.php?update=preparedstatementfailed");
              exit();
            }
            else {
              $_SESSION['availability']=0;
              mysqli_stmt_bind_param($stmt2 , "sssssssss" ,$_SESSION['afm'], $_SESSION['availability'] ,$_SESSION['location']['lat'] , $_SESSION['location']['long'], $row['id'] , $_SESSION['availability'] ,$_SESSION['location']['lat'] , $_SESSION['location']['long'], $row['id']);
              mysqli_stmt_execute($stmt2);
            }


            $sql3 = "UPDATE orders SET pros_paradwsh=? , dist_km_store=? WHERE id=?" ;
            $stmt3 = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt3 , $sql3)) {
              header("Location: ../deliverypage.php?update=preparedstatementfailed");
              exit();
            }
            else {
              $pros_paradwsh2=1;
              mysqli_stmt_bind_param($stmt3 , "sss" , $pros_paradwsh2 , $dist_km_store , $row['id']);
              mysqli_stmt_execute($stmt3);
            }
        }
    }
}
}