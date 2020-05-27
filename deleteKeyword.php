<?php include('head.php'); ?>

<!DOCTYPE html>
<html lang="en">
<body>

	<div id="deleteKeywordModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="modal-title" name="modalTitle">Keyword removal</h2>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<p class="modal-title" id="modalTitle" name="modalTitle">Do you really want to delete <span name="currentKeyword"></span> from <span name="currentCategory"></span> ?</p>
					<div class="row justify-content-center">
						<button type="button" name="yesButton" id="deleteKeywordButton" class="btn btn-primary">
							<i class="fas fa-check modal-button-link-icon"></i>Yes
						</button>
						<button type="button" name="noButton" class="btn btn-primary" data-dismiss="modal">
							<i class="fas fa-times modal-button-link-icon"></i>No
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php include('script.php'); ?>

</body>