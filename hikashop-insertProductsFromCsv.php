<?php

require_once('db-connection.php');

$insert = false;
$update = false;

define("TAX_10", 11);
define("TAX_20", 12);

$myfile = fopen("NonExistingCategories.txt", "w+")  ;	
	
if (($handle = fopen("csv_files/item.csv", "r")) !== FALSE) {	
	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {	
	
	$product_name = $data[1];
	$product_barcode = $data[3];
	$product_transport_package = $data[5];
	$product_alias = $product_name;
	$product_code = $data[0];
	$product_keywords = $product_code;	
	$product_published = $data[14];	
	//$product_tax_id = $data[16];	
	$product_tax = $data[16];
	
	if($product_tax == '20,00') {
		$product_tax_id = TAX_20;
	} else if ($product_tax == '10,00') {
		$product_tax_id = TAX_10;
	}
		
	$product_weight_unit = $data[9];
	$product_type = "main";
	$product_dimension_unit = "m";
	$product_access = "all";
	$product_min_per_order = $data[5];
	$unix_time = time();
	$product_created = $unix_time;
	$product_modified = $unix_time;
	
	$category_keyword = $data[11];
	
	$stmt = $conn->prepare("SELECT * FROM jos_mdprodukt92_com_hikashop_product WHERE product_code = :product_code");
	$stmt->bindParam('product_code',$product_code);
	$stmt->execute();
	
	if ($stmt->rowCount() > 0) {
		$update = true;
		$insert = false;
	} else {
		$insert = true;
		$update = false;
	}
	
	//----------category_id
	$stmt = $conn->prepare("SELECT category_id FROM jos_mdprodukt92_com_hikashop_category WHERE category_keywords= :category_keyword");
	$stmt->bindParam('category_keyword', $category_keyword);
	$stmt->execute();
		
	$row = $stmt->fetch(PDO::FETCH_ASSOC);	
    $category_id = $row['category_id'];
	if(! $category_id) {

		$txt = "None existing category:".$category_keyword." for product with code:".$product_code."\r\n";
		fwrite($myfile, $txt);
		$insert = false;
		$update = false;
	}
	$product_manufacturer_id = $category_id;
	//---------------------
	
	if($insert) {
	//echo "inset";
	$stmt = $conn->prepare("INSERT INTO jos_mdprodukt92_com_hikashop_product(product_name, product_code,product_published,product_created,product_tax_id,product_type,
						   product_manufacturer_id,product_weight_unit,product_modified,product_dimension_unit,product_access,product_min_per_order,product_alias,
						   product_keywords,barcode,transport_package) 
						   VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
						   
	$stmt->bindParam(1, $product_name,PDO::PARAM_STR);
	$stmt->bindParam(2, $product_code,PDO::PARAM_INT);
	$stmt->bindParam(3, $product_published,PDO::PARAM_INT);
	$stmt->bindParam(4, $product_created,PDO::PARAM_INT);
	$stmt->bindParam(5, $product_tax_id,PDO::PARAM_STR);
	$stmt->bindParam(6, $product_type,PDO::PARAM_STR);
	$stmt->bindParam(7, $product_manufacturer_id,PDO::PARAM_STR);
	$stmt->bindParam(8, $product_weight_unit,PDO::PARAM_STR);
	$stmt->bindParam(9, $product_modified,PDO::PARAM_INT);
	$stmt->bindParam(10, $product_dimension_unit,PDO::PARAM_STR);
	$stmt->bindParam(11, $product_access,PDO::PARAM_STR);
	$stmt->bindParam(12, $product_min_per_order,PDO::PARAM_INT);
	$stmt->bindParam(13, $product_alias,PDO::PARAM_STR);
	$stmt->bindParam(14, $product_keywords,PDO::PARAM_INT);	
	$stmt->bindParam(15, $product_barcode,PDO::PARAM_STR);		
	$stmt->bindParam(16, $product_transport_package,PDO::PARAM_STR);		
						   	
	$stmt->execute();
				
	//----------MAX product_id	 
	$stmt = $conn->prepare("SELECT product_id FROM jos_mdprodukt92_com_hikashop_product ORDER BY product_id DESC limit 1");
	$stmt->execute();
		
	$row = $stmt->fetch(PDO::FETCH_ASSOC);	
    $max_product_id = $row['product_id'];
	//---------------------
	
	$sql_insert_product_category = "INSERT INTO jos_mdprodukt92_com_hikashop_product_category(category_id,product_id)
									VALUES(".$category_id.",".$max_product_id.")";	
				
	$conn->exec($sql_insert_product_category);
	
	$sql_insert_product_category = "INSERT INTO jos_mdprodukt92_com_hikashop_product_category(category_id,product_id)
									VALUES(2,".$max_product_id.")";	
	$conn->exec($sql_insert_product_category);		
	} //insert
	
	else if ($update) {
		echo "update";
		echo "product_barcode=".$product_barcode;
	$stmt = $conn->prepare("UPDATE jos_mdprodukt92_com_hikashop_product 
							SET product_name=?, product_published=?, product_created=?, product_tax_id=?, product_type=?,
							product_manufacturer_id=?, product_weight_unit=?,product_modified=?, product_dimension_unit=?, product_access=?, product_min_per_order=?, 
							product_alias=?, product_keywords=?, barcode=?, transport_package=? 
							WHERE product_code=?");	
					
    $stmt->bindParam(1, $product_name,PDO::PARAM_STR);
	$stmt->bindParam(2, $product_published,PDO::PARAM_INT);
	$stmt->bindParam(3, $product_created,PDO::PARAM_INT);
	$stmt->bindParam(4, $product_tax_id,PDO::PARAM_INT);
	$stmt->bindParam(5, $product_type,PDO::PARAM_STR);
	$stmt->bindParam(6, $product_manufacturer_id,PDO::PARAM_INT);
	$stmt->bindParam(7, $product_weight_unit,PDO::PARAM_STR);
	$stmt->bindParam(8, $product_modified,PDO::PARAM_INT);
	$stmt->bindParam(9, $product_dimension_unit,PDO::PARAM_INT);
	$stmt->bindParam(10, $product_access,PDO::PARAM_STR);
	$stmt->bindParam(11, $product_min_per_order,PDO::PARAM_INT);
	$stmt->bindParam(12, $product_alias,PDO::PARAM_STR);
	$stmt->bindParam(13, $product_keywords,PDO::PARAM_INT);
	$stmt->bindParam(14, $product_barcode,PDO::PARAM_INT);
	$stmt->bindParam(15, $product_transport_package,PDO::PARAM_STR);
	$stmt->bindParam(16, $product_code,PDO::PARAM_STR);
	
	$stmt->execute();	
	
	} //update
	
 }	
	
}
fclose($myfile);
echo "-- FINISH --";