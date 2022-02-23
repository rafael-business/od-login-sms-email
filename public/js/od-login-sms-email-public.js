(function( $ ) {
	'use strict';

	$(function() {
		
		var btns = $('#od-login-sms-email');
		$('.socials-list').append(btns.html());
		btns.remove();

		$('#sms_popup').click(function(e){

			setTimeout(function(){
				$('#cel').focus();
			}, 100);
		});

		$('#email_popup').click(function(e){

			setTimeout(function(){
				$('#email').focus();
			}, 100);
		});

		$('#send_sms').click(function(e){

			e.preventDefault();

			$('#sms_status').removeClass('error');
			$('#sms_status').addClass('loading');
			$('#cel').attr('disabled', true);
			$(this).attr('disabled', true);

			var dados_envio = {
				'od_login_sms_email_nonce': od_login_sms_email_js.od_login_sms_email_nonce,
				'to': $('#cel').val(),
				'action': 'sms_flow'
			}

			$.ajax({
				url: od_login_sms_email_js.xhr_url,
				type: 'POST',
				data: dados_envio,
				dataType: 'JSON',
				success: function( response ) {
				  
					$('#sms_status').removeClass('loading');
					$('#sms_status').addClass(response.type);

					if ( 'success' === response.type ) {

						call_code_popup();
					} else {

						$('#cel').attr('disabled', false);
						$('#send_sms').attr('disabled', false);
					}

					$('#TB_ajaxContent .msg').html( '<span class ="'+response.type+'">' + response.msg + '</span>' );
				}
			});
		});

		$('#send_email').click(function(e){

			e.preventDefault();

			$('#email_status').removeClass('error');
			$('#email_status').addClass('loading');
			$('#email').attr('disabled', true);
			$(this).attr('disabled', true);

			var dados_envio = {
				'od_login_sms_email_nonce': od_login_sms_email_js.od_login_sms_email_nonce,
				'to': $('#email').val(),
				'action': 'email_flow'
			}

			$.ajax({
				url: od_login_sms_email_js.xhr_url,
				type: 'POST',
				data: dados_envio,
				dataType: 'JSON',
				success: function ( response ) {

					$('#email_status').removeClass('loading');
					$('#email_status').addClass(response.type);

					if ( 'success' === response.type ) {

						call_code_popup();
					} else {

						$('#email').attr('disabled', false);
						$('#send_email').attr('disabled', false);
					}

					$('#TB_ajaxContent .msg').html( '<span class ="'+response.type+'">' + response.msg + '</span>' );
				}
			});
		});

		$('#code').on('keyup', function(){

			var qtd_char = $(this).val().length;
			if ( 5 == qtd_char ) {

				$('#TB_ajaxContent .msg').html('<br /><br /><br />');
				$('#TB_ajaxContent .msg').addClass('loading_msg');

				$(this).attr('disabled', true);

				var dados_envio = {
					'od_login_sms_email_nonce': od_login_sms_email_js.od_login_sms_email_nonce,
					'code': $('#code').val(),
					'action': 'validate_code'
				}

				$.ajax({
					url: od_login_sms_email_js.xhr_url,
					type: 'POST',
					data: dados_envio,
					dataType: 'JSON',
					success: function(response) {

						$('#TB_ajaxContent .msg').html( '<span class ="'+response.type+'">' + response.msg + '</span>' );
						$('#TB_ajaxContent .msg').removeClass('loading_msg');
						if ( 'success' == response.type ){

							window.location.reload(false);
						} else {

							$('#code').attr('disabled', false);
						}
					}
				});
			}
		});

		$(document).on('click', '#restart', function(e){

			e.preventDefault();
			window.location.reload(false);
		});

		$(document).on('keyup', '#cel', function(){

			var valor = $(this).val();
			valor = valor.replace(/\D/g, "");
		    valor = valor.replace(/^(\d{2})(\d)/g, "($1) $2");
		    valor = valor.replace(/(\d)(\d{4})$/, "$1-$2");
		    $(this).val( valor );
		});
	});

	function call_code_popup() {

		$('#TB_ajaxContent .field_tickbox').hide();
		$('#TB_ajaxContent .controls').hide();
		$('#TB_ajaxContent .msg').hide();
		$('#code_popup').trigger('click');

		setTimeout(function(){
			$('#code').focus();
		}, 100);
	}

})( jQuery );
