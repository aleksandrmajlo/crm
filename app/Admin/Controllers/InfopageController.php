<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;


class InfopageController extends Controller
{

    use HasResourceActions;

    public function index(Content $content){
        $Infopage=\App\Infopage::class;
        return $content
            ->header('Infopage')
            ->description(' ')
            ->row(function (Row $row) use ($Infopage) {

                $tab = new Tab();

                $form = new Form();
                $type=$Infopage::where('alias',"home")->first();
                $form->ckeditor('text','Text')->default($type->text);
                $form->text('h1', 'H1')->default($type->h1);
                if(!empty($type->image)){
                    $form->html('<img class="img-thumbnail" src="'.$type->image.'">');
                }
                $form->image('image', 'Image')->default($type->image);
                $form->text('meta_title', 'Meta title')->default($type->meta_title);
                $form->textarea('meta_description', 'Meta description')->default($type->meta_description);
                $form->html('<input type="hidden" name="type" value="home"> ');
                $form->action('infopages')->disablePjax();
                $tab->add('Home', $form->render());


                $form = new Form();
                $type=$Infopage::where('alias',"contact")->first();
                $form->ckeditor('text','Text')->default($type->text);
                $form->text('h1', 'H1')->default($type->h1);
                if(!empty($type->image)){
                    $form->html('<img class="img-thumbnail" src="'.$type->image.'">');
                }
                $form->image('image', 'Image')->default($type->image);
                $form->text('meta_title', 'Meta title')->default($type->meta_title);
                $form->textarea('meta_description', 'Meta description')->default($type->meta_description);
                $form->html('<input type="hidden" name="type" value="contact"> ');
                $form->action('infopages')->disablePjax();
                $tab->add('Contact', $form->render());


                $form = new Form();
                $type=$Infopage::where('alias',"help")->first();
                $form->ckeditor('text','Text')->default($type->text);
                $form->text('h1', 'H1')->default($type->h1);
                if(!empty($type->image)){
                    $form->html('<img class="img-thumbnail" src="'.$type->image.'">');
                }
                $form->image('image', 'Image')->default($type->image);
                $form->text('meta_title', 'Meta title')->default($type->meta_title);
                $form->textarea('meta_description', 'Meta description')->default($type->meta_description);
                $form->html('<input type="hidden" name="type" value="help"> ');
                $form->action('infopages')->disablePjax();
                $tab->add('Help', $form->render());


                $row->column(12, $tab);



            });

    }

    public function update(Request $request){

        $type=$request->type;
        $infopage=\App\Infopage::where('alias',$type)->first();
        $infopage->text=$request->text;
        $infopage->h1=$request->h1;
        $infopage->meta_title=$request->meta_title;
        $infopage->meta_description=$request->meta_description;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $destinationPath = public_path('uploads');
            $name=$file->getClientOriginalName();
            $file->move($destinationPath,$name);
            $infopage->image='/uploads/'.$name;
        }


        $infopage->save();

        return redirect()->back()->with('message', 'Update!');

    }


}