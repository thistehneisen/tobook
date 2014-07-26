<?php
class Link extends AppModel {
	var $name = 'Link';
	var $validate = array(
		'mails_id' => array(
			'numeric' => array(
				'rule' => array('numeric'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed
/*
	var $belongsTo = array(
		'Mail' => array(
			'className' => 'Mail',
			'foreignKey' => 'mail_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);*/
}
?>