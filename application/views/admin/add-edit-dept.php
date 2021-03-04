<?php
	/*
	<div class="row">
		<div class="col-md-6">
			<label for="title">Logo</label>
			<div class="picture-container">
				<div class="picture">
					<label>
						<img src="'.base_url('assets/img/institute/'.trim($dept[0]->logo)).'" class="picture-src" onerror="this.src=`'.base_url('assets/img/image_placeholder.jpg').'`" id="wizardPicturePreview" title="" width="250"/>
						<input type="file" name="avatar" id="avatar" accept="image/jpg, image/jpeg, image/png" style="display:none;" onchange="avaterOnChange()">
					</label>
					<input type="hidden" name="crop_img" id="crop_img" value="" />
				</div>
			</div>
		</div>
		<div class="col-md-6" id="cropImagePop" style="display:none;">
			<div id="upload-demo" class="center-block"></div>
			<br>
			<button type="button" onClick="cropImageBtn()" class="btn btn-primary">Crop</button>
			<button type="button" class="btn btn-danger btn-link" onClick="$(`#cropImagePop`).hide()">Cancel</button>
		</div>
	</div>
	*/
	echo '<div class="row">
			<div class="col-md-12">
				<h5>Under: '.trim($dept[0]->po_title).'</h5>
				<div class="form-group">
					<label for="title">Title <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="title" id="title" value="'.trim($dept[0]->title).'" required="true">
				</div>
				<div class="form-group">
					<label for="short_name">Short Form <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="short_name" id="short_name" value="'.trim($dept[0]->short_name).'" required="true">
				</div>
				<div class="form-group">
					<label for="url">Website</label>
					<input type="url" class="form-control" name="weburl" id="weburl" value="'.trim($dept[0]->website).'">
				</div>
				<div class="form-group">
					<input type="hidden" class="form-control" name="id" id="id" value="'.$dept[0]->id.'">
					<input type="hidden" class="form-control" name="org_id" id="org_id" value="'.$dept[0]->org_id.'">
					<input type="hidden" class="form-control" name="logo" id="logo" value="'.trim($dept[0]->logo).'">
				</div>
				<div class="form-group">
					<label for="contact">Contact</label><br>
					<textarea class="form-control" name="contact" id="contact" rows="3"></textarea>
					<script>$("#contact").summernote("code", `'.trim($dept[0]->contact_info).'`);</script>
				</div>
				<div class="form-group">
					<label for="details">Details</label><br>
					<textarea class="form-control" name="details" id="details" rows="3"></textarea>
					<script>$("#details").summernote("code", `'.trim($dept[0]->details).'`);</script>
				</div>
			</div>
		</div>';
?>