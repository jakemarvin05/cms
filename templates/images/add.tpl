{include file="../header.tpl" title='Accounts - Add' authorized=true current='accounts'}

<section class="add-view">
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="well">
					<form class="form-horizontal" id="new-image" name="new-image" novalidate enctype="multipart/form-data">
						<fieldset>
							<legend>Add new image</legend>
							<div class="form-group">
								<label for="new-image-name" class="col-lg-4 control-label">
									Name
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="new-image-name" name="name" placeholder="Name" type="text" required>
									<span class="error-message">Name is required</span>
								</div>
							</div>
							<div class="form-group">
								<label for="new-image-file" class="col-lg-4 control-label">
									Image
								</label>
								<div class="col-lg-8">
									<input class="form-control" id="new-image-file" name="file" placeholder="Choose" type="file" required>
									<span class="error-message">Choose file to upload</span>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-8 col-lg-offset-4">
									<button type="submit" class="btn btn-primary">Submit</button>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<script src="{$smarty.const.BASE_URL}/assets/js/images.js"></script>

{include file="../footer.tpl"}
