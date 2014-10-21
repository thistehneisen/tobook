<?php namespace App\Lomake\Fields;

class HtmlField extends Text
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        \App\Lomake\Lomake::addRequiredJs('assets/ckeditor/ckeditor.js');
        return call_user_func_array('Form::textarea', $this->getParams());
    }

    protected function init($opt)
    {
        parent::init($opt);

        if (empty($this->options['class'])) {
            $this->options['class'] = '';
        }
        $this->options['class'] .= ' ckeditor';
    }
}
