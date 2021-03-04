$(document).ready(function($)
{
	$("form#frmProgram").validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		rules: {
			org: {
				required: true
			},
			dept: {
				required: true
			},
			title: {
				required: true,
				minlength: 5
			},
			type: {
				required: true
			},
			duration: { 
				required: true,
				digits: true
			},
			total_credit: {
				digits: true
			},
			dtype: {
				required: true
			},
			pcode: {
				required: true
			},
			category: {
				required: true
			},
			sdate: {
				required: true
			},
			ldate: {
				required: true
			},
			feetype: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true,
				digits: true,
				maxlength: 10,
				minlength: 10
			},
			website: {
				url: true
			},
			facebook: {
				url: true
			},
			linkedin: {
				url: true
			},
			twitter: {
				url: true
			},
			fees: {
				digits: true
			},
			prog_type: {
				required: true
			},
			screen_type: {
				required: true
			},
			apply_type: {
				required: true
			},
			adstart: {
				required: true
			},
			adend: {
				required: true
			},
			criteria: {
				required: true
			},
			semtype: {
				required: true
			}
		},
		messages: {
			org: {
				required: 'This field is required.'
			},
			dept: {
				required: 'This field is required.'
			},
			title: {
				required: 'This field is required.',
				minlength: 'The title must be minimum 6 characters'
			},
			type: {
				required: 'This field is required.'
			},
			duration: { 
				required: 'Must enter the duration [in months].',
				digits: 'Only enter numbers.'
			},
			total_credit: {
				digits: 'Only enter numbers.'
			},
			dtype: {
				required: 'This field is required.'
			},
			pcode: {
				required: 'This field is required.'
			},
			category: {
				required: 'This field is required.'
			},
			sdate: {
				required: 'This field is required.'
			},
			ldate: {
				required: 'This field is required.'
			},
			feetype: {
				required: 'This field is required.'
			},
			email: {
				required: 'This field is required.',
				email: 'Enter valid email.'
			},
			phone: {
				required: 'This field is required.',
				digits: 'Phone must have digits only.',
				maxlength: 'Phone must have 10 digits only.',
				minlength: 'Phone must have 10 digits only.'
			},
			website: {
				url: 'Enter valid URL fo website.'
			},
			facebook: {
				url: 'Enter valid URL fo facebook.'
			},
			linkedin: {
				url: 'Enter valid URL fo linkedin.'
			},
			twitter: {
				url: 'Enter valid URL fo twitter.'
			},
			fees: {
				digits: 'The fee must be in digits.'
			},
			prog_type: {
				required: 'This field is required.'
			},
			screen_type: {
				required: 'This field is required.'
			},
			apply_type: {
				required: 'This field is required.'
			},
			adstart: {
				required: 'This field is required.'
			},
			adend: {
				required: 'This field is required.'
			},
			criteria: {
				required: 'This field is required.'
			},
			semtype: {
				required: 'This field is required.'
			}
		}
	});
});