<div class="panel-body">
	<?php if($message): ?>
		<div class="alert alert-danger alert-dismissible" role="alert">
			<?=$message?>
		</div>
	<?php endif; ?>
	<?=form_open('register')?>
	<div class="form-group">
		<div class="input-group input-group-lg">
			<span class="input-group-addon"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
			<?=form_input($username)?>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group input-group-lg">
			<span class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
			<?=form_input($password)?>
		</div>
		<div class="input-group input-group-lg">
			<span class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
			<?=form_input($confirmpassword)?>
		</div>
		<div class="input-group input-group-lg">
			<span class="input-group-addon"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span></span>
			<?=form_dropdown($role, array(
								// 'admin' => 'admin',
								'tutor' => 'tutor',
								'student' => 'student',))?>
		</div>
		<?=form_submit('Register','Register')?>
		<?=form_close()?>
	</div>
