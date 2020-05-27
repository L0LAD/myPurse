<?php
include('head.php');
include('function.php');
include('addKeyword.php');
include('addCategory.php');
include('deleteKeyword.php');

if(isset($_POST["newKeyword"])) {  
  $newKeyword = $_POST["newKeyword"];
  $categoryTable = "category_" .strtolower($_POST["category"]);
  $req1 = "INSERT INTO `mypurse`.`$categoryTable` (keyword) VALUES ('$newKeyword')";
  $ans1 = mysqli_query($conn, $req1);
  
  foreach ($dateList as $date) {
    setCategory($conn, $date);
  }
}

if(isset($_POST["editKeyword"])) {
  $currentKeyword = $_POST["currentKeyword"];
  $editKeyword = $_POST["editKeyword"];
  $categoryTable = "category_" .strtolower($_POST["category"]);
  $req1 = "UPDATE `mypurse`.`$categoryTable` SET keyword='$editKeyword' WHERE keyword='$currentKeyword'";
  $ans1 = mysqli_query($conn, $req1);

  foreach ($dateList as $date) {
    setCategory($conn, $date);
  }
}

if(isset($_POST["oldKeyword"])) {  
  $keyword = $_POST["oldKeyword"];
  $category = ucfirst($_POST["category"]);
  $categoryTable = "category_" .strtolower($category);

  $req1 = "DELETE FROM `mypurse`.`$categoryTable` WHERE keyword='$keyword'";
  $ans1 = mysqli_query($conn, $req1);
  
  foreach ($dateList as $date) {
    $req2 = "UPDATE `mypurse`.`$date` SET type='Trivia' WHERE type='$category'";
    $ans2 = mysqli_query($conn, $req2);
  }  
}

if(isset($_POST["newCategory"])) {  
  $categoryName = ucfirst($_POST["newCategory"]);
  $color = $_POST["color"];
  $icon = $_POST["icon"];

  //Creation of the category table
  $tableName = "category". "_" .strtolower($categoryName);
  $req1 = "CREATE TABLE `mypurse`.`$tableName` (`id` INT NOT NULL AUTO_INCREMENT, `keyword` VARCHAR(255) NOT NULL, PRIMARY KEY (`id`)) ENGINE = MyISAM";
  $ans1 = mysqli_query($conn, $req1); 
  echo "<br><br><br>" .$req1;

  //Adding the category to the categories table
  $req2 = "INSERT INTO `mypurse`.`categories`(`category`,`color`,`icon`) VALUES ('$categoryName','$color','$icon')";
  $ans2 = mysqli_query($conn, $req2); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
  <?php include("navbar.php"); ?>
  <div id="layoutSidenav">
    <?php include("sidebar.php"); ?>
    <div id="layoutSidenav_content">
      <main>
        <div class="container-fluid">
          <div class="row title">
            <h1>Categories</h1>
          </div>
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <?php
              foreach ($categoryList as $element) {
                $categoryName = ucfirst($element['category']);
                if ($element == $categoryList[0]) {
                  echo "<a class='nav-item nav-link active' data-toggle='tab' href='#" .$categoryName. "Tab'>" .ucfirst($categoryName). "</a>";
                } else {
                  echo "<a class='nav-item nav-link' data-toggle='tab' href='#" .$categoryName. "Tab'>" .ucfirst($categoryName). "</a>";
                }
              }
              ?>
              <button id="addCategoryBefore" type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#addCategoryModal">&#43;</button> 
            </div>
          </nav>

          <div class="tab-content table-responsive" id="nav-tabContent">
            <?php
            foreach ($categoryList as $element) {    
              $categoryName = $element['category'];   
              $tableName = "category_" .strtolower($categoryName);
              $idEdit = strtolower($categoryName). "Edit";
              $idDelete = strtolower($categoryName). "Edit";
              if ($element == $categoryList[0]) {
                echo "<div id='" .$categoryName. "Tab' class='tab-pane fade in card mb-4 shadow show active'>";
              } else {
                echo "<div id='" .$categoryName. "Tab' class='tab-pane fade in card mb-4 shadow'>";
              }
              ?>
              <div class="card-header">
                <?php echo ucfirst($categoryName); ?>
                <button id='$idEdit' class='btn btn-warning' data-toggle='modal' data-target='#addCategoryModal'>Edit</button>
                <button id='$idDelete' class='btn btn-warning' data-toggle='modal' data-target='#deleteCategoryModal'>Delete</button>
              </div>
              <div class="card-body">
                <table id="table" class="table table-sm table-bordered table-striped" cellspacing="0">
                  <thead>
                    <th>Keywords
                      <button id="addKeywordBefore" type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#addKeywordModal" onclick="setCurrentCategory()">&#43;</button>  
                    </th>
                  </thead>
                  <tbody>
                    <?php
                    $reqKeyword = "SELECT * FROM $tableName ORDER BY keyword";
                    $ansKeyword = $conn->query($reqKeyword);
                    if ($ansKeyword) {
                      while ($row = $ansKeyword->fetch_assoc()) {
                        $keyword = $row['keyword'];
                        $id = $row['id'];
                        $idRow = strtolower($categoryName) . $id. "Row";
                        $idEdit = $idRow. "Edit";
                        $idDelete = $idRow. "Delete";
                        echo "<tr id='$idRow' name='$keyword' class='keyword-row'><td>
                        " .$keyword. "
                         <a id='$idEdit' class='fas fa-pencil-alt link-icon' data-toggle='modal' data-target='#addKeywordModal'></a>
                         <a id='$idDelete' class='fas fa-times link-icon' data-toggle='modal' data-target='#deleteKeywordModal'></a>
                        </td></tr>";
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <?php
              echo "</div>";
            }
            ?>
          </div>
        </div>
        <?php include('script.php'); ?>

      </main>
      <footer>
        <?php include("footer.php"); ?>
      </footer>
    </div>
  </div>
</body>

<script>

//Click to open the modal creating a new keyword

var currentCategory;
var currentKeyword;

$("#addKeywordBefore").click(function() {
  $("#keywordField").val("");
  $("#addKeywordAfter").text("Add");
  $("#keywordModalTitle").text("New keyword");
  $("#addKeywordAfter").prop("disabled", true);
});

$(".keyword-row").on({
  mouseenter : function(){
    var idRow = $(this).attr('id');
    $("#"+idRow+"Edit").css('visibility', 'visible');
    $("#"+idRow+"Delete").css('visibility', 'visible');
  },
  mouseleave : function(){
    var idRow = $(this).attr('id');
    $("#"+idRow+"Edit").css('visibility', 'hidden');
    $("#"+idRow+"Delete").css('visibility', 'hidden');
  },
  click : function(){
    var idRow = $(this).attr('id');
    var keyword = $(this).text().trim();
    setCurrentCategory();
    setCurrentKeyword(this);
    $("#keywordField").val(keyword);
    $("#addKeywordAfter").text("Edit");
    $("#keywordModalTitle").text("Edit keyword");
    $("#addKeywordAfter").prop("disabled", false);
  }
});

//Click to open the modal creating a new category
$("#addCategoryBefore").click(function() {
  $("#categoryField").val("");
  $("#iconField").val("");
  $("#colorPicker").val("#000");
  $("#addCategoryAfter").text("Add");
  $("#categoryModalTitle").text("New category");
  $("#addCategoryAfter").prop("disabled", true);
});

//Click to open the modal editing a category
$(".card-header").click(function() {
  setCurrentCategory();
  var categoryName = getCurrentCategory();
  var categoryTable = <?php echo json_encode($categoryList); ?>;
  for (var i = 0; i < categoryTable.length; i++) {
    if (categoryTable[i]['category'] == categoryName) {
      var color = categoryTable[i]['color'];
      var icon = categoryTable[i]['icon'];
    };
  };
  $("#categoryField").val(categoryName);
  $("#colorPicker").val(color);
  $("#iconField").val(icon);
  $("#addCategoryAfter").text("Edit");
  $("#categoryModalTitle").text("Edit category");
  $("#addCategoryAfter").prop("disabled", false);
});

$(document).ready(function(){
  $('#addKeywordAfter').click(function(){
    if ($(this).text()=="Add") {
      var newKeyword = $('#keywordField').val();
      var category = getCurrentCategory();
      if(newKeyword != '') {
        $.ajax({
          url:"categories.php",
          method:"POST",
          data: {newKeyword:newKeyword, category:category},
          success:function(data) {
            if(data != 'No') {
              $('#addKeywordModal').hide();
              currentKeyword = "";
              location.reload();
            }  
          }  
        });  
      }
    } else if ($(this).text()=="Edit") {
      var editKeyword = $('#keywordField').val();
      var currentKeyword = getCurrentKeyword();
      var category = getCurrentCategory();
      if(editKeyword != '') {
        $.ajax({
          url:"categories.php",
          method:"POST",
          data: {editKeyword:editKeyword, currentKeyword:currentKeyword, category:category},
          success:function(data) {
            if(data != 'No') {
              $('#addKeywordModal').hide();
              location.reload();
            }  
          }  
        });  
      }
    }
  });

  $('#addCategoryAfter').click(function(){
    var newCategory = $('#categoryField').val();
    var color = $('#colorPicker').val();
    var icon = $('#iconField').val();
    if(newCategory!='' && color!='' && icon!='') {
      $.ajax({
        url:"categories.php",
        method:"POST",
        data: {newCategory:newCategory, color:color, icon:icon},
        success:function(data) {
          if(data != 'No') {
            $('#addCategoryModal').hide();
            location.reload();
          }
        }
      });
    }
  });
});

$('#deleteKeywordButton').click(function(){
    var oldKeyword = $("[name='currentKeyword']").text();
    var category = document.getElementsByClassName("active")[1].innerText;
    if(oldKeyword != '') {
      $.ajax({
        url:"categories.php",
        method:"POST",
        data: {oldKeyword:oldKeyword, category:category},
        success:function(data) {
          if(data != 'No') {
            $('#deleteKeywordModal').hide();
            location.reload();
          }  
        }  
      });  
    } 
  });

function fields_valid(type) {
  if (type=="category") {
    var requiredList = <?php echo json_encode($categoryRequiredList); ?>;
  } else if (type=="keyword") {
    var requiredList = <?php echo json_encode($keywordRequiredList); ?>;
  }

  var valid = true;
  for (var i = 0; i < requiredList.length; i++) {
    var inputID = requiredList[i];
    var value = document.getElementById(inputID).value;
    if (value == '' || value == ' ' || value == null) {
      valid = false;
      break;
    } else if (inputID == 'keywordField') {
      console.log(value);
    } else if (inputID == 'categoryField') {
      console.log(value);
    }
  }

  if (valid) {
    if (type=="category") {
      document.getElementById('addCategoryAfter').disabled = false;
    } else if (type=="keyword") {
      document.getElementById('addKeywordAfter').disabled = false;
    }
  } else {
    if (type=="category") {
      document.getElementById('addCategoryAfter').disabled = true;
    } else if (type=="keyword") {
      document.getElementById('addKeywordAfter').disabled = true;
    }
  }
}

function openTab(tab) {
  var i;
  var x = document.getElementsByClassName("city");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  document.getElementById(tab).style.display = "block";  
}

function getCurrentCategory() {
  return currentCategory;
}

function setCurrentCategory() {
  currentCategory = document.getElementsByClassName("nav-item nav-link active")[0].innerText;
  $("[name='currentCategory']").text(currentCategory);
}

function getCurrentKeyword() {
  return currentKeyword;
}

function setCurrentKeyword(row) {
  currentKeyword = $(row).attr('name');
  $("[name='currentKeyword']").text(currentKeyword);
}
</script>

</html>
