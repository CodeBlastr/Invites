<?php 
	//echo $this->Html->script('/galleries/js/fancybox/jquery.mousewheel-3.0.4.pack'); 
	//echo $this->Html->script('/galleries/js/fancybox/jquery.fancybox-1.3.4.pack');	
	//echo $this->Html->css('/galleries/css/fancybox/jquery.fancybox-1.3.4');	
?>

<h2>Invite Friends</h2>
<?php
	echo $this->Form->create('Invite', array('action' => 'invitation'));
	echo $this->Form->input('Invite.emails', array('label' => 'Email Addresses (comma separated)'));
	/*echo $this->Form->input('Invite.emails', array('label' => 'Email Addresses', 'after' => '<div class="floatRight divRightWidth">
				<span class="textColorGrey">Import your contacts with their email addresses directly from your favorite mail service.</span>
				<p><a href="#importContacts" value="Gmail" class="import"><img src="../images/email/gmail.gif" /></a>
				<a href="#importContacts" value="Yahoo" class="import"><img class="imgPointer" src="../images/email/yahoo.gif" /></a></p>
				<p><a href="#importContacts" value="Hotmail" class="import"><img class="imgPointer" src="../images/email/msn-hotmail.png" /></a>
				<a href="#importContacts" value="Aol" class="import"><img class="imgPointer" src="../images/email/aol.jpg" /></a></p>
				<p><a href="#importContacts" value="Outlook" class="import"><img class="imgPointer" src="../images/email/outlook.gif" /></a></p>
				</div>'));*/
	echo $this->Form->end('Send!', array('class'=>'button'));

	if ( !empty($fb_invite_info) ) {
		echo $this->Element('facebookInviter', array('fb_invite_info' => $fb_invite_info));
	}
	?>

<?php /*
<!-- FancyBox Div for contacts import from different mail services -->

<div style="display: none;">
	<div id="importContacts" style="width: 375px;">
		<div id="import-dialog-content">
	        <div id="import-dialog-accordion">
	            <h3><a href="#">Import from Gmail</a></h3>
	            <div>
					<p><img src="../images/email/gmail.gif" /></p>
					<?php
						echo $this->Form->create('Referral', array('url' => 'import_contacts', 'id' => 'importGmail'));
					?>
					<p>E-mail:</p>
	                <p><?php echo $this->Form->input('login', array('label' => false, 'class' => 'required')); ?></p>
	                <p>Password:</p>
	                <p><?php echo $this->Form->input('password', array('class' => 'required', 'label' => false)); ?></p>
					<?php 	
						echo $this->Form->hidden('service', array('value' => 'Gmail'));
						echo $this->Form->submit(__('Import Contacts', true), array('class' => 'contactsImport'));
						//echo $this->Form->submit('', array('type'=>'image','src' => '/images/buttons/import_lb1.jpg', 'class' => 'contactsImport'));
						echo $this->Form->end();
						  
					?>
					<div id="importGmailError" style="display: none;"><font color="red"> Fill All Fields Required. </font></div>	
				</div>
				
				<h3><a href="#">Import from Yahoo</a></h3>
				<div>
					<p><img src="../images/email/yahoo.gif" /></p>
					<?php
						echo $this->Form->create('Referral', array('url' => 'import_contacts', 'id' => 'importYahoo'));
					?>
					<p>E-mail:</p>
	                <p><?php echo $this->Form->input('login', array('label' => false, 'class' => 'required')); ?></p>
	                <p>Password:</p>
	                <p><?php echo $this->Form->input('password', array('class' => 'required', 'label' => false)); ?></p>
					<?php 	
						echo $this->Form->hidden('service', array('value' => 'Yahoo'));
						echo $this->Form->submit(__('Import Contacts', true), array('class' => 'contactsImport'));
						//echo $this->Form->submit('', array('type'=>'image','src' => '/images/buttons/import_lb1.jpg', 'class' => 'contactsImport'));
						echo $this->Form->end();
					?>
					<div id="importYahooError" style="display: none;"><font color="red"> Fill All Fields Required. </font></div>	
				</div>
				            
	            <h3><a href="#">Import from Hotmail</a></h3>
	            <div>
	                <p><img src="../images/email/msn-hotmail.png" /></p>	
					<?php
						echo $this->Form->create('Referral', array('url' => 'import_contacts', 'id' => 'importHotmail'));
						echo $this->Form->hidden('service', array('value' => 'Hotmail'));
						echo $this->Form->submit(__('Import Contacts', true), array('class' => 'contactsImport'));
						//echo $this->Form->submit('', array('type'=>'image','src' => '/images/buttons/import_lb1.jpg', 'class' => 'contactsImport'));
						echo $this->Form->end();
					?>
					<div id="importHotmailError" style="display: none;"><font color="red"> Fill All Fields Required. </font></div>
	            </div>
	
				<h3><a href="#">Import from Aol</a></h3>
	            <div>
	            	<img src="../images/email/aol.jpg" />
					<?php
						echo $this->Form->create('Referral', array('url' => 'import_contacts', 'id' => 'importAol'));
					?>
					<p>E-mail:</p>
	                <p><?php echo $this->Form->input('login', array('label' => false, 'class' => 'required')); ?></p>
	                <p>Password:</p>
	                <p><?php echo $this->Form->input('password', array('class' => 'required', 'label' => false)); ?></p>
					<?php 
						echo $this->Form->hidden('service', array('value' => 'Aol'));
						echo $this->Form->submit(__('Import Contacts', true), array('class' => 'contactsImport'));
						//echo $this->Form->submit('', array('type'=>'image','src' => '/images/buttons/import_lb1.jpg', 'class' => 'contactsImport'));
						echo $this->Form->end();
					?>
					<div id="importAolError" style="display: none;"><font color="red"> Fill All Fields Required. </font></div>	
	            </div>
	
	            <h3><a href="#">Import from Outlook</a></h3>
	            <div>
	            	<img src="../images/email/outlook.gif" />
					<?php
						echo $this->Form->create('Referral', array('url' => 'import_contacts', 'type' => 'file', 'id' => 'importOutlook'));
					?>
					<p>Download csv file:</p>
					<?php 
						echo $this->Form->file('outlook', array('class' => 'required'));
						echo $this->Form->hidden('service', array('value' => 'Outlook'));
						echo $this->Form->submit(__('Import Contacts', true), array('class' => 'contactsImport'));
						//echo $this->Form->submit('', array('type'=>'image','src' => '/images/buttons/download_lb1.jpg', 'class' => 'contactsImport'));
						echo $this->Form->end();
					?>
					
					<div id="importOutlookError" style="display: none;"><font color="red"> Fill All Fields Required. </font></div>	
	            </div>
	        </div>
	    </div>
	</div>    
    <div id="dialog-overlay" class="dialog-overlay"></div>
</div>	

<script type="text/javascript">

	$("a.import").fancybox();

	$('a.import').find('img').each(function(i, e) {
        $(e).click(function() {
            $('#import-dialog-accordion').accordion({
            			clearStyle: true, 
                		autoHeight: false,
                		animated: false,
                		active: i
                	});
        });
    });

	$(".contactsImport").live("click", function(e) {
		var formId = $(this).parents('form:first').attr('id');
		var inputs = $('#'+ formId +' :input');
		inputs.each(function() {
			if($(this).val() == '' && $(this).attr('id') != '') {
				$('#' + formId + 'Error').show();
				e.preventDefault();
			}
		});
	});

</script> */ ?>