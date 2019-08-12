<?php
namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Field;

class CKEditor extends Field
{
    public static $js = [
        '/packages/ckeditor/ckeditor.js',
        '/packages/ckeditor/adapters/jquery.js',
    ];

    protected $view = 'admin.ckeditor';

    public function render()
    {
        $className = 'custom-ckeditor';
        $this->addElementClass($className);
        $this->script = "$('textarea." . $className . "').ckeditor();";
        return parent::render();
    }
}
