<?php

class Category extends AppModel {

    var $name = 'Category';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $validate = array('Category' => array('rule' => 'checkCategory'),
        'name' => array(
            'notempty' => array(
                'rule' => array('notempty'),
                'message' => 'This field cannot be left blank',
            //'allowEmpty' => false,
            //'required' => false,
            //'last' => false, // Stop validation after this rule
            //'on' => 'create', // Limit validation to 'create' or 'update' operations
            ),
            ));
    var $virtualFields = array(
        'fullname' => "CONCAT(Category.name, ' (',(SELECT COUNT(*) AS count FROM `categories_subscribers` AS `CategoriesSubscriber` LEFT JOIN `subscribers` AS `Subscriber` ON (`Subscriber`.`id` = `CategoriesSubscriber`.`subscriber_id`) WHERE `Subscriber`.deleted=0 and category_id = `Category`.`id` ),' / ',(SELECT COUNT(*) AS count FROM `categories_subscribers` AS `CategoriesSubscriber` LEFT JOIN `subscribers` AS `Subscriber` ON (`Subscriber`.`id` = `CategoriesSubscriber`.`subscriber_id`) WHERE category_id = `Category`.`id` ),')')"
    );

    function checkCategory() {

        if (!empty($this->data['Category']['Category'])) {
            return true;
        }

        return false;
    }

    var $hasAndBelongsToMany = array(
        'Subscriber' => array(
            'className' => 'Subscriber',
            'joinTable' => 'categories_subscribers',
            'foreignKey' => 'category_id',
            'associationForeignKey' => 'subscriber_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => 1,
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ),
        'Mail' => array(
            'className' => 'Mail',
            'joinTable' => 'mails_categories',
            'foreignKey' => 'category_id',
            'associationForeignKey' => 'mail_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => 1,
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
    public function afterDelete() {
        $this->query("DELETE FROM `newsletter`.`categories_subscribers` WHERE `categories_subscribers`.`category_id` = ".$this->id.";");
        parent::afterDelete();
    }
}

?>