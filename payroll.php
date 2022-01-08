<?php

include './includes/dbconn.php';
$month = $_GET['month'];
$year = $_GET['year'];

$xml = new DOMDocument("1.0","utf-8");
$xml->formatOutput=true;
$xml1 = $xml->createElement("xml");
$xml->appendChild($xml1);

$header = $xml->createElement("header");
$xml1->appendChild($header);

$transaction = $xml->createElement("transaction");
$header->appendChild($transaction);

$period = $xml->createElement("period");
$period->setAttribute( "month", $month );
$period->setAttribute( "year", $year );
$transaction->appendChild($period);

$body = $xml->createElement("body");
$xml1->appendChild($body);

$employees = $xml->createElement("employees");
$body->appendChild($employees);

$sql = "SELECT * FROM employee ";  
$stmt = mysqli_stmt_init($conn);
if (!mysqli_stmt_prepare($stmt , $sql)) {
header("Location: payroll.php?preparedstatementfailed");
exit();
}
else {	
	
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if (mysqli_num_rows($result) < 1) {	
		header("Location: payroll.php?employee=NotFound");
		exit();
	} else {
        
		while($row = mysqli_fetch_assoc($result)) {
            $employee = $xml->createElement("employee");
            $employees->appendChild($employee);

            $firstName = $xml->createElement("firstName" , $row['name']);
            $employee->appendChild($firstName);

            $lastName = $xml->createElement("lastName" , $row['surname']);
            $employee->appendChild($lastName);

            $amka = $xml->createElement("amka" , $row['amka']);
            $employee->appendChild($amka);

            $afm = $xml->createElement("afm" , $row['afm']);
            $employee->appendChild($afm);

            $iban = $xml->createElement("iban" , $row['iban']);
            $employee->appendChild($iban);
            
			if ($row['employee_type'] === 'dianomeas') {
                $count1 = 0;

                $sql8 = "SELECT amount FROM delivery_daily_payment WHERE MONTH(payment_date) = ?  and YEAR(payment_date) = ? and del_afm=?";  
                $stmt8 = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt8 , $sql8)) {
                header("Location: payroll.php?preparedstatementfailed");
                exit();
                }
                else {	
                    mysqli_stmt_bind_param($stmt8 , "sss" , $month , $year , $row['afm'] );
                    mysqli_stmt_execute($stmt8);
                    $result8 = mysqli_stmt_get_result($stmt8);
                	
                    while($row8 = mysqli_fetch_assoc($result8)) {  
                        $count1+=$row8['amount'];
                    }	
                    $ammount = $xml->createElement("ammount" , $count1);
                    $employee->appendChild($ammount);
                }
            } elseif ($row['employee_type'] === 'manager') {
                
                $count = 0;
                $count_total = 0;
                $sql8 = "SELECT orders.id FROM orders INNER JOIN store ON orders.store_id=store.id WHERE MONTH(date_created) = ?  and YEAR(date_created) = ? and store.store_manager=?";  
                $stmt8 = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt8 , $sql8)) {
                header("Location: payroll.php?preparedstatementfailed");
                exit();
                }
                else {	
                    mysqli_stmt_bind_param($stmt8 , "sss" , $month , $year , $row['afm'] );
                    mysqli_stmt_execute($stmt8);
                    $result8 = mysqli_stmt_get_result($stmt8);	
                        while($row8 = mysqli_fetch_assoc($result8)) {  
                            $sql9 = "SELECT orderproducts.quantity,product.price FROM orderproducts INNER JOIN product ON orderproducts.prod_id=product.id WHERE orderproducts.order_id = ? ";  
                            $stmt9 = mysqli_stmt_init($conn);
                            if (!mysqli_stmt_prepare($stmt9 , $sql9)) {
                            header("Location: payroll.php?preparedstatementfailed");
                            exit();
                            }
                            else {	
                                mysqli_stmt_bind_param($stmt9 , "s" , $row8['id']   );
                                mysqli_stmt_execute($stmt9);
                                $result9 = mysqli_stmt_get_result($stmt9);
                                if (mysqli_num_rows($result9) < 1) {	
                                    header("Location: payroll.php?orders=NotFound");
                                    exit();
                                } else {
                                    $count = 0;		
                                    while($row9 = mysqli_fetch_assoc($result9)) {  
                                        
                                        $count += $row9['quantity']*$row9['price'];
                                        
                                    }
                                    
                                }  
                            }
                            $count_total += $count;
                        }	
                        
                        $manager_ammount = round((800 + $count_total*0.02) , 2); 
                        $ammount = $xml->createElement("ammount" , $manager_ammount);
                        $employee->appendChild($ammount);
                }   
            }			
        }
	}  
}

echo "<xmp>" . $xml->saveXML() . "</xmp>";
$xml->save("payroll.xml");
?>
