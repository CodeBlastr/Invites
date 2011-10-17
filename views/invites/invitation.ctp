<h2>Invite Friends</h2>
<?php
	echo $form->create('Invite');
		
	echo $form->input('Invite.emails', array('label' => 'Email Addresses'));
		
				
	echo $form->button('Send!', array('type'=>'submit', 'class'=>'button'));
	echo $form->end();
?>
<?php echo $this->element('facebookInviter', array('fb_invite_info' => $fb_invite_info)); ?>

	