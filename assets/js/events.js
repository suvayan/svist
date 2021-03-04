$(document).ready(function(){
	//setFormValidation('#frmEvent');
    md.initFormExtendedDatetimepickers();
	
	var addEventForm = $("#frmEvent");
	
	var validator = addEventForm.validate({
		rules: {
			title: {required: true, minlength: 5},
			type: { required : true },
			sub_type: { required : true },
			review: { required : true },
			pre_type: { required : true, selected: true },
			venue: { required : true },
			sdate: { required : true },
			edate: { required : true },
			email: { email: true },
			phone: { digits: true, maxlength: 10 },
			website: { url: true },
			facebook: { url: true },
			linkedin: { url: true },
			twitter: { url: true }
		},
		message: {
			title: { required: 'Must enter event title', minlength: 'Must have more than one characters' },
			type: { required : 'Must select one event type'},
			sub_type: { required : 'Must select one submission type'},
			review: { required : 'Must select one review type'},
			pre_type: { required : 'Must select one/more presentation type', selected : "Please select atleast one option"},
			venue: { required : 'Must select one venue'},
			sdate: { required : 'Must add start date and time'},
			edate: { required : 'Must add end date and time'},
			email: { email: 'Must enter valid email' },
			phone: { digits: 'Must contain digits', maxlength: 'Must be 10 digits' },
			website: { url: 'Must enter valid url: http://www.example.com' },
			facebook: { url: 'Must enter valid url: http://www.example.com' },
			linkedin: { url: 'Must enter valid url: http://www.example.com' },
			twitter: { url: 'Must enter valid url: http://www.example.com' }
		}
	});
});