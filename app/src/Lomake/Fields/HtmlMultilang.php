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
        $params = $this->getParams();

        $languages = Config::get('varaa.languages');
        foreach ($languages as $lang) {
            $params[0] .= self::NAME_SUFFIX."[$lang]";
            $fields[] = [
                'lang'     => $lang,
                'title'    => trans('home.languages.'.$lang),
                'textarea' => call_user_func_array('Form::textarea', $params),
            ];
        }

        return \View::make('varaa-lomake::fields.html_multilang', [
            'fields' => $fields
        ])->render();
    }
}
