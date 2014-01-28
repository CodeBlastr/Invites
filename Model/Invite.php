<?php
App::uses('InvitesAppModel', 'Invites.Model');

class Invite extends InvitesAppModel {
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
		)
	);

/**
 * Process Invite Data
 */
 	public function processInvite($inviteId) {
		$invite = $this->find('first', array('conditions' => array('Invite.id' => $inviteId, 'Invite.status' => 0)));
		if(!empty($invite)) {
			App::uses($invite['Invite']['model'], ZuhaInflector::pluginize($invite['Invite']['model']) . '.Model');
			$Model = new $invite['Invite']['model']();
	 		if(method_exists($Model,'processInvite') && is_callable(array($Model, 'processInvite'))) {
				if ($Model->processInvite($invite)) {
					$this->_updateStatus($inviteId, 1, CakeSession::read('Auth.User.id'));
				}
			} else {
				$this->_updateStatus($inviteId, 1, CakeSession::read('Auth.User.id'));
			}
		} else {
			throw new Exception(__('Invite invalid'));
		}
	}

/**
 * Update Status method
 * 
 */
	private function _updateStatus($inviteId, $status, $userId = null){
		$row = $this->read(array('status'),$inviteId);
		if(!empty($row) && $row['Invite']['status'] <= 0){
			$data = array(
				'Invite'=>array(
					'status'=> $status,
					'user_id'=>$userId,
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
		$this->_updateStatus($inviteId,-1,$userId);
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
