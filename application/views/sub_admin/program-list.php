<?php
    if(!empty($progs)):
        $i = 1;
        foreach($progs as $row):
?>
            <tr>
                <td><?php echo $i;?></td>
                <td><?php echo trim($row->title);?>-<?php echo $row->yearnm.'<br>';?><?php echo $row->code.'<br>';?><?php echo $row->category.'<br>';?><?php echo ($row->type=='1')? 'New Admission':'Old Admission';?></td>
		        <td><?php echo $row->stream_title;?></td>
		        <td><?php echo $row->duration.'('.$row->dtype.')';?></td>
		        <td><?php echo date('j M Y', strtotime($row->start_date)).' To '.date('j M Y', strtotime($row->end_date));?></td>
				<td>
					<a href="<?php echo base_url('Subadmin/viewProgCourse/?pid='.base64_encode($row->pro_id));?>" class="btn btn-success btn-sm">Courses</a>
					<a href="<?php echo base_url('Subadmin/viewProgTeachers/?pid='.base64_encode($row->pro_id).'&org='.base64_encode($row->org_id).'&strm='.base64_encode($row->strm_id));?>" class="btn btn-primary btn-sm">Teachers</a>
					<br/>
					<a  href="<?php echo base_url().'Subadmin/addEditProgram/?id='.base64_encode($row->pro_id);?>" class="btn btn-warning btn-sm">Edit</a>
				</td>
            </tr>
<?php
        $i++;
        endforeach;
    endif;
?>
