<?php

include('head.php');

$dateList = [];
$reqDate = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'mypurse' AND table_name LIKE '20%_%'";
$ansDate = mysqli_query($conn, $reqDate);
while ($row = $ansDate->fetch_assoc()) {
	array_push($dateList, $row['table_name']);
}

if(isset($_POST["newKeyword"])) {  
	$keyword = $_POST["newKeyword"];
	$categoryTable = "category_" .strtolower($_POST["category"]);
	$req1 = "INSERT INTO `mypurse`.`$categoryTable` (keyword) VALUES ('$keyword')";
	$ans1 = mysqli_query($conn, $req1);
	
	foreach ($dateList as $date) {
		/*setCategory($conn, $date);*/
	    $reqType = "SELECT * FROM `mypurse`.`$date` WHERE amount<0";
	    $ansType = $conn->query($reqType);
	    while ($row = $ansType->fetch_assoc()) {
	        $id = $row['id'];
	        $label = $row['label'];
	        $type = $row['type'];
	        if ($type=='Trivia') {
	        	$a = $categoryList[1]['color'];
			    $reqA = "INSERT INTO `category_catest` (`keyword`) VALUES ('$b')";
			    $ansA = $conn->query($reqA);
	            foreach ($categoryList as $category) {
	                $categoryName = $category['category'];
	                foreach ($keywordList[$categoryName] as $keyword) {
	                    if (strpos(strtolower($label), strtolower($keyword)) !== false) {
	                        $req5 = "UPDATE `mypurse`.`$date` SET type='$categoryName' WHERE id=$id";
	                        $ans5 = $conn->query($req5);
	                        break;
	                    }
	                }
	            }
	        }                    
	    }
	}
}

if(isset($_POST["newCategory"])) {  
	$categoryName = strtolower($_POST["newCategory"]);
	$color = $_POST["color"];
	$icon = $_POST["icon"];

	//Creation of the category table
	$tableName = "category". "_" .$categoryName;
	$req1 = "CREATE TABLE `mypurse`.`$tableName` ( `id` INT NOT NULL AUTO_INCREMENT, `keyword` VARCHAR(255) NOT NULL, PRIMARY KEY (`id`)) ENGINE = MyISAM";
	$ans1 = mysqli_query($conn, $req1); 

	//Adding the category to the categories table
	$req2 = "INSERT INTO `mypurse`.`categories`(`category`,`color`,`icon`) VALUES ('$categoryName','$color','$icon')";
	$ans2 = mysqli_query($conn, $req2); 
}

?>  
