<?php

$dateList = getDates($conn);
function getDates($conn) {
    $dateList = [];
    $reqDate = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'mypurse' AND table_name LIKE '20%_%'";
    $ansDate = mysqli_query($conn, $reqDate);
    while ($row = $ansDate->fetch_assoc()) {
      array_push($dateList, $row['table_name']);
    }
    return $dateList;
}

$accountList = getAccounts($conn);
function getAccounts($conn) {
    $accountList = [];
    $reqAccount = "SELECT * FROM `mypurse`.`accounts`";
    $ansAccount = $conn->query($reqAccount);
    if ($ansAccount->num_rows > 0) {
        while ($row = $ansAccount->fetch_assoc()) {
            $account = $row['account'];
            array_push($accountList, $account);
        }
    }
    return $accountList;
}

$categoryDetailList = getCategories($conn);
$categoryList = $categoryDetailList['category'];
$colorList = $categoryDetailList['color'];
$keywordList = $categoryDetailList['keyword'];

function getCategories($conn) {
    $array = [];
    $categoryList = [];
    $colorList = [];
    $keywordList = [];
    $reqCategory = "SELECT * FROM `mypurse`.`categories` ORDER BY `category`";
    $ansCategory = $conn->query($reqCategory);
    if ($ansCategory->num_rows > 0) {
        while ($row = $ansCategory->fetch_assoc()) {
            $category = $row['category'];
            $color = $row['color'];
            $icon = $row['icon'];
            $element = array('category' => $category, 'color' => $color, 'icon' => $icon);
            array_push($categoryList, $element);
            array_push($colorList, $color);

            $keywordList[$category] = getKeywords($conn, $category);
        }
    }
    $array['category'] = $categoryList;
    $array['color'] = $colorList;
    $array['keyword'] = $keywordList;
    return $array;
}

function getKeywords($conn, $category) {
    $categoryTable = "category_" .strtolower($category);
    $reqKeyword = "SELECT * FROM `mypurse`.`$categoryTable`";
    $ansKeyword = $conn->query($reqKeyword);
    $list = [];
    array_push($list, $category);
    if ($ansKeyword) {
        while ($row = $ansKeyword->fetch_assoc()) {
            array_push($list, $row['keyword']);
        };
    }
    return $list;
}

function setCategory($conn, $table) {
    $categoryDetailList = getCategories($conn);
    $categoryList = $categoryDetailList['category'];
    $keywordList = $categoryDetailList['keyword'];
    $reqType = "SELECT * FROM `mypurse`.`$table` WHERE amount<0";
    $ansType = $conn->query($reqType);
    while ($row = $ansType->fetch_assoc()) {
        $id = $row['id'];
        $label = $row['label'];
        $type = $row['type'];
        if ($type=='Trivia') {
            foreach ($categoryList as $category) {
                $categoryName = ucfirst($category['category']);
                foreach ($keywordList[$categoryName] as $keyword) {
                    if (strpos(strtolower($label), strtolower($keyword)) == true) {
                        $req5 = "UPDATE `mypurse`.`$table` SET type='$categoryName' WHERE id=$id";
                        $ans5 = $conn->query($req5);
                        break;
                    }
                }
            }
        }                    
    }
}

?>