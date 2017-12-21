<?php

$dsn = "mysql:host=mysql531.loopia.se:3306/;dbname=mdprodukt92_com;charset=utf8";
$username = "joomla@m38472";
$password = "kcqfovkqh8ckym39";

ini_set('max_execution_time', 300);

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
    } catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
    }
	
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