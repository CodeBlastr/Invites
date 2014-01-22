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




	private function _updateStatus($inviteId,$status,$userId = null){
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

	public function accept($inviteId,$userId = null){
		$this->_updateStatus($inviteId,1,$userId);
	}

	public function decline($inviteId,$userId = null){
		$this->_updateStatus($inviteId,-1,$userId);
	}

	public function findAllAcceptUserIdByTaskId($taskId,$creator_id){
		return $this->find('all',
			array(
				'conditions'=>array('Invite.foreign_key'=>$taskId,
					'Invite.status'=>1,'Invite.creator_id'=>$creator_id,
					'Invite.user_id !='=> null,
					'Invite.user_id !='=> $creator_id,
				),

			));
	}

}
