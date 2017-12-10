<?php
	foreach($users as $user){
		echo form_open('user/manage/'.$user->id);
		echo form_input('username', $user->username);
		echo form_input('password');
		echo form_dropdown('role', array(
								// 'admin' => 'admin',
								'tutor' => 'tutor',
								'student' => 'student',));
		echo form_submit('Save', 'save');
		echo form_close();
	}
?>
