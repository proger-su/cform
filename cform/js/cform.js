jQuery(document).ready(function ($) {

	var cform = {
		init: function () {
			this.formValidateInit();
		},
		formValidateInit: function () {
			$('form.cform').each(function (idx, self) {
				$(self).validate({
					submitHandler: function (form) {
						$.ajax({
							type: "POST",
							url: $(form).attr('action'),
							data: $(form).serialize(),
							timeout: 3000,
							success: function () {
								var $message = $('<p style="color: green">Sussess</p>').appendTo($(form));
								setTimeout(function () {
									$(form).trigger('reset');
									$message.remove();
								}, 1500);
							},
							error: function () {
								alert('Failed');
							}
						});
						return false;
					}
				});
			});
		}

	}

	cform.init();
});