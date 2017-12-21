<?php

require_once('db-connection.php');	
require_once('functions.php');
	
$stmt = $conn->prepare("SELECT * FROM jos_mdprodukt92_com_hikashop_product");
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
 // print "<p>Name: ".$row[2]."; id =".$row[0]."</p> \n";
 
    $sql_insert_files = "INSERT INTO jos_mdprodukt92_com_hikashop_file(file_name,file_path,file_type,file_ref_id)
						VALUES('".$row[5]."','".$row[5].".jpg','product',".$row[0].")";
						//echo $sql_insert_files;
						//exit;
	$conn->exec($sql_insert_files);
 
 
}