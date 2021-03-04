$(document).ready(function($)
{
	$("form#frmProfile").validate({
		errorPlacement: function(error, element) {
		  $(element).closest('.form-group').append(error);
		},
		rules: {
			firstname: {
				required: true,
				minlength: 5
			},
			lastname: {
				required: true,
				minlength: 2
			},
			dob: {
				required: true
			},
			gender: {
				required: true
			},
			maritalstatus: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true,
				minlength: 10,
				maxlength: 10,
				digits: true
			},
			org: {
				required: true,
				minlength: 5
			},
			designation: {
				required: true,
				minlength: 5
			},
		},

		messages: {
			firstname: {
				required: 'Please enter your firstname',
				minlength: 'Minimum 5 characters'
			},
			lastname: {
				required: 'Please enter your firstname',
				minlength: 'Minimum 2 characters'
			},
			dob: {
				required: 'Enter your date of birth'
			},
			gender: {
				required: 'Select your gender'
			},
			maritalstatus: {
				required: 'Select marital status'
			},
			email: {
				required: 'Please enter email address',
				email: 'Enter valid email address'
			},
			phone: {
				required: 'Please enter your phone',
				minlength: 'Must be 10 digits',
				maxlength: 'Must be 10 digits',
				digits: 'Must be number'
			},
			org: {
				required: 'Please enter organization/company',
				minlength: 'Minimum 5 characters'
			},
			designation: {
				required: 'Please enter your designation',
				minlength: 'Minimum 5 characters'
			},
		}
	});
	// Set Form focus
	$("form#frmProfile .form-group:has(.form-control):first .form-control").focus();
});
