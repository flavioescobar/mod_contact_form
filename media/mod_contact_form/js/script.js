$(document).ready( function() {
	$('#fale_conosco').submit( function ( evt )
	{
		$('#fale_conosco .btn').hide();
		$('#fale_conosco .msg_sucesso').hide();
		$('#fale_conosco .msg_falha').hide();
		$('#fale_conosco .msg_carregando').show();

		$.ajax({
			type: 'POST',
			url: urlAtual,
			data: {
				task: 'mod_contact_form_sendmail',
				nome: $('#fale_conosco input[name="nome"]').val(),
				email: $('#fale_conosco input[name="email"]').val(),
				mensagem: $('#fale_conosco textarea[name="mensagem"]').val()
			},
			success: function( resultado )
			{
				$('#fale_conosco .msg_carregando').hide();

				if ( resultado.sucesso == 1 )
				{
					$('#fale_conosco .msg_sucesso').html( resultado.msg );
					$('#fale_conosco .msg_sucesso').show();
				}
				else
				{
					$('#fale_conosco .msg_falha').html( resultado.msg );
					$('#fale_conosco .msg_falha').show();
					$('#fale_conosco .btn').show();
				}
			},
			dataType: "json"
		});

		evt.preventDefault();
		return false;
	});
});