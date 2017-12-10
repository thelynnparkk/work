<div class="panel-body">

	<?php echo validation_errors(); ?>
	<?php if($message): ?>
		<div>
			<?=$message?>
			<?=password_hash($this->input->post('password'), PASSWORD_BCRYPT);?>
		</div>
	<?php endif; ?>
	<?=form_open('login')?>
	<div class="form-group">
		<div class="input-group">
			<?=form_input($username)?>
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<?=form_input($password)?>
		</div>
		<!-- 
		 -->
		<?=form_submit('name','value')?>
		<?=form_close()?> 
	</div>

