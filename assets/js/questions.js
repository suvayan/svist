$(document).ready(function(){
	CKEDITOR.disableAutoInline = true;
	CKEDITOR.inline('qbstitle', {
		extraPlugins: 'uploadimage,colorbutton,embedsemantic,mathjax,font,justify,table,specialchar,tabletools',
		height: 100,
		mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
	});
	
	$("form#frmQues").validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		submitHandler: function(form, e) {
			e.preventDefault();
			$('#loading').css('display', 'block');
			/*for (instance in CKEDITOR.instances) {
				CKEDITOR.instances[instance].updateElement();
			}*/
			var qbs_id = parseInt($('#qbs_id').val());
			var frmQuesData = new FormData($('#frmQues')[0]);
			var arr = [];
			$('#frmsubmit').attr('disabled', true);
			
			var mcount = parseInt($('#mcount').val());
			if(mcount!=0){
				for(var i=0; i<=mcount; i++){
					if($('#chkmcq_'+i).val()){
						if($('#chkmcq_'+i).is(':checked')){
							arr.push({
								'id':i,
								'ckval':true
							});
						}else{
							arr.push({
								'id':i,
								'ckval':false
							});
						}
					}
				}
				frmQuesData.append('mcqchkboxes',JSON.stringify(arr));
			}
			$.ajax({
				url: baseURL+'Exam/cuQuestions',
				type: 'POST',
				data: frmQuesData,
				cache : false,
				processData: false,
				contentType: false,
				async: false,
				success: (resp)=>{ 
					$('#loading').css('display', 'none');
					var obj = JSON.parse(resp);
					swal({
						title: 'Added',
						text: 'The Question '+obj['msg'],
						type: obj['status'],
						confirmButtonClass: "btn btn-success",
						buttonsStyling: false
					}).then((result)=>{
						if(qbs_id==0){
							window.location.reload();
						}else{
							window.location.href=baseURL+'Teacher/questionBank';
						}
						//window.location.reload();
						//window.location.href=baseURL+'Teacher/questionBank';
						/*$('#frmQues')[0].reset();
						$('.selectpicker').selectpicker('refresh');
						$('#qbs_id').val(0);
						$('#qbs_qb').val(obj['qbs_qb']);
						$('#qbs_prog').val(obj['cat_id']);
						$('#qbs_course').val(obj['scat_id']);
						$('#frmsubmit').removeAttr('disabled');*/
						//$('#quesl_'+id).remove();
						/*if(parseInt(qid)==0){
							$('#frmQues')[0].reset();
							$('.selectpicker').selectpicker('refresh');
							$('#qbs_id').val(0);
							$('#qbs_btn').removeAttr('disabled');
						}else{
							
						**/
					})
					/*if(obj['status']=='success'){
						getAllQuestionsList();
					}
					$.notify({icon:"add_alert",message:'The Question '+obj['msg']},{type:obj['status'],timer:3e3,placement:{from:'top',align:'right'}})*/
				},
				error: (errors)=>{
					console.log(errors);
				}
			});
		}
	});
	jQuery.validator.addMethod('ckrequired', function (value, element, params) {
		var idname = jQuery(element).attr('id');
		var messageLength =  jQuery.trim ( CKEDITOR.instances[idname].getData() );
		return !params  || messageLength.length !== 0;
	}, "This field is required");
});
$('#qbs_test').on('change', ()=>{
	var tyt = $('#qbs_test').val();
	toggleOptions(tyt, 0);
});
function toggleOptions(tyt, mcount)
{
	if(tyt==2){
		$('#mcqs_all').show();
		$('#num_all').hide();
		$('#mcount').val(mcount);
		$('#mcqs_set').html('');
		$('#mcq_error').html('');
	}else if(tyt==4){
		$('#num_all').show();
		$('#mcqs_all').hide();
	}else{
		$('#mcqs_all').hide();
		$('#num_all').hide();
	}
}
$('#ms').on('change', ()=>{
	var ms = $('#ms:checked').val();
	var mcount = parseInt($('#mcount').val());
	var i;
	if(ms==1){
		for(i=0; i<=mcount; i++){
			$('#chkmcq_'+i).attr('type', 'checkbox');
		}
	}else{
		for(i=0; i<=mcount; i++){
			$('#chkmcq_'+i).attr('type', 'radio');
		}
	}
})
function addMore()
{
	var mcount = parseInt($('#mcount').val());
	
	var fieldset = '<fieldset class="mb-3" id="fld_'+mcount+'" style="border: 1px solid #a1a1a1; border-radius:10px; padding: 10px;"><div class="form-group"><div class="row"><div class="col-sm-1 mb-4"><div class="form-check"><label class="form-check-label text-dark"><input class="form-check-input" type="radio" value="1" name="chkmcq[]" id="chkmcq_'+mcount+'" required="true"><span class="circle"><span class="check"></span></span></label></div></div><div class="col-sm-8 border"><textarea class="form-control" name="mcqs_'+mcount+'" id="mcqs_'+mcount+'" ckrequired="true"></textarea></div><div class="col-sm-2"><div class="input-group"><input type="number" name="msnum_'+mcount+'" id="msnum_'+mcount+'" class="form-control" value="0"><div class="input-group-prepend"><span class="input-group-text">% marks</span></div></div></div><div class="col-sm-1 align-self-center"><button type="button" class="btn btn-sm btn-danger btn-link p-0 pull-right" onClick="removeMSet('+mcount+')" id="btn_'+mcount+'"><i class="material-icons">close</i></button></div></div></div></fieldset>';
	
	$('#mcqs_set').append(fieldset);
	CKEDITOR.inline('mcqs_'+mcount, {
		extraPlugins: 'uploadimage,colorbutton,embedsemantic,mathjax,font,justify,table,specialchar,tabletools',
		height: 100,
		mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.4/MathJax.js?config=TeX-AMS_HTML'
	});
	mcount++;
	$('#mcount').val(mcount);
	checkMinMaxSets();
}
function removeMSet(id)
{
	$('#fld_'+id).remove();
	checkMinMaxSets();
}
function checkMinMaxSets()
{
	var fcounter = document.getElementsByTagName('fieldset').length;
	frmsubmit
	mcq_error
	if(fcounter<2)
	{	
		$('#mcq_error').html('MCQ must have atleast 2 options');
		$('#frmsubmit').attr('disabled', true);
		$('#addSet').show();
	}else if(fcounter==8){
		$('#mcq_error').html('MCQ must have max 8 options');
		$('#addSet').hide();
	}else{
		$('#addSet').show();
		$('#mcq_error').html('');
		$('#frmsubmit').removeAttr('disabled', true);
	}
}