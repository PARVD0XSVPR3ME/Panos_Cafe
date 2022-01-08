<?php if (!isset($_SESSION['u_email'])) {
    header("Location: index.php");
    exit();
}?> 

<?php

$product_ids = array();


if(filter_input(INPUT_POST, 'add_to_cart')){
	
    if(isset($_SESSION['shopping_cart'])){
		
		$count = count($_SESSION['shopping_cart']);
		
		
		$product_ids = array_column($_SESSION['shopping_cart'], 'id');
		
		if (!in_array(filter_input(INPUT_GET, 'id'), $product_ids)){
			$_SESSION['shopping_cart'][$count] = array
				(
					'id' => filter_input(INPUT_GET, 'id'),
					'product_name' => filter_input(INPUT_POST, 'product_name'),
					'price' => filter_input(INPUT_POST, 'price'),
					'quantity' => filter_input(INPUT_POST, 'quantity')
				);   
		}
		else { 
			
			for ($i = 0; $i < count($product_ids); $i++){
				if ($product_ids[$i] == filter_input(INPUT_GET, 'id')){
					
					$_SESSION['shopping_cart'][$i]['quantity'] += filter_input(INPUT_POST, 'quantity');
				}
			}
		}

	}
	else { 
		 
		 $_SESSION['shopping_cart'][0] = array 
		 (
				'id' => filter_input(INPUT_GET, 'id'),
                'product_name' => filter_input(INPUT_POST, 'product_name'),
                'price' => filter_input(INPUT_POST, 'price'),
                'quantity' => filter_input(INPUT_POST, 'quantity')
		 );
	}
}
if(filter_input(INPUT_GET, 'action') == 'delete'){
    
    foreach($_SESSION['shopping_cart'] as $key => $product){
        if ($product['id'] == filter_input(INPUT_GET, 'id')){
            
            unset($_SESSION['shopping_cart'][$key]);
        }
    }
    
    $_SESSION['shopping_cart'] = array_values($_SESSION['shopping_cart']);
}



?> 
 


<div class="container" id="order_container">  
<div class="products_bg">
<?php 
include 'includes/dbconn.php';
$product_type_var = array("καφές","γεύματα");
for ($x = 0;  $x <= count($product_type_var)-1; $x++) {
	$sql = "SELECT * FROM product WHERE product_type = ?;";
	$stmt = mysqli_stmt_init($conn);
	if (!mysqli_stmt_prepare($stmt , $sql)) {
		header("Location: ./userpage.php?signup=preparedstatementfailed");
		exit();
	}
	else {  
		mysqli_stmt_bind_param($stmt , "s" , $product_type_var[$x]);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck < 1) {
			header("Location: ./userpage.php?products=NotFound");
			exit();
		}
		else {
			if ($x == 0) { ?>
				<div class="products_title">
					<div class="row">
						<div class="col">
							<h3 class="text-light"><?php echo $product_type_var[$x] ?></h3>
						</div>
					</div>
				</div>	
			<?php }
			else { ?>
				<div class="products_title">
					<div class="row">
						<div class="col">
							<h3 class="text-light"><?php echo $product_type_var[$x] ?></h3>
						</div>
					</div>
				</div>
			<?php }	?>
			<div class="form-row">
			<?php	while ($product = mysqli_fetch_assoc($result)) { ?>
				
				<div class="col-12">
					
					
					<form method="post" action="index.php?action=add&id=<?php echo $product['id']; ?>">
						<div class="form-row products">
							<div class="col-12 col-sm-3">
								<h4 class="text-info"><?php echo $product['product_name'] ?></h4>
							</div>
							<div class="col-12 col-sm-3">
								<h4><?php echo $product['price'] ?> €</h4>
							</div>
							<div class="col-12 col-sm-3">
								<input name="quantity" class="form-control " type="number" value="1" min="1" >
							</div>
							<div class="form-group">
							<input type="hidden" name="product_name" value="<?php echo $product['product_name']; ?>" />
							<input type="hidden" name="price" value="<?php echo $product['price']; ?>" />
							</div>
							<div class="col-12 col-sm-3">
							<input type="submit" name="add_to_cart" id="add_to_cart" class="btn btn-info"
                                   value="Αγορά" />	
							</div>
						</div>	
						</form>
				</div>
			<?php } ?>
			</div>
		<?php }
	}
}
?>			
</div>
<div style="clear:both"></div>  
<br /> 
<div class="table-responsive">  
        <table class="table table-light" id="order_table">  
            <tr><th colspan="5"><h3>Λεπτομέρειες<br>Παραγγελιας</h3></th></tr>   
        <tr>  
             <th width="70%">Όνομα Προιόντος</th>  
             <th width="10%">Ποσότητα</th>  
               
             <th width="15%">Σύνολο</th>  
             <th width="5%"></th>  
        </tr>  
        <?php   
        if(!empty($_SESSION['shopping_cart'])):  
            
             $total = 0;  
        
             foreach($_SESSION['shopping_cart'] as $key => $product): 
        ?>  
        <tr>  
           <td><?php echo $product['product_name']; ?></td>  
           <td><?php echo $product['quantity']; ?></td>  
             
           <td><?php echo number_format($product['quantity'] * $product['price'], 2); ?> €</td>  
           <td>
               <a id="remove_a" href="index.php?action=delete&id=<?php echo $product['id']; ?>">
                    <div class="btn-danger" id="remove_btn">Διαγραφή</div>
               </a>
           </td>  
        </tr>  
        <?php  
                  $total = $total + ($product['quantity'] * $product['price']);  
             endforeach;  
        ?>  
        <tr>  
             <td colspan="3" align="right">Σύνολο : </td>  
             <td ><?php echo number_format($total, 2); ?> €</td>  
             <td></td>  
        </tr>  
        <tr>
            
            <td colspan="5">
             <?php 
                if (isset($_SESSION['shopping_cart'])):
                if (count($_SESSION['shopping_cart']) > 0):
             ?>
                <a href="userpagemap.php" class="button" id="checkout_btn">Συνέχεια</a>
             <?php endif; endif; ?>
            </td>
        </tr>
        <?php  
        endif;
        ?>  
        </table>  
         </div>
</div> 
   

       
       
        









