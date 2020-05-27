<?php
include('head.php');

$keywordRequiredList = ['keywordField'];
?>

<!DOCTYPE html>
<html lang="en">
<body>

	<div id="addKeywordModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title" id="keywordModalTitle"></h2>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>

				<form class="modal-body">
					<div class="form-row">
						<div class="form-group col-md-12">
							<label for="keywordField" name="currentCategory"></label>
							<input type="text" name="keyword" id="keywordField" class="form-control" onKeyDown="fields_valid('keyword')" onKeyUp="fields_valid('keyword')"/>
						</div>
					</div>
				</form>

				<div class="modal-footer">
					<button type="button" name="addKeywordAfter" id="addKeywordAfter" class="btn btn-primary">Add</button>
				</div>
			</div>
		</div>
	</div>
	<?php include('script.php'); ?>
</body>
<script>
	document.getElementById('keywordField').value = '';
	
	$(document).keydown(function(keyboard){ // on écoute l'évènement keyup()
	    var e = keyboard.which || keyboard.keyCode; // le code est compatible tous navigateurs grâce à ces deux propriétés
	    if(e == 17){ // si le code de la keyboard est égal à 13 (Entrée)
	        keyboard.preventDefault();
	        $("#addKeywordAfter").click();
	        return false;
	    }
	});

</script>