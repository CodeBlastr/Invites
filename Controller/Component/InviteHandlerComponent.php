<?php

class InviteHandlerComponent extends Object {

/**
 * Component name
 *
 * @var string
 * @access public
 */
	var $name = 'InviteHandler';

/**
 * Components
 *
 * @var array
 * @access public
 */
	var $components = array('Session', 'Email');    
	
	function __construct(ComponentCollection $collection, $settings = array()) {
        parent::__construct($collection, $settings);
    }
	
	function initialize($controller) { }
	function beforeRedirect() { }
	function beforeRender($viewFile) { }
	function shutdown() { }

/**
 * startup callback
 *
 * @return void
 */
 	function startup(&$controller) {
		Configure::load('Invites.invite');	// make configuration available
 		$this->controller =& $controller;	// make calling controller available in this component
 	}

/**
 * ONLY edit this function if your app uses another method for providing the user-id
 *
 * @return string
 * @access public
 */
	public function getUserId(){
		return $this->controller->Auth->user('id');
	}

}