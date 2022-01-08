<?php 


if (!isset($_SESSION['e_uname'])) {
  header("Location: ../index.php");
  exit();
}


$_SESSION['order_info'] = array();
$sql = "SELECT orders.u_email , orders.lat as user_lat , orders.lng as user_lng , users.phone as users_phone , store.store_name , store.address , 
store.phone as store_phone, store.lat as store_lat , store.lng as store_lng , orders.dist_km_store,orders.dist_km_usr ,delivery_order.del_availability 
FROM delivery_order INNER JOIN orders ON delivery_order.order_id=orders.id INNER JOIN store ON orders.store_id=store.id INNER JOIN users 
ON orders.u_email=users.email_address WHERE delivery_afm = ?  and orders.delivered=?;";
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt , $sql)) {
    
    header("Location: ../deliv_page.php?order=preparedstatementfailed");
    exit();
}
else {
    $delivered=0;
    mysqli_stmt_bind_param($stmt , "ss" , $_SESSION['afm'], $delivered);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $resultCheck = mysqli_num_rows($result);
    if ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['availability'] = $row['del_availability'];
        $_SESSION['order_info'] = $row;
    }
    
}



