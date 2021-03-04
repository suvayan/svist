$(document).ready(function() {
	setInterval(()=>{
		getActiveInactiveUsers('student');
		getActiveInactiveUsers('teacher');
		getSMSToForUsers();
		updateChatHistoryData();
	},5000);
})
function getActiveInactiveUsers(userType)
{
	$.ajax({
		url: baseURL+'Message/getAllConnectedUsers',
		type: 'GET',
		data: {ut: userType},
		success: (res)=>{
			$('#'+userType+'_list').html(res);
		},
		error: (errors)=>{
			console.log(errors);
		}
	});
}
function getSMSToForUsers()
{
	$.ajax({
		url: baseURL+'Message/getToFroMsgUsers',
		type: 'GET',
		success: (res)=>{
			$('#msg_details').html(res);
		},
		error: (errors)=>{
			console.log(errors);
		}
	});
}

function make_chat_dialog_box(to_user_id, to_user_name)
{
	var modal_content = '<div id="user_dialog_'+to_user_id+'" class="user_dialog" title="You have chat with '+to_user_name+'">';
	modal_content += '<div style="height:300px; border:1px solid #ccc; overflow-y: scroll; padding:0px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
	modal_content += '<div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%">Loading all chats</div>';
	modal_content += '</div>';
	modal_content += '<div class="form-group">';
	modal_content += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control"></textarea>';
	modal_content += '</div><div class="form-group" align="right">';
	modal_content+= '<button type="button" name="send_chat" id="'+to_user_id+'" class="btn btn-info btn-sm send_chat">Send</button></div></div>';

	$('#user_model_details').html(modal_content);
	fetchUserChatHistory(to_user_id);
}

 $(document).on('click', '#start_chat', function(){
	var to_user_id = $(this).data('touserid');
	var to_user_name = $(this).data('tousername');
	make_chat_dialog_box(to_user_id, to_user_name);
	$("#user_dialog_"+to_user_id).dialog({
		autoOpen:false,
		width:500
	});
	$('.ui-dialog-titlebar-close').html('<i class="material-icons">close</i>');
	$('#user_dialog_'+to_user_id).dialog('open');
});
$(document).on('click', '.send_chat', function(){
	var to_user_id = $(this).attr('id');
	var chat_message = $('#chat_message_'+to_user_id).val();
	$.ajax({
	   url:baseURL+"Message/insertChat",
	   method:"POST",
	   aysnc: false,
	   data:{to_user_id:to_user_id, chat_message:chat_message},
	   success:function(data)
	   {
		$('#chat_message_'+to_user_id).val('');
		fetchUserChatHistory(to_user_id);
	   }
	});
})

function fetchUserChatHistory(to_user_id)
{
	$.ajax({
		url: baseURL+'Message/fetchUserChatHistory',
		type: 'GET',
		data: {
			uid: to_user_id
		},
		aysnc: false,
		success: (data)=>{
			$('#chat_history_'+to_user_id).html(data);
			$('#chat_history_'+to_user_id).stop ().animate ({
			  scrollTop: $('#chat_history_'+to_user_id)[0].scrollHeight
			});
		}
	});
}

function updateChatHistoryData()
{
	$('.chat_history').each(function(){
		var to_user_id = $(this).data('touserid');
		fetchUserChatHistory(to_user_id);
	});
}