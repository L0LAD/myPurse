<?php


include('conn.php');
include('head.php');

if(isset($_POST['colour'])){
    $colour = ($_POST['colour']);
} else {
    $colour = 0;
}
echo $colour;

?>

<!DOCTYPE html>
<html lang="en">
<body>

    <div id="wrapper">  

        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main content -->
            <div id="content">
                <div class="container">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h3 class="m-0 font-weight-bold">Edit place</h3>
                        </div>
                        <div class="card-body">  

                            <form class="form-horizontal" method="post" action="">

                                <div class="form-group">
                                    <div class="col-md-3 col-sm-11">
                                        <label>Colour</label>
                                    </div>
                                    <div>
                                        <input id="colour" type="color" class="form-control" class="form-control" name="colour">
                                    </div>
                                </div>

                                <div class="right row">
                                    <button id="addButton" class="btn btn-primary" type="submit">Edit</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>

$('#addButton').click(function(){
    var colour = $('#colour').val();
    alert(colour);
})

</script>

</body>