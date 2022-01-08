<?php 


if (!isset($_SESSION['e_uname'])) {
  header("Location: ../index.php");
  exit();
}
$order_id=0;
$sql1 = "SELECT orders.dist_km_usr,orders.dist_km_store,orders.lat,orders.lng,orders.id FROM delivery_order INNER JOIN orders ON delivery_order.order_id=orders.id WHERE delivery_afm = ?  and orders.delivered=?;";
$stmt1 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt1 , $sql1)) {
    
    header("Location: ../deliv_page.php?order=preparedstatementfailed");
    exit();
}
else {
    $delivered=0;
    mysqli_stmt_bind_param($stmt1 , "ss" , $_SESSION['afm'], $delivered);
    mysqli_stmt_execute($stmt1);
    $result1 = mysqli_stmt_get_result($stmt1);
    
    if ($row1 = mysqli_fetch_assoc($result1)) {
        $order_id = $row1['id'];
        $_SESSION['daily_km'] += ($row1['dist_km_usr']+$row1['dist_km_store']); 
        $_SESSION['location']['lat'] = $row1['lat'];
        $_SESSION['location']['long'] = $row1['lng'];
        
    }
    
}

$_SESSION['diadromes'] += 1;
$_SESSION['availability'] = 1;
$sql20 = "UPDATE delivery_order SET del_availability=? ,lat=?, lng=? WHERE delivery_afm=?" ;
$stmt20 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt20 , $sql20)) {
  header("Location: ../deliv_page.php?update=preparedstatementfailed1");
  exit();
}
else {
 
  mysqli_stmt_bind_param($stmt20 , "ssss" , $_SESSION['availability'], $_SESSION['location']['lat'], $_SESSION['location']['long'] ,$_SESSION['afm']);
  mysqli_stmt_execute($stmt20);
}


$sql21 = "UPDATE orders SET delivered=? , pros_paradwsh=? WHERE id=?" ;

$stmt21 = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt21 , $sql21)) {
  header("Location: ../deliv_page.php?update=preparedstatementfailed2");
  exit();
}
else {
  if ($order_id !== 0) {
    $delivered=1;
    $pros_paradwsh=0;
    mysqli_stmt_bind_param($stmt21 , "sss" , $delivered, $pros_paradwsh ,$order_id);
    mysqli_stmt_execute($stmt21);
  }
}



