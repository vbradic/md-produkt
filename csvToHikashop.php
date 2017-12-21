<?php

$dsn = "mysql:host=mysql531.loopia.se:3306/;dbname=mdprodukt92_com";
$username = "joomla@m38472";
$password = "kcqfovkqh8ckym39";

try {
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully"; 
    } catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
    }
	
	$row = 1;
	
if (($handle = fopen("test.csv", "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
    $num = count($data);
    echo "<p> $num fields in line $row: <br /></p>\n";
    $row++;
   
    for ($c=0; $c < $num; $c++) {
        echo $data[$c] . "<br />\n";// ovde upis u Hikashop
	}
		
//		$sql = "INSERT INTO MyGuests (firstname, lastname, email) //sql
//    VALUES ('John', 'Doe', 'john@example.com')";
	
//		$conn->exec($sql);

  }
  fclose($handle);
}
	
	
	
	
	
	$sql_insert = "INSERT into jos_mdprodukt92_com_hikashop_category (category_type, category_parent_id, category_depth
				,category_published , category_left, category_right, category_ordering,category_namekey,category_created, category_modified, category_name, category_alias, category_keywords)
				VALUES('manufacturer', 10, 2, 1,".$category_left.",".$category_right.", 2,        ,1508864060, 1508864060, '".$data[1]."','".$data[1]."',".$data[0].")";
	

	
?>