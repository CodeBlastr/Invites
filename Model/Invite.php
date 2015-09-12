<?php
App::uses('InvitesAppModel', 'Invites.Model');

class AppInvite extends InvitesAppModel {
/**
 * Name
 *
 * @var string $name
 * @access public
 */
	public $name = 'Invite';
	
	public $actsAs = array('Metable');

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
		),
		'Creator' => array(
			'className' => 'Users.User',
			'foreignKey' => 'creator_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * After find callback
 * 
 */
 	public function afterFind($results = array(), $primary = false) {
 		// decode data field
		if (!empty($results[0])) {
			// many results
	 		for ($i=0; $i < count($results); $i++) {
	 			$results[$i][$this->alias]['data'] = json_decode($results[$i][$this->alias]['data'], true);
			}
		} elseif (!empty($results[$this->alias])) {
			// just one
	 		$results[$this->alias]['data'] = json_decode($results[$this->alias]['data'], true);
		}
		return parent::afterFind($results, $primary);
 	}

/**
 * Process Invite Data
 */
 	public function processInvite($inviteId, $userId = null) {
 		$userId = !empty($userId) ? $userId : CakeSession::read('Auth.User.id');
		$invite = $this->find('first', array('conditions' => array('Invite.id' => $inviteId, 'Invite.status' => 0)));
		if(!empty($invite)) {
			App::uses($invite['Invite']['model'], ZuhaInflector::pluginize($invite['Invite']['model']) . '.Model');
			$Model = new $invite['Invite']['model']();
	 		if(method_exists($Model,'processInvite') && is_callable(array($Model, 'processInvite'))) {
				if ($Model->processInvite($invite, $userId)) {
					$this->_updateStatus($inviteId, 1, $userId);
				}
			} else {
				$this->_updateStatus($inviteId, 1, $userId);
			}
		} else {
			throw new Exception(__('Invite invalid'));
		}
	}

/**
 * Update Status method
 * 
 */
	public function _updateStatus($inviteId, $status, $userId = null) {
		$invite = $this->find('first', array('conditions' => array('Invite.id' => $inviteId)));
		if(!empty($invite) && $invite['Invite']['status'] <= 0){
			$data = array(
				'Invite'=> array(
					'id' => $invite['Invite']['id'],
					'status' => $status,
					'user_id' => $userId,
				),
			);
			return $this->save($data);
		}
		return false;
	}

/**
 * Accept method
 */
	public function accept($inviteId, $userId = null){
		$this->_updateStatus($inviteId, 1, $userId);
	}

/**
 * Decline method
 * 
 * @param $inviteId
 */
	public function decline($inviteId, $userId = null){
		$this->_updateStatus($inviteId, -1, $userId);
	}

/**
 * Find All Accept Use Id by Task Id
 */
	public function findAllAcceptUserIdByTaskId($taskId, $creator_id){
		return $this->find('all',
			array(
				'conditions'=>array('Invite.foreign_key' => $taskId,
					'Invite.status'=>1,'Invite.creator_id' => $creator_id,
					'Invite.user_id !='=> null,
					'Invite.user_id !='=> $creator_id,
				)
			));
	}

}

if (!isset($refuseInit)) {
	class Invite extends AppInvite {}
}