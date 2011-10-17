<?php
/**
 * Configuration setting for Invite plugin
 *
 */
$config['Invite'] = array(

/**
 * Enable or disable the invitation plugin
 */
  	'enabled' => true,

/**
 * Set the maximum number of invitations a user can send here
 */
  	'max_invitations' => 10,

/**
 * Set the length of the emailed invitation-token here.
 *
 * Will default to 15 if not set
 */
  	'token_length' => 20,

/**
 * Name of the controller and action used for user-registration. Used after successfull token-validation
 */
	'register_controller' => 'yourcontroller',
	'register_action' => 'youraction',

/**
 * Set 'token_expires' for the number of days the token remains valid before expiring
 *
 * Incorrect timestamps are related to your timezone settings. Fix by either:
* - uncommenting the timezone setting in /app/config/core.php  (PHP =>5.3)
* - setting the correct timezone yourself (http://php.net/manual/function.date-default-timezone-set.php)
*/ 
	'token_expires' => 7,

/**
 * Define email options here.
 * 
 * These standard Email Component settings are explained at http://book.cakephp.org/view/1284/Class-Attributes-and-Variables
 *
 * Exceptions are:
 * - subject: this cannot be set here. It is hardcoded, gets translated from the .pot file and replaces %s with the name of the invitor
 * - layout: simply create your own template in /app/views/plugins/invite/layouts/email/html/your_cool_layout.ctp
 * - template: simply create your own template in /app/views/plugins/invite/email/invitation.ctp
 */
	'email_options' => array(
		'from'		=> 'Zuha Join My RFQ',
		'return'		=> 'faheem@enbake.com',
		'sendAs'	=> 'both',
		'delivery'	=> 'mail',
		'layout'		=> null,
	),

/**
 * Define the css-classes you would like to use for the 3 states of CakePHP flashmessages
 */
	'flash' => array(
		'info' => 'info',
		'warning' => 'warning',
		'success' => 'success'
	),

);