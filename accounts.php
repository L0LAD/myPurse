<?php
include("head.php");
include("action.php");
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
                        <h1>Accounts</h1>
                    </div>
                    <div class="tab-content table-responsive" id="nav-tabContent">
                        <table id="accountTable" class="table table-sm table-bordered table-striped" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Accounts
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#accountModal" onclick="setModalTitle()">&#43;</button>  
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $reqAccount = "SELECT * FROM accounts ORDER BY account";
                                $ansAccount = $conn->query($reqAccount);
                                if ($ansAccount->num_rows > 0) {
                                    while ($row = $ansAccount->fetch_assoc()) {
                                        echo "<tr><td>" .utf8_encode($row['account']). "</td></tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
            <footer>
                <?php include("footer.php"); ?>
            </footer>
        </div>
    </div>
    <?php include('script.php'); ?>
</body>

<script>
$(document).ready(function() {
    $('#addAccountButton').click(function() {  
        window.location.reload(true);
        var account = $('#account').val();  
        if(account != '') {  
            $.ajax({  
                url:"action.php",  
                method:"POST",  
                data: {account:account},  
                success:function(data) {  
                    if(data == 'No') {  
                        alert("Wrong Data");  
                    }  
                    else {  
                        $('#accountModal').hide();  
                        location.reload();  
                    }  
                }  
            });  
        }  
        else {  
            alert("Both Fields are required");  
        }  
    }); 
});

function setModalTitle() {
    tabName = document.getElementsByClassName("active")[1].innerText;
    document.getElementById("modalTitle").textContent = tabName;
}
</script>

</html>
