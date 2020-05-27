<!DOCTYPE html>
<html lang="en">
<body>
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link" href="index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>

                    <div class="sb-sidenav-menu-heading">Budget</div>
                    <?php
                    for ($year=2020; $year > 2017 ; $year--) {
                        for ($i=1; $i <= 12; $i++) {
                            $table = $year. "_" .$i;
                            $reqYearExist = "SELECT COUNT(*) FROM $table";
                            $ansYearExist = $conn->query($reqYearExist);
                            if ($ansYearExist) {
                                ?>
                                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapse<?php echo $year;?>">
                                    <div class="sb-nav-link-icon"><i class="far fa-calendar-alt"></i></div>
                                    <?php echo $year; ?>
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="collapse<?php echo $year;?>" data-parent="#sidenavAccordion">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <?php
                                        for ($month=1; $month <= 12; $month++) {
                                            $table = $year. "_" .$month;
                                            $reqMonthExist = "SELECT COUNT(*) FROM $table";
                                            $ansMonthExist = $conn->query($reqMonthExist);
                                            if ($ansMonthExist) {
                                                echo "<a class='nav-link' href='budget.php?table=$table'>" .$monthList[$month]. "</a>";
                                            }
                                        }
                                        ?>
                                    </nav>
                                </div>
                                <?php
                                $year--;
                            }
                        }
                    }
                    ?>
                    
                    <div class="sb-sidenav-menu-heading">Addons</div>
                    <a class="nav-link" href="upload.php"><div class="sb-nav-link-icon"><i class="fa fa-download"></i></div>Upload</a>
                    <a class="nav-link" href="categories.php"><div class="sb-nav-link-icon"><i class="fa fa-archive"></i></div>Categories</a>
                    <a class="nav-link" href="accounts.php"><div class="sb-nav-link-icon"><i class="fas fa-university"></i></div>Accounts</a>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>Admin
            </div>
        </nav>
    </div>
</body>
</html>