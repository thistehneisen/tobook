<?php namespace App\Lomake\Fields;

use Config;

class HtmlMultilang extends HtmlField
{
    const NAME_SUFFIX = '_html';

    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $fields = [];

        \App\Lomake\Lomake::addRequiredJs('packages/ckeditor/ckeditor.js');

        $languages = Config::get('varaa.languages');
        foreach ($languages as $lang) {
            $params = $this->getParams();
            // Change the name of each textarea to its own language
            $params[0] = $this->opt['name'].self::NAME_SUFFIX."[$lang]";

            // Then change the content
            $method = 'get'.studly_case($this->opt['name']).'InLanguage';
            if (!method_exists($this->opt['model'], $method)) {
                throw new \RuntimeException('Cannot find method '.$method.'()');
            }

            // Call the method to get value for textarea
            $params[1] = $this->opt['model']->$method($lang);
            $fields[] = [
                'lang'     => $lang,
                'title'    => strtoupper($lang),
                'textarea' => call_user_func_array('Form::textarea', $params),
            ];
        }

        return \View::make('varaa-lomake::fields.html_multilang', [
            'fields' => $fields
        ])->render();
    }
}
