<div class="row">
	
	<div class="col-md-12">
		<div class="card mt-2" style="background-color: #e6ecf6;">
			<div class="card-header card-header-info card-header-text">
			  <div class="card-text">
				<h4 class="card-title"><?php echo $title; ?></h4>
			  </div>
			</div>
			<div class="card-body">
				<div class="material-datatables">
					<table class="table table-striped table-no-bordered table-hover" id="schclassList" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th width="5%">Sl.</th>
								<th width="40%">Title</th>
								<th width="30%">Duration</th>
								<th width="25%">Medium</th>
							</tr>
						</thead>
						<tbody>
							<?php $i=1; foreach($schClass as $schr){ ?>
								<tr>
									<td><?php echo $i; ?></td>
									<td><?php echo '<div class="td-name">'.$schr->class_title.'<br>'.$schr->class_type.'<br>By: '.$schr->facname.'</div>'; ?></td>
									<td><?php echo 'Start: '.date('j M Y h:sa',strtotime($schr->start_datetime)).'<br>End: '.date('j M Y h:sa',strtotime($schr->end_datetime)); ?></td>
									<td><?php echo $schr->onlinemedium; ?></td>
								</tr>
							<?php $i++; } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$('#schclassList').DataTable();
</script>