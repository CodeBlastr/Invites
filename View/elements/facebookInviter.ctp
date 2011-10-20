<br />
<h1>Invite with Facebook</h1>
<br />
<?php if ($this->Session->read('FB.uid')) { ?>        
	<fb:serverFbml>
	<script type="text/fbml">
	<fb:fbml>
    	<fb:request-form
			action="<?php echo $fb_invite_info['fb_invite_action']; ?>"
        	method='POST'
        	type='invite'
        	content='<?php echo $fb_invite_info['fb_invite_content']; ?>
				<fb:req-choice url="<?php echo $fb_invite_info['fb_req_choice_yes_url']; ?>" label="Yes" />'
            	<fb:req-choice url="<?php echo $fb_invite_info['fb_req_choice_no_url']; ?>" label="No" />'
        <fb:multi-friend-selector actiontext="<?php echo $fb_invite_info['fb_invite_action_text']; ?>">
    </fb:request-form>
	</fb:fbml>
	</script>
	</fb:serverFbml>
<?php
} else {
	echo '<p>'.$fb_invite_info['fb_before_login_invite_text'].'</p>';
	echo '<br />';
	echo $this->Facebook->login(array('perms' => 'email,publish_stream', 'redirect' => $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]));
}
?>