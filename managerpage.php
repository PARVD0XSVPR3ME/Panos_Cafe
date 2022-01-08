<?php if (!isset($_SESSION['e_uname'])) {
    header("Location: index.php");
    exit();
}?> 

<div class="container">
	<div class="manager_pg">
        <?php 
        include 'includes/dbconn.php';
		$product_type_var = "γεύματα";  
		$afm = $_SESSION['afm'] ;   
		$sql = "SELECT product.product_name , stock.quantity , stock.store_id , stock.product_id FROM product INNER JOIN stock ON product.id=stock.product_id INNER JOIN store ON stock.store_id=store.id WHERE product.product_type = ? AND store.store_manager =?;";
		$stmt = mysqli_stmt_init($conn);
		if (!mysqli_stmt_prepare($stmt , $sql)) {
			header("Location: ./index.php?stock=preparedstatementfailed");
			exit();
		}
        else {  
        	mysqli_stmt_bind_param($stmt , "ss" , $product_type_var , $afm );
    		mysqli_stmt_execute($stmt);
    		$result = mysqli_stmt_get_result($stmt);
			
			
    		if (mysqli_num_rows($result) < 1) {
				
    		    $sql = "SELECT * FROM product WHERE product_type = ?;";
    		    $stmt = mysqli_stmt_init($conn);
    		    if (!mysqli_stmt_prepare($stmt , $sql)) {
    		        header("Location: ./index.php?prod=preparedstatementfailed");
    		        exit();
    		    }
    		    else {
    		        mysqli_stmt_bind_param($stmt , "s" , $product_type_var);
    		    	mysqli_stmt_execute($stmt);
    		    	$result1 = mysqli_stmt_get_result($stmt);
				
    		        $num_rows = mysqli_num_rows($result1);
    		        if ($num_rows < 1) {
    		            header("Location: ./index.php?products=NotFound");
    		            exit();
    		        }
    		        else {
    		            $sql = "SELECT id FROM store WHERE store.store_manager = ?;";
    		            $stmt = mysqli_stmt_init($conn);
    		            if (!mysqli_stmt_prepare($stmt , $sql)) {
    		                header("Location: ./index.php?store=preparedstatementfailed");
    		                exit();
    		            }
    		            else {
    		                mysqli_stmt_bind_param($stmt , "s" , $afm);
    		                mysqli_stmt_execute($stmt);
    		                $result2 = mysqli_stmt_get_result($stmt);
    		                $num_rows2 = mysqli_num_rows($result2);
    		                if ($num_rows2 < 1) {
    		                    header("Location: ./index.php?manager=NotFound");
    		                    exit();
    		                }
    		                else {
    		                    while($row2 = mysqli_fetch_assoc($result2)) {
									$row2_result = $row2["id"];
									
    		                        while($row1 = mysqli_fetch_assoc($result1)) {
    		                            $row1_result = $row1["id"];
    		                            $sql = "INSERT INTO stock (product_id, quantity, store_id)
    		                            VALUES (?, ?, ?)";
    		                            $stmt = mysqli_stmt_init($conn);
    		                            if (!mysqli_stmt_prepare($stmt , $sql)) {
    		                                header("Location: ./index.php?insert=preparedstatementfailed");
    		                                exit();
    		                            }
    		                            else {
    		                                $quantity = 0;
    		                                mysqli_stmt_bind_param($stmt , "sss" , $row1_result , $quantity , $row2_result);
											mysqli_stmt_execute($stmt);
											echo '<meta http-equiv="refresh" content="0">';
    		                            }                                
    		                        }
    		                    }
    		                }       
    		            }            
    		        }           
    		    }                      
    		} else {		
        	?>
        <div class="products_title">
        	<div class="row">
        		<div class="col">
        			<h3 class="text-light"><?php echo $product_type_var ?></h3>
        		</div>
        	</div>
        </div>
        <div class="form-row">
        <?php	while($row = mysqli_fetch_assoc($result)) { ?>  
        	<div class="col-12">
        		<form method="post" action="index.php?action=updatestock&store_id=<?php echo $row['store_id']; ?>&product_id=<?php echo $row['product_id']; ?>">
        			<div class="form-row products">
        				<div class="col-12 col-sm-3">
        					<h4 class="text-info"><?php echo $row['product_name'] ?></h4>
        				</div>
        				<div class="col-12 col-sm-3">
        					<input name="quantity" class="form-control " type="number" value=<?php echo $row["quantity"] ?> min="0" >
        				</div>
						
        				<div class="col-12 col-sm-3">
				            <input type="submit" name="update_stock" id="update_stock" class="btn btn-info"
                                value="Ενημέρωση Αποθέματος" />	
				        </div>
        			</div>	
        		</form>                    
        	</div>                   
        <?php } ?>
        </div>
		<?php }
		} ?>
		<?php  
		if(filter_input(INPUT_POST, 'update_stock')){	
			$product_id = filter_input(INPUT_GET, 'product_id');
			$quant = filter_input(INPUT_POST, 'quantity');
			$store_id = filter_input(INPUT_GET, 'store_id');
			$sql1 = "UPDATE stock SET stock.quantity=? WHERE stock.product_id=? AND stock.store_id=? ;" ;
			$stmt1 = mysqli_stmt_init($conn);
			if (!mysqli_stmt_prepare($stmt1 , $sql1)) {
				header("Location: ./index.php?signup=preparedstatem2entfailed");
				exit();
			}
			else {
				mysqli_stmt_bind_param($stmt1 , "sss" , $quant , $product_id , $store_id);
				mysqli_stmt_execute($stmt1);
				
			}
			
			echo '<script>location.href="index.php"</script>';
		}
		?>
		<div class="products_title">
        	<div class="row">
        		<div class="col">
        			<h3 class="text-light">Παραγγελίες</h3>
        		</div>
        	</div>
		</div>
		<div id="ordersdiv">
		</div>
    </div>
</div>
	   
<script>
(function () {
	var poll = function() {
		$.ajax({
			url: './includes/manager_orders.php',
			dataType: 'json',
			type: 'get',
			success: function(data) {
				
				var i;
				var j;
				document.getElementById("ordersdiv").innerHTML = "";
				for (i = 1; i <= Object.keys(data).length; i++) {
					
					document.getElementById("ordersdiv").innerHTML += `
					<div class="order_class">
						<h5 class="text-light "><b><u>Παραγγελία ${i}</u></b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>e-mail:</b> ${data[i].email} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Τηλέφωνο:</b> ${data[i].phone}</h5>
						
					</div>`;
					
					for (j = 1; j <= Object.keys(data[i].shopping_cart).length; j++) {
						document.getElementById("ordersdiv").innerHTML += `
						<div class="prod_class">
							<h5 class="text-dark "><b>Ονομα προϊόντος:</b> ${data[i].shopping_cart[j].product_name} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Ποσότητα:</b> ${data[i].shopping_cart[j].quantity}</h5>
							
						</div>
						
					`;
					}	
				}	
			}
		});
	};
	poll();
	setInterval(function() {
		poll();
	} , 5000);	
})();
</script>	
