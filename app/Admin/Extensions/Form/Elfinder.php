<?php
namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Field;
use Illuminate\Support\Facades\Storage;

class Elfinder extends Field
{
    protected static $css = [
        '/assets/css/colorbox.css'
    ];
    public static $js = [
        '/assets/js/jquery.colorbox-min.js',
        '/packages/barryvdh/elfinder/js/standalonepopup.js',
    ];
    protected $view = 'admin.elfinder';
    public function render()
    {
        $publicDirectoryUrl = Storage::disk('public')->url('');
        $this->script = "
        var publicDirectory = '" . $publicDirectoryUrl . "';
        var groups = $('.elfinder-group');
        for(var i = 0; i < groups.length; i++){
            var id = 'elfinder-group-' + i;
            $(groups[i]).find('input').attr('id', id);
            $(groups[i]).find('img').attr('id', 'thumb_' + id);
            $(groups[i]).find('a').attr('data-inputid', id);
        }
        
        $(document).on('change', '.elfinder-input', function (e) {
            var imgPath = $(this).val();
            var imgTag = $(this).next();
            imgTag.attr('src', publicDirectory + imgPath);
				});
				$(document).on('click', '[data-resset=img]', function (e) {
					e.preventDefault();
					var elfinderInput = $(this).closest('.elfinder-group__inner').find('.elfinder-input'),
							elfinderImgPath = $(this).closest('.elfinder-group__inner').find('img');
							elfinderInput.attr('src', '');
							elfinderImgPath.attr('src', '');
				});";
        return parent::render();
    }
}
