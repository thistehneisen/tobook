<?php namespace App\Lomake\Fields;

use App;
use Config;

class TextMultilang extends Text
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $fields = [];

        $languages = Config::get('varaa.languages');
        foreach ($languages as $lang) {
            $params = $this->getParams();
            // Change the name of each control to its own language
            $params[0] = $this->opt['name']."[$lang]";

            // Then change the content
            $method = 'getAttributeInLanguage';
            // Call the method to get value for textarea
            $params[1] = $this->opt['model']->$method($this->opt['name'], $lang);
            $fields[] = [
                'lang'    => $lang,
                'title'   => strtoupper($lang),
                'name'    => $this->opt['name'],
                'control' => call_user_func_array('Form::text', $params),
            ];
        }

        return \View::make('varaa-lomake::fields.text_multilang', [
            'fields' => $fields,
            'locale' => App::getLocale(),
        ])->render();
    }
}
