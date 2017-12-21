<?php

require_once('db-connection.php');	
require_once('functions.php');
	
$price_currency_id = 136; 
$price_access = "all";

$insert = false;
$update = false;
	
if (($handle = fopen("csv_files/singleLine.csv", "r")) !== FALSE) {	
	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		echo "csv";
	$product_code = $data[0];
	
	$product_fabric_price = $data[1];
	
	$rebate_for_customer_a = $data[2];
	$rebate_for_customer_b = $data[3];
	$rebate_for_customer_c = $data[4];
	$rebate_for_customer_d = $data[5];
	$rebate_for_customer_e = $data[6];
	
	$customer_a_id = 11;
	$customer_b_id = 12;
	$customer_c_id = 13;
	$customer_d_id = 14;
	$customer_e_id = 15;
	
	$price_value_a = calculate_discount_price($product_fabric_price,$rebate_for_customer_a);
	$price_value_b = calculate_discount_price($product_fabric_price,$rebate_for_customer_b);
	$price_value_c = calculate_discount_price($product_fabric_price,$rebate_for_customer_c);
	$price_value_d = calculate_discount_price($product_fabric_price,$rebate_for_customer_d);
	$price_value_e = calculate_discount_price($product_fabric_price,$rebate_for_customer_e);

	$stmt = $conn->prepare("SELECT * FROM jos_mdprodukt92_com_hikashop_product WHERE product_code= :product_code");
	$stmt->bindParam('product_code', $product_code);
	$stmt->execute();
	
	if ($stmt->rowCount() > 0) {
	
		$row = $stmt->fetch(PDO::FETCH_ASSOC);	
		$price_product_id = $row['product_id'];
	}
	
	if($price_product_id) {
	//check if price for product exists	
	$stmt = $conn->prepare("SELECT * FROM jos_mdprodukt92_com_hikashop_price WHERE price_product_id= :price_product_id");
	$stmt->bindParam('price_product_id', $price_product_id);
	$stmt->execute();
	
	if ($stmt->rowCount() > 0) {
		$update = true;
		$insert = false;
	} else {
		$insert = true;
		$update = false;
	}
	
	if($insert) {
		echo "insert";
		$stmt = $conn->prepare("INSERT INTO jos_mdprodukt92_com_hikashop_price(price_currency_id, price_product_id, price_value, price_access,fabric_price,
								rebate_per_contract)
							VALUES(?,?,?,?,?,?)");
		
		
		for($customer=0;$customer<5;$customer++) {
											
		switch((string)$customer) {
			
			case "0" :
				$price_value = $price_value_a;
				$price_access = $customer_a_id;
				$rebate_per_contract = $rebate_for_customer_a;
				break;
				
			case "1" :
				$price_value = $price_value_b;
				$price_access = $customer_b_id; 
				$rebate_per_contract = $rebate_for_customer_b;
				break;
				
			case "2" :
				$price_value = $price_value_c;
				$price_access = $customer_c_id;
				$rebate_per_contract = $rebate_for_customer_c;
				break;
			
			case "3" :
				$price_value = $price_value_d;
				$price_access = $customer_d_id;
				$rebate_per_contract = $rebate_for_customer_d;
				break;
			
			case "4" :
				$price_value = $price_value_e;
				$price_access = $customer_e_id;
				$rebate_per_contract = $rebate_for_customer_e;
				break;
			
		}
	
		$stmt->bindParam(1, $price_currency_id,PDO::PARAM_INT);
		$stmt->bindParam(2, $price_product_id,PDO::PARAM_INT);
		$stmt->bindParam(3, $price_value,PDO::PARAM_INT);
		$stmt->bindParam(4, $price_access,PDO::PARAM_STR);
		$stmt->bindParam(5, $product_fabric_price,PDO::PARAM_STR);
		$stmt->bindParam(6, $rebate_per_contract,PDO::PARAM_STR);
		//$stmt->bindParam(7, $price_access,PDO::PARAM_STR);
		//$stmt->bindParam(8, $price_access,PDO::PARAM_STR);
	echo "execute;";
		$stmt->execute();
		}
	} else if ($update) {
		
		
		
		$stmt = $conn->prepare("UPDATE jos_mdprodukt92_com_hikashop_price 
								SET price_value=?
								WHERE price_product_id=?");
								
								
		for($customer=0;$customer<5;$customer++) {		

		switch($customer) {
			
			case 0 :
				$price_value = $price_value_a;
				$price_access = $customer_a_id;
				break;
				
			case 1 :
				$price_value = $price_value_b;
				$price_access = $customer_b_id; 
				break;
				
			case 2 :
				$price_value = $price_value_c;
				$price_access = $customer_c_id;
				break;
			
			case 3 :
				$price_value = $price_value_d;
				$price_access = $customer_d_id;
				break;
			
			case 4 :
				$price_value = $price_value_e;
				$price_access = $customer_e_id;
				break;
			
		}		
								
		$stmt->bindParam(1, $price_value,PDO::PARAM_INT);
		$stmt->bindParam(2, $price_product_id,PDO::PARAM_INT);
		
		$stmt->execute();
		
		}
		
	}
   }
 }
}

echo "FINISH ------";