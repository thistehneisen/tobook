<?php namespace App\Lomake\Fields;

class HtmlField extends Text
{
    const NAME_SUFFIX = '_html';

    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        \App\Lomake\Lomake::addRequiredJs('assets/ckeditor/ckeditor.js');

        $params = $this->getParams();
        $params[0] .= self::NAME_SUFFIX;

        return call_user_func_array('Form::textarea', $params);
    }

    /**
     * Populate properties with data from given options
     *
     * @param array $opt
     *
     * @return void
     */
    protected function init($opt)
    {
        if (empty($opt['default'])) {
            throw new \UnexpectedValueException('HtmlField must be configured with `default` option.');
        }

        parent::init($opt);

        if (empty($this->options['class'])) {
            $this->options['class'] = '';
        }
        $this->options['class'] .= ' ckeditor';
    }

    /**
     * Filter html from user input.
     * This method will strip out all but white-listed tags for security reason.
     *
     * @param array $inputAll
     * @param string $name
     *
     * @return string
     */
    public static function filterInput(array $inputAll, $name)
    {
        $nameWithSuffix = $name . self::NAME_SUFFIX;

        if (empty($inputAll[$nameWithSuffix])) {
            return '';
        }

        $html = $inputAll[$nameWithSuffix];

        $html = strip_tags($html, '<p><strong><em><ul><ol><li><a>');

        return $html;
    }
}
