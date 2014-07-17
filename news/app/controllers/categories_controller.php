<?php

class CategoriesController extends AppController {

    var $name = 'Categories';

    function index() {
        $this->Category->recursive = 0;
        $this->set('categories', $this->paginate());
    }

    function split($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid category', true));
            $this->redirect(array('action' => 'index'));
        }
        $cat = $this->Category->read(null, $id);
        $this->Category->create();
        $data["Category"]["name"] = $cat["Category"]["name"] . " (A)";
        if ($this->Category->save($data)) {
            $count = $this->Category->query("(select count( * ) as k from categories_subscribers WHERE category_id=" . $id . ")");

            $this->Category->query("INSERT INTO
categories_subscribers
(
category_id,
subscriber_id
)
(
SELECT
" . $this->Category->id . " as `category_id`,
`subscriber_id`
FROM
categories_subscribers
WHERE
`category_id`=" . $id . " ORDER BY RAND() LIMIT " . floor($count[0][0]["k"] / 2) . ")");
            $old_id = $this->Category->id;
            $this->Category->create();
            $data["Category"]["name"] = $cat["Category"]["name"] . " (B)";
            if ($this->Category->save($data)) {
                $this->Category->query("INSERT INTO
categories_subscribers
(
category_id,
subscriber_id
)
(
SELECT
" . $this->Category->id . " as `category_id`,
t1.subscriber_id
FROM
categories_subscribers as t1
WHERE
`category_id`=" . $id . " and (select count(*) from categories_subscribers where t1.subscriber_id=subscriber_id and category_id=" . $old_id . "  )=0 ORDER BY RAND() )");
                $this->Session->setFlash(__('Category split', true));
                $this->redirect(array('action' => 'index'));
            }
        }
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid category', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->set('category', $this->Category->read(null, $id));
    }

    function add() {
        if (!empty($this->data)) {
            $this->Category->create();

            if ($this->Category->save($this->data)) {
                $this->Session->setFlash(__('The category has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The category could not be saved. Please, try again.', true));
            }
        }
 
        $mails = $this->Category->Mail->find('list');
        $this->set(compact(  'mails'));
    }

    function edit($id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid category', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            if ($this->Category->save($this->data)) {
                $this->Session->setFlash(__('The category has been saved', true));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The category could not be saved. Please, try again.', true));
            }
        }
        if (empty($this->data)) {
            $this->Category->recursive = 0;
            $this->data = $this->Category->read(null, $id);
        }
 
        $mails = $this->Category->Mail->find('list');
        $this->set(compact(  'mails'));
    }

    function delete($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for category', true));
            $this->redirect(array('action' => 'index'));
        }
        if ($this->Category->delete($id)) {
            $this->Session->setFlash(__('Category deleted', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Category was not deleted', true));
        $this->redirect(array('action' => 'index'));
    }

}

?>