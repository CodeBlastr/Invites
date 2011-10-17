<h2>Invite Friends</h2>
<?php
	echo $this->Form->create('Invite');
		
	echo $this->Form->input('Invite.emails', array('label' => 'Email Addresses'));
		
				
	echo $this->Form->button('Send!', array('type'=>'submit', 'class'=>'button'));
	echo $this->Form->end();
?>
<?php echo $this->element('facebookInviter', array('fb_invite_info' => $fb_invite_info)); ?>

	