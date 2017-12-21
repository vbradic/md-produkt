<?php
/*
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
	
if (($handle = fopen("csv_files/brand.csv", "r")) !== FALSE) {	
	while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
		
		$stmt = $conn->prepare("SELECT category_left FROM jos_mdprodukt92_com_hikashop_category ORDER BY category_left DESC limit 1");
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$max_category_left = $row['category_left'];
	
		$category_left = $max_category_left + 2;
		$category_right = $category_left + 1;
		
		$unix_time = time();
		$random_appender = round($unix_time/8) + rand(100,1000);
		
		$category_namekey = "manufacturer_".$unix_time."_".$random_appender;	
		
		$stmt = $conn->prepare("SELECT category_ordering FROM jos_mdprodukt92_com_hikashop_category ORDER BY category_ordering DESC limit 1");
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$max_ordering = $row['category_ordering']+1;
		
		$category_name = $data[1];
		$category_keywords = $data[0];
		
		// provera da li u bazi postoji kategorija sa $category_keyword, ako da -> preskoci upis
		
		
		$sql_insert = "INSERT into jos_mdprodukt92_com_hikashop_category (category_type, category_parent_id, category_depth
				,category_published , category_left, category_right, category_ordering,category_namekey,category_created, category_modified, category_name, category_alias,category_keywords)
				VALUES('manufacturer', 10, 2, 1,".$category_left.",".$category_right.", ".$max_ordering.",'".$category_namekey ."' ,".$unix_time.", ".$unix_time.", '".$category_name."','".$category_name."',".$category_keywords.")";
				
			//echo $sql_insert;
			//exit;
	
		$conn->exec($sql_insert);
	
		//$root_category_right = $root_category_right + 2;
	
		//echo $root_category_right;
		
		$stmt = $conn->prepare("SELECT category_right FROM jos_mdprodukt92_com_hikashop_category ORDER BY category_left DESC limit 1");
		$stmt->execute();
		
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		$category_right_max = $row['category_right'];
	
		$sql_update = "UPDATE jos_mdprodukt92_com_hikashop_category SET category_right=".$category_right_max." WHERE category_id = 1";
		$conn->exec($sql_update);
		
		$sql_update_manufacturer = "UPDATE jos_mdprodukt92_com_hikashop_category SET category_right=".$category_right_max." WHERE category_id = 10";
		$conn->exec($sql_update_manufacturer);
		
		//sleep(1);
	
	}
}	
	