<?php

include('head.php');
include('script.php');
include('function.php');

if (isset($_GET['table'])) {
    $currentTable = $_GET['table'];
}

$pieArray = array(array("Category","Amount"));

foreach ($categoryList as $element) {
    $category = $element['category'];
    $reqTotal = "SELECT -SUM(amount) AS sum FROM $currentTable WHERE type='$category' AND amount<0";
    $ansTotal = mysqli_query($conn, $reqTotal);
    $total = (int)mysqli_fetch_array($ansTotal)['sum'];
    array_push($pieArray, array($category,$total));
}

list($selectedYear, $selectedMonth) = explode("_", $currentTable);

$reqExpense = "SELECT * FROM $currentTable WHERE amount<0 ORDER BY date";
$ansExpense = $conn->query($reqExpense);

$reqGain = "SELECT * FROM $currentTable WHERE amount>0 ORDER BY date";
$ansGain = $conn->query($reqGain);

$reqExpenseSum = "SELECT SUM(amount) AS sum FROM $currentTable WHERE amount<0";
$ansExpenseSum = mysqli_query($conn, $reqExpenseSum);
$expense = mysqli_fetch_array($ansExpenseSum)['sum'];

$reqGainSum = "SELECT SUM(amount) AS sum FROM $currentTable WHERE amount>0";
$ansGainSum = $conn->query($reqGainSum);
$gain = mysqli_fetch_array($ansGainSum)['sum'];

$total = $gain + $expense;
if ($total>0) {
    $total = "+" .$total;
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
                <div id="accountLayout" class="container-fluid">
                    <div class="row title">
                        <h1><?php echo $monthList[$selectedMonth]. " " .$selectedYear;?></h1>
                        <button type="button" class="btn btn-sm btn-info shadow-sm" data-toggle="modal" data-target="#sendModal"><i class="fas fa-envelope"></i></button> 
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary shadow-sm text-white mb-4">
                                <div class="card-body"><?php echo $total; ?> €</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#" data-toggle="collapse" data-target="#pieChart">Total</a>
                                    <div class="small text-white"><i class="fas fa-angle-down"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success shadow-sm text-white mb-4">
                                <div class="card-body">+<?php echo $gain; ?> €</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#" data-toggle="collapse" data-target="#gainCard">Gains</a>
                                    <div class="small text-white"><i class="fas fa-angle-down"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning shadow-sm text-white mb-4">
                                <div class="card-body"><?php echo $expense; ?> €</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#" data-toggle="collapse" data-target="#expenseCard">Expenses</a>
                                    <div class="small text-white"><i class="fas fa-angle-down"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="pieChart" class="card mb-4 shadow collapse show" data-parent="#accountLayout"></div>

                    <div id="gainCard" class="card mb-4 shadow collapse" data-parent="#accountLayout">
                        <div class="card-header">Gains</div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered" id="gainTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Source</th>
                                            <th>Label</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class='font-sm'>
                                     <?php
                                     while ($operation = $ansGain->fetch_assoc()) {
                                        $date = $operation['date'];
                                        $source = $operation['source'];
                                        $label = $operation['label'];
                                        $amount = $operation['amount'];

                                        echo "<tr>";
                                        echo "<td>" .$date. "</td>";
                                        echo "<td>" .$source. "</td>";
                                        echo "<td>" .$label. "</td>";
                                        echo "<td>" .$amount. "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="expenseCard" class="card mb-4 shadow collapse" data-parent="#accountLayout">
                    <div class="card-header">Expenses</div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="expenseTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Source</th>
                                        <th>Label</th>
                                        <th>Amount</th>
                                        <th>Category</th>
                                    </tr>
                                </thead>
                                <tbody class='font-sm'>
                                 <?php
                                 while ($operation = $ansExpense->fetch_assoc()) {
                                    $id = $operation['id'];
                                    $date = $operation['date'];
                                    $source = $operation['source'];
                                    $label = $operation['label'];
                                    $amount = $operation['amount'];
                                    $type = $operation['type'];

                                    echo "<tr class='tr-" .strtolower($type). "'>";
                                    echo "<td>" .$date. "</td>";
                                    echo "<td>" .$source. "</td>";
                                    echo "<td>" .$label. "</td>";
                                    echo "<td>" .$amount. "</td>";
                                    echo "<td>" .$type. "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="sendModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalTitle" name="modalTitle"><?php echo $monthList[$selectedMonth]. " " .$selectedYear;?></h2>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p class="modal-title" id="modalTitle" name="modalTitle">Do you really want to send the expenses ?</p>
                        <div class="row justify-content-center">
                            <button type="button" name="yesButton" id="sendButton" class="btn btn-primary">
                                <i class="fas fa-check modal-button-link-ico"></i>Yes
                            </button>
                            <button type="button" name="noButton" class="btn btn-primary" data-dismiss="modal">
                                <i class="fas fa-times modal-button-link-ico"></i>No
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include("footer.php"); ?>
    </footer>
</div>
</div>
</body>

<!-- Pie chart -->
<script>   
var pieTable = <?php echo json_encode($pieArray); ?>;
var colorTable = <?php echo json_encode($colorList); ?>;
</script> 

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="assets/demo/chart-pie-demo.js"></script>

<!-- Table -->
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="assets/demo/datatables-demo.js"></script>
</html>
