<?php
/*
	David Bray
	BrayWorth Pty Ltd
	e. david@brayworth.com.au

	This work is licensed under a Creative Commons Attribution 4.0 International Public License.
		http://creativecommons.org/licenses/by/4.0/

	*/	?>
<div class="container-fluid">
	<form method="post" class="form" data-role="user-form" action="<?php url::write('users') ?>">
		<input type="hidden" name="id" value="<?php print $this->data->dto->id ?>" />

		<div class="row form-group">
			<div class="col-3">UserName</div>
			<div class="col-9">
				<input type="text" name="username" class="form-control" placeholder="username"
					value="<?php print $this->data->dto->username ?>" required
					<?php if ( $this->data->dto->id) print 'disabled'; ?> />

			</div>

		</div>

		<div class="row form-group">
			<div class="col-3">Name</div>
			<div class="col-9">
				<input type="text" name="name" class="form-control" placeholder="name" required
					autofocus value="<?php print $this->data->dto->name ?>" />

			</div>

		</div>

		<div class="row form-group">
			<div class="col-3">Email</div>
			<div class="col-9">
				<input type="text" name="email" class="form-control" placeholder="email"
					value="<?php print $this->data->dto->email ?>" required />

			</div>

		</div>

		<div class="row form-group">
			<div class="col-3">Exchange Server</div>
			<div class="col-9">
				<input type="text" name="exchange_server" class="form-control" placeholder="exchange server"
					value="<?php print $this->data->dto->exchange_server ?>" required />

			</div>

		</div>

		<div class="row form-group">
			<div class="col-3">Exchange Alias</div>
			<div class="col-9">
				<input type="text" name="exchange_alias" class="form-control" placeholder="exchange alias"
					value="<?php print $this->data->dto->exchange_alias ?>" required />

			</div>

		</div>

		<div class="row form-group">
			<div class="col-3">Exchange Account</div>
			<div class="col-9">
				<input type="text" name="exchange_account" class="form-control" placeholder="domain\user"
					value="<?php print $this->data->dto->exchange_account ?>" required />

			</div>

		</div>

		<div class="row form-group">
			<div class="col-3">Exchange Password</div>
			<div class="col-9 input-group">
				<input type="password" name="exchange_password" class="form-control" placeholder="exchange password if you would like to change"
					value="" />
					<span class="input-group-addon" title="test connection" id="exchange-connection-tester"><i class="fa fa-question-circle-o"></i></span>

			</div>

		</div>

		<div class="row form-group">
			<div class="col-3">Password</div>
			<div class="col-9">
				<input type="password" name="pass" class="form-control" placeholder="password - if you want to change it .." />

			</div>

		</div>

		<div class="row form-group">
			<div class="col-9 offset-3">
				<input  class="btn btn-primary" type="submit" name="action" value="save/update" />

			</div>

		</div>

	</form>

</div>
<script>
$(document).ready( function() {
	$('#exchange-connection-tester')
	.css('cursor','pointer')
	.on('click', function(e) {
		e.stopPropagation(); e.preventDefault();

		var data = {
			action : 'check-exchange-password',
			user : $('input[name="exchange_account"]').val(),
			pass : $('input[name="exchange_password"]').val(),
			server : $('input[name="exchange_server"]').val(),
		}

		if ( '' === data.user.trim()) {
			$('body').growlError('enter a user name');
			$('input[name="exchange_account"]').focus();

		}
		else if ( '' === data.pass.trim()) {
			$('body').growlError('enter a password');
			$('input[name="exchange_password"]').focus();

		}
		else if ( '' === data.server.trim()) {
			$('body').growlError('enter a server');
			$('input[name="exchange_server"]').focus();

		}
		else {

			$.ajax({
				type : 'POST',
				url : _brayworth_.urlwrite( 'users'),
				data : data,

			})
			.done( function( data) {
				$('body').growlAjax( data);
				if ( !!data.response ) {
					if ( data.response == 'ack' )
						$('#ExchangeVerfied').html( 'Verified');

					return;

				}
				modalAlert( 'there was an error');

			})
			.fail( function( data) { modalAlert( 'there was an error'); })

		}

	})


	var f = $('form.form[data-role="user-form"]');
	f.on( 'submit', function() {
		var p = $('input[name="pass"]').val();
		if ( p.length > 0 && p.length < 3) {
			$('body').growlError('password must be 3 or more characters');
			$('input[name="pass"]').focus().select();
			return ( false);

		}

		return ( true);

	})

});
</script>
