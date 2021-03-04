<?php
	foreach($progs as $row){
	echo '<tr>
		<td>'.trim($row->title).' - ('.$row->yearnm.')<br>'.$row->code.'<br>'.$row->category.'<br>'.(($row->type=='1')? 'New Admission':'Old Admission').'</td>
		<td>'.$row->org_title.'</td>
		<td>'.$row->stream_title.'</td>
		<td>'.$row->duration.'('.$row->dtype.')</td>
		<td>'.date('j M Y', strtotime($row->start_date)).' To '.date('j M Y', strtotime($row->end_date)).'</td>
		<td>
			<a href="'.base_url('Admin/viewProgCourse/?pid='.base64_encode($row->pro_id)).'" class="btn btn-success btn-sm">Courses</a>
			<a href="'.base_url('Admin/viewProgTeachers/?pid='.base64_encode($row->pro_id).'&org='.base64_encode($row->org_id).'&strm='.base64_encode($row->strm_id)).'" class="btn btn-primary btn-sm">Teachers</a>
			<br>
			<a  href="'.base_url().'Admin/addEditProgram/?id='.base64_encode($row->pro_id).'" class="btn btn-warning btn-sm">Edit</a>
			<a  href="#" class="btn btn-info btn-sm">View</a>
		</td>
	</tr>';
	}
?>