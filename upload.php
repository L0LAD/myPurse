<?php
include("head.php");
include("script.php");
include("function.php");

$uploadAlert = "";

if (isset($_FILES['userfile'])) {

    $phpFileUploadErrors = array(
        0 => 'There is no error, the file uploaded with success.',
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
        3 => 'The uploaded file was only partially uploaded.',
        4 => 'No file was uploaded.',
        6 => 'Missing a temporary folder.',
        7 => 'Failed to write file to disk.',
        8 => 'A PHP extension stopped the file upload.'
    );

    $extensions = array('csv');
    $fileExt = explode('.',$_FILES['userfile']['name']);
    $fileExt = end($fileExt);

    if ($_FILES['userfile']['error']) {
        $uploadAlert = $phpFileUploadErrors[$_FILES['userfile']['error']];
        $alertClass = "alert-warning";
    } elseif (!in_array($fileExt, $extensions)) {
        $uploadAlert = "Invalid file extension !";
        $alertClass = "alert-warning";
    } else {
        $uploadAlert = "The file has been successfully uploaded.";
        $alertClass = "alert-success";
    }

    //Move the file into the local WAMP folder
    $directory = 'C:\wamp64\tmp\ '.$_FILES['userfile']['name'];
    move_uploaded_file($_FILES['userfile']['tmp_name'], $directory);
    $address = addslashes($directory);

    //Generate the name of the table
    $selectedMonth = array_search($_POST['month'], $monthList);
    $selectedYear = $_POST['year'];
    $newTable = $selectedYear. "_" .$selectedMonth;

    $alreadyExist = false;
    $alreadyImport = false;
    $selectedAccount = utf8_decode($_POST['account']);
    $req0 = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'mypurse' AND table_name LIKE '20%%'";
    $ans0 = $conn->query($req0);
    while ($row0 = $ans0->fetch_assoc()) {
        if ($row0['table_name']==$newTable) {
            $alreadyExist = true;
            $req01 = "SELECT account FROM `mypurse`.`$newTable` GROUP BY account";
            $ans01 = $conn->query($req01);
            while ($row1 = $ans01->fetch_assoc()) {
                if ($row1['account']==$selectedAccount) {
                    $alreadyImport = true;
                }
            }
        }
    }
    if ($alreadyExist==false) {
        $req1 = "CREATE TABLE `mypurse`.`$newTable` (`id` INT NOT NULL AUTO_INCREMENT , `account` VARCHAR(100) NOT NULL, `date` VARCHAR(25) NOT NULL , `source` VARCHAR(255) NOT NULL , `label` VARCHAR(255) NOT NULL , `amount` VARCHAR(10) NOT NULL , `type` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = MyISAM";
        $ans1 = $conn->query($req1);

        $req2 = "LOAD DATA INFILE '$address' INTO TABLE `mypurse`.`$newTable` FIELDS TERMINATED BY ';' IGNORE 3 LINES (`date`,`source`,`label`,`amount`)";
        $ans2 = $conn->query($req2);
    } elseif ($alreadyExist==true && $alreadyImport==false) {
        $req2 = "LOAD DATA INFILE '$address' INTO TABLE `mypurse`.`$newTable` FIELDS TERMINATED BY ';' IGNORE 3 LINES (`date`,`source`,`label`,`amount`)";
        $ans2 = $conn->query($req2);
    } elseif ($alreadyExist==true && $alreadyImport==true) {
        $req1 = "DELETE FROM `mypurse`.`$newTable` WHERE account='$selectedAccount'";
        $ans1 = $conn->query($req1);

        $req2 = "LOAD DATA INFILE '$address' INTO TABLE `mypurse`.`$newTable` FIELDS TERMINATED BY ';' IGNORE 3 LINES (`date`,`source`,`label`,`amount`)";
        $ans2 = $conn->query($req2);
    }

    unlink($address);

    //Fill the 'account' column in the table
    $req3 = "UPDATE `mypurse`.`$newTable` SET account='$selectedAccount' WHERE account=''";
    $ans3 = $conn->query($req3);

    //Fill the 'type' column in the table
    $req4 = "UPDATE `mypurse`.`$newTable` SET type='Trivia' WHERE type=''";
    $ans4 = $conn->query($req4);
    setCategory($conn, $newTable);
    
}

?>

<!DOCTYPE html>
<html lang="en">
<body class="sb-nav-fixed">
    <?php include("navbar.php"); ?>
    <div id="layoutSidenav">
        <?php include("sidebar.php"); ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <div class="row title">
                        <h1>Upload</h1>
                    </div>

                    <?php
                    if ($uploadAlert) {
                        echo "<div class='alert " .$alertClass. "' role='alert'>";
                        echo $uploadAlert;
                        echo "</div>";
                    }
                    ?>

                    <form action="" method="post" enctype="multipart/form-data">
                        Select a CSV file:
                        <input class="btn btn-outline-secondary shadow-sm" type="file" name="userfile" id="userfile" onChange="fields_valid()">

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="monthSelect">Month</label>
                                <select id="monthSelect" name="month" class="form-control" onChange="fields_valid()">
                                    <option value="empty"></option>
                                    <?php
                                    foreach ($monthList as $month) {
                                        echo "<option value='$month'>$month</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="yearSelect">Year</label>
                                <select id="yearSelect" name="year" class="form-control" onChange="fields_valid()">
                                    <option value="empty"></option>
                                    <?php
                                    $currentYear = date("Y");
                                    for ($year=$currentYear; $year > 2010; $year--) { 
                                        echo "<option value='$year'>$year</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="accountSelect">Account</label>
                                <select id="accountSelect" name="account" class="form-control" onChange="fields_valid()">
                                    <option value="empty"></option>
                                    <?php
                                    $reqAccount = "SELECT * FROM `mypurse`.`accounts`";
                                    $ansAccount = $conn->query($reqAccount);
                                    while ($row = $ansAccount->fetch_assoc()) {
                                        $account = $row['account'];
                                        echo "<option value='" .utf8_encode($account). "'>" .utf8_encode($account). "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <input id="submitButton" name="submit" type="submit" class="btn btn-success shadow-sm" value="Upload" disabled>
                    </form>
                </div>

            </main>
            <footer>
                <?php include("footer.php"); ?>
            </footer>
        </div>
    </div>

    <script>
    function fields_valid() {
        var file = false;
        if($('#userfile').val().length){
            file = true;
        }
        var monthSelect = document.getElementById('monthSelect');
        var month = monthSelect.options[monthSelect.selectedIndex].value;
        var yearSelect = document.getElementById('yearSelect');
        var year = yearSelect.options[yearSelect.selectedIndex].value;
        var accountSelect = document.getElementById('accountSelect');
        var account = accountSelect.options[accountSelect.selectedIndex].value;
        if (file!=false && month!='empty' && year!='empty' && account!='empty') {
            document.getElementById('submitButton').disabled = false;
        }
    }
    </script>

    <?php include('script.php'); ?>
</body>