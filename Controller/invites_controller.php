<?php

class InvitesController extends InviteAppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	var $name = 'Invites';

/**
 * Components used by this controller
 *
 * @var array
 * @access public
 */
	var $components = array( 'Auth', 'Session', 'Invite.InviteHandler');

/**
 * beforeFilter callback
 *
 * If 'usesAuth' is true in the config file we allow access to the accept_invitation() function
 * so anonymous users will be able to validate their email_token.
 *
 * @return void
 * @access public
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('index'));
	}


/**
 * Display the basic invitation-screen /views/invite/index.ctp 
 *
 * Customize this page by either:
*  - editing the existing plugin view: /app/plugins/invite/views/invite/index.ctp
*  - creating a new view: /app/views/plugins/invite/invite/index.ctp (recommended)
 *
 * @param none
 * @return void
 * @access public
 */
	public function index(){		
	}

	function invitation() {		
		
		//copy implementation fronm the following function	
		if(isset($this->data)){		
			$toemails = explode(",",trim($this->data['Invite']['emails'],","));
			$emials = array();
			$emails_tosave = '';
			foreach($toemails as $email){
				//TODO:validate $email
				$emails[$email] = '';	//since no name is specified
				$emails_tosave = $email.",";			
			}
			$emails_tosave = trim($emails_tosave,",");
			$this->data['Invite']['user_id'] = $this->InviteHandler->getUserId(); 
			$this->data['Invite']['emails'] = $emails_tosave;
			if($this->Invite->save($this->data)){
				$subject = Inflector::humanize($_SERVER['SERVER_NAME']);
				$message = 'Join me over at '.Inflector::humanize($_SERVER['SERVER_NAME']).'! http://' . $_SERVER['SERVER_NAME'];
				$sentCount = $this->__sendMail($emails, $subject, $message,  'welcome');
				$this->Session->setFlash('Invitation sent to '.$sentCount.' persons.');	
			}
		}	
		
		$fb_invite_info = array();
		$server_name = env("SERVER_NAME");
		
		$fb_invite_info['fb_invite_action']="http://".$server_name;
		$fb_invite_info['fb_invite_action_text']="Invite your friends to ".Inflector::humanize($_SERVER['SERVER_NAME'])."!";
		$fb_invite_info['fb_invite_content']="Would you like to join me at ".Inflector::humanize($_SERVER['SERVER_NAME'])."?";
		$fb_invite_info['fb_req_choice_yes_url'] = "http://".$server_name;
		$fb_invite_info['fb_req_choice_no_url']= "http://".$server_name;
		$fb_invite_info['fb_before_login_invite_text']="If you have a facebook account, you can easily invite your friends and email contacts to join you on your ".Inflector::humanize($_SERVER['SERVER_NAME'])." account.";
			
		
		$this->set(compact('fb_invite_info'));	
	}

}