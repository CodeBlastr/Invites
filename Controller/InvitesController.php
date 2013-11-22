<?php
App::uses('InvitesAppController', 'Invites.Controller');

class InvitesController extends InvitesAppController {

/**
 * Controller name
 *
 * @var string
 * @access public
 */
	public $name = 'Invites';
	public $uses = 'Invites.Invite';

/**
 * Components used by this controller
 *
 * @var array
 * @access public
 */
	public $components = array( 'Auth', 'Session', 'Invites.InviteHandler');
	public $helpers = array('Session');
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

	public function __construct($request = null, $response = null) {
		if (CakePlugin::loaded('Facebook')) {
			$this->helpers[] = 'Facebook.Facebook';
		}
		parent::__construct($request, $response);
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

/**
 * Invitation method
 */
	public function invitation($model = false, $foreignKey = false) {
		if (isset($this->request->data['Invite']['emails'])) {
			$toemails = explode(",", trim($this->request->data['Invite']['emails'], ","));
			$emails = array();
			$emails_tosave = '';
			foreach ($toemails as $email) {
				$email = trim($email);
				if (Validation::email($email)) {
					$emails[$email] = '';	//since no name is specified
					$emails_tosave = $email.",";
				}
			}
			
			if (empty($emails)) {
				$this->Session->setFlash('No valid email addresses provided.', 'flash_danger');
			} else {
				$emails_tosave = trim($emails_tosave,",");
				$user_id = $this->InviteHandler->getUserId();
				$reference_code = $this->Invite->User->generateReferalCode($user_id);
				$this->request->data['Invite']['user_id'] = $user_id;
				$this->request->data['Invite']['emails'] = $emails_tosave;
				if ($this->Invite->save($this->request->data)) {
					$subject = Inflector::humanize($_SERVER['SERVER_NAME']);
					$message = 'Join me over at '.Inflector::humanize($_SERVER['SERVER_NAME']).'! http://' . $_SERVER['SERVER_NAME'] . '/users/users/register/referal_code:' . $reference_code;
					$sentCount = $this->__sendMail($emails, $subject, $message, 'welcome');
					$this->Session->setFlash('Invitation sent to '.$sentCount.' persons.', 'flash_success');
				}
			}
			
		} else {
			if (!empty($this->request->data['Referral'])) {
				// check if email value is zero
				$emails = array();
				foreach ($this->request->data['Referral'] as $contact_key => $contact) {
					if ($contact['email'] == '0') {
						unset($this->request->data['Referral'][$contact_key]);
					} else {
						$emails[$contact_key] = $contact;
					}
				}

				$email_str = '';
				// create a formatted string to send emails
				foreach ($emails as $email) {
					$email_str .= $email['email'] . ',';
				}
				$this->request->data['Invite']['emails'] = $email_str;
			}
		}

		$this->set('related', ($model && $foreignKey) ? array('model' => $model, 'foreign_key' => $foreignKey) : false);
		
		
		$fb_invite_info = array();
		$server_name = env("SERVER_NAME");
		$user_id = $this->InviteHandler->getUserId();
		$reference_code = $this->Invite->User->generateReferalCode($user_id);

		if (CakePlugin::loaded('Facebook')) {
			$fb_invite_info['fb_invite_action'] = "http://".$server_name;
			$fb_invite_info['fb_invite_action_text'] = "Invite your friends to ".Inflector::humanize($_SERVER['SERVER_NAME'])."!";
			$fb_invite_info['fb_invite_content'] = 'Join me over at '.Inflector::humanize($_SERVER['SERVER_NAME']).' <a href="http://' . $_SERVER['SERVER_NAME'] . '/users/users/register/referal_code:' . $reference_code. '">by clicking here.</a>';
			$fb_invite_info['fb_req_choice_yes_url'] = "http://".$server_name;
			$fb_invite_info['fb_req_choice_no_url'] = "http://".$server_name;
			$fb_invite_info['fb_before_login_invite_text'] = "If you have a facebook account, you can easily invite your friends and email contacts to join you on your ".Inflector::humanize($_SERVER['SERVER_NAME'])." account.";
			$this->set(compact('fb_invite_info'));
		}

	}

/**
 * Import Contacts is used to get contacts from different
 * email services eg. gmail, yahoo, hotmail
 * returns contacts array()
 */
	public function import_contacts(){
		$request = array();

		$login = isset($this->request->data['Referral']['login']) ? $this->request->data['Referral']['login'] : '' ;
		$password = isset($this->request->data['Referral']['password']) ? $this->request->data['Referral']['password'] : '' ;
		$service = isset($this->request->data['Referral']['service']) ? $this->request->data['Referral']['service'] : $this->passedArgs['service'] ;

		switch ($service) {
			case 'Gmail':

				#include vendor file because two files of same vendor can't includes at same time.
				include_once '..' . DS . '..' . DS . 'vendors' . DS . 'Svetlozar' . DS . 'init.php';
				App::import('Vendor','Svetlozar', array('file' => 'Svetlozar' . DS . 'Contacts' . DS . 'Gmail.php'));
				$gmail = new Gmail($login, $password);

				$contacts_array = $gmail->contacts ;

				if(!empty($contacts_array)) {
					foreach($gmail->contacts as $con_key => $con_val) {
						$contacts[$con_key] = array(
									'first_name' => $gmail->names[$con_key],
									'email' => $gmail->emails[$con_key],
								);
					}
					$request['users'] = $contacts;
				} else {
					//$request['error'] = "Login Failed.";
					$this->Session->setFlash(__('Login Failed.', true));
					$this->redirect(array('action' => 'invitation'));
				}
				break;

		    case 'Yahoo':
				App::import('Vendor','Refer', array('file' => 'Refer' . DS . 'Yahoo.php'));
				try {
				    $yahoo = new Core_Yahoo();
				    $yahoo->service = 'addressbook';
				    $yahoo->cookieJarPath = getcwd() .DS. '..' .DS. 'tmp'.DS ;
				    $contacts = $yahoo->execService($login, $password);
				    if ($yahoo->errorInfo) {
				        throw new Exception($yahoo->errorInfo);
				    }
				    $sorted_contacts = array();
				    foreach($contacts as $contact){
				    	$sorted_contacts[] = array('first_name' => $contact['first_name'],
				    			'email' => $contact['email_1']);
				    }
				    $request['users'] = $sorted_contacts;
				} catch (Exception $e) {
				    //$request['error'] = $e->getMessage();
				    $this->Session->setFlash(__($e->getMessage(), true));
					$this->redirect(array('action' => 'invitation'));
				}
		        break;
		    case 'Aol':
				App::import('Vendor','Refer', array('file' => 'Refer' . DS . 'Aol.php'));
				try {
				    $aol = new Core_Aol();
				    $aol->settings = array(
				        "username" => "rewolf",//api openinviter username
				        "private_key" => "89b3a9414c12a20ab36748593a7717cb",//api key
				        //"cookie_path" => ROOT_DIR . '/public/coockies',
				        "cookie_path" => getcwd() .DS. '..' .DS. 'tmp'.DS ,
				        "transport" => "curl",
				        "local_debug" => "on_error",
				        "remote_debug" => false,
				        "proxies" => array(),
				    );
				    if (!$aol->login($login, $password)) {
				        throw new Exception(
				            'Could not login to server, check your credentials.'
				        );
				    }
				    $contacts = $aol->getMyContacts();
				    if (!$contacts) {
				        throw new Exception('Your contact list is empty.');
				    }

				    //sorting contacts in required format
					$sorted_contacts = array();
				    foreach($contacts as $contact_email => $contact_name){
				    	if($contact_email != $contact_name) {
				    		$sorted_contacts[] = array('first_name' => $contact_name,
				    			'email' => $contact_email);
				    	} else {
				    		$sorted_contacts[] = array('first_name' => '',
				    			'email' => $contact_email);
				    	}
				    }
				    //set sorted contacts in $request variable
				    $request['users'] = $sorted_contacts;
				} catch (Exception $e) {
				    //$request['error'] = $e->getMessage();
				    $this->Session->setFlash(__($e->getMessage(), true));
					$this->redirect(array('action' => 'invitation'));
				}
		        break;
		    case 'Hotmail':
		    	App::import('Vendor','Refer', array('file' => 'Refer' . DS . 'Hotmail.php'));
		    	$import = new Core_Hotmail();
				$import->TempDir = getcwd() .DS. '..' .DS. 'tmp'.DS ;
				$import->returnURL = Router::url('/invite/invites/import_contacts/return:true/service:Hotmail', true) ;
				$import->WLLPolicy = Router::url('/', true) . 'privacy.php';
				$import->WLLAPIid = '000000004004B922';
				$import->WLLSecret = 'mo2NaE4fgPn2Km6UW2zpirBd4FnNVFSr';

				if (!isset($this->passedArgs['return'])){
					header("Location: {$import->getWLLLink()}");
				} else {
					$contacts  = $import->getContacts();
				}

				$sorted_contacts = array();
				foreach($contacts as $contact) {
				    $sorted_contacts[] = array(
				    		'first_name' => $contact->name,
				    		'email' => $contact->email
				    );
				}
				$request['users'] = $sorted_contacts;
				break;

			case 'Outlook':

		    	App::import('Vendor','Varien', array('file' => 'Varien' . DS . 'Csv.php'));

				$parser = new Varien_File_Csv();
				$data = $parser->getData($this->request->data['Referral']['outlook']['tmp_name']);

				foreach ($data as $i => $row) {
				    if (count($row) < 3) {
				        unset($data[$i]);
				        continue;
				    }
				    $data[$i]['2'] = reset(explode(' ', $row['2']));
				}

				$result = array();
				if ($data) {
				    $fields = $data[0];
				    unset($data[0]);
				    if ($data) {
				        foreach ($data as $row) {
				            $_row = array();
				            foreach ($row as $i => $value) {
				                if ($value) {
				                    $_row[$fields[$i]] = $value;
				                }
				            }
				            $result[] = $_row;
				        }
				    }
				}

				$emails = array();
				foreach ($result as $user) {
				    $name = trim($user['Name']);
				    $name = preg_replace('/[;,]/', '', $name);
				    $emails[] = array(
				        'first_name' => $name,
				        'email' => trim($user['E-mail']),
				    );
				}

				$request['users'] = $emails;
				break;
		}

		/*
		//check if user already invited then remove from imported list
		if(isset($request['users'])) {
			$referrals = $this->Referral->find('list', array('conditions' => array(
								'Referral.account_id' => $this->Auth->user('id')
							)));
			foreach($request['users'] as $ckey => $contacts) {
				if(in_array($contacts['email'], $referrals))
					unset($request['users'][$ckey]) ;
			}
		}
		*/

		$this->set('contacts', $request);
	}

}
