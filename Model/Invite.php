<?php
App::uses('InvitesAppModel', 'Invites.Model');

class Invite extends InviteAppModel {
/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'Invite';

/**
 * belongsTo association
 *
 * @var array $belongsTo 
 * @access public
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}