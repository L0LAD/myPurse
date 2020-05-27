<?php
include('head.php');

$categoryRequiredList = ['categoryField','colorPicker','iconField'];
?>

<!DOCTYPE html>
<html lang="en">
<body>

	<div id="addCategoryModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title" id="categoryModalTitle"></h2>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<form class="modal-body">
					<div class="form-row">
						<div class="form-group col-md-12">
		                    <label for="categoryField">Category</label>
							<input type="text" name="category" id="categoryField" class="form-control" onKeyUp="fields_valid('category')" onKeyDown="fields_valid('category')"/>
						</div>
					</div>
					<div class="form-row">
						<div class="form-group col-md-6">
		                    <label for="colorPicker">Color</label>
							<input type="color" name="color" id="colorPicker" class="form-control" onKeyUp="fields_valid('category')" onKeyDown="fields_valid('category')"/>
						</div>
						<div class="form-group col-md-6">
		                    <label for="iconField">Icon</label>
							<input type="text" name="icon" id="iconField" class="form-control" onKeyUp="fields_valid('category')" onKeyDown="fields_valid('category')"/>
						</div>
					</div>
				</form>

				<div class="modal-footer">
					<button type="button" name="addCategoryAfter" id="addCategoryAfter" class="btn btn-success">Add</button>
				</div>
			</div>
		</div>
	</div>
	<?php include('script.php'); ?>
</body>
<script>
	document.getElementById('categoryField').value = '';
	document.getElementById('colorPicker').value = '';
	document.getElementById('iconField').value = '';
	document.getElementById('addCategoryAfter').disabled = true;

	$(document).keydown(function(keyboard){ // on écoute l'évènement keyup()
	    var e = keyboard.which || keyboard.keyCode; // le code est compatible tous navigateurs grâce à ces deux propriétés
	    if(e == 17){ // si le code de la keyboard est égal à 13 (Entrée)
	        keyboard.preventDefault();
	        $("#addCategoryAfter").click();
	        return false;
	    }
	});
</script>