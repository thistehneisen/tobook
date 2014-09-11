<?php
namespace App\MarketingTool\Controllers;

use Input, Session, Redirect, View, Validator, Response, File;
use \App\MarketingTool\Models\Template as TemplateModel;
use Confide;

class Template extends \App\Core\Controllers\Base {

    public function __construct() {
        $user_id = Confide::user()->id;
        File::makeDirectory(public_path()."/assets/img/templates/".$user_id, 0775, true, true);
    }    
    
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    protected $path = '/assets/img/templates/thumbs';
    public function index()
    {
        // get all the templates
        $templates = TemplateModel::all();

        // load the view and pass the templates
        return View::make('modules.mt.templates.index')
            ->with('templates', $templates);
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('modules.mt.templates.create');
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        
        $rules = [
            'name'       => 'required',
            'content'    => 'required',
            'thumbnail'  => 'required|image',
        ];

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $template = new TemplateModel;
            $template->name = Input::get('name');
            $template->content = Input::get('content');
                        
            if (Input::hasFile('thumbnail')) {
                $filename = str_random(24);
                $extension = Input::file('thumbnail')->getClientOriginalExtension();
                $destination = base_path()."/public".$this->path;                
                Input::file('thumbnail')->move($destination, $filename.".".$extension);
                $template->thumbnail = $this->path."/".$filename.".".$extension;
            }

            $template->user_id = Confide::user()->id;
            $template->save();

            Session::flash('message', 'Successfully created!');
            return Redirect::route('mt.templates.index');
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $template = TemplateModel::find($id);
    
        return View::make('modules.mt.templates.show')
            ->with('template', $template);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $template = TemplateModel::find($id);
    
        return View::make('modules.mt.templates.edit')
            ->with('template', $template);
    }
    
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $rules = [
            'name'       => 'required',
            'content'    => 'required',
            'thumbnail'  => 'image',
        ];
    
        $validator = Validator::make(Input::all(), $rules);
    
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $template = TemplateModel::find($id);
            $template->name = Input::get('name');
            $template->content = Input::get('content');

            if (Input::hasfile('thumbnail')) {
                $filename = str_random(24);
                $extension = Input::file('thumbnail')->getClientOriginalExtension();
                $destination = base_path()."/public".$this->path;
                Input::file('thumbnail')->move($destination, $filename.".".$extension);
                $template->thumbnail = $this->path."/".$filename.".".$extension;
            }

            $template->user_id = Confide::user()->id;
            $template->save();

            return Redirect::route('mt.templates.index')
                ->with('message', 'Successfully created!');
        }
    }

    /**
     * Load Defined Template
     * 
     */
    public function load()
    {
        $template_id = Input::get('template_id');
        $template = TemplateModel::find($template_id);
        $content = $template['content'];
        
        return Response::json(['result' => 'success',
                               'content' => $content,
                             ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $template = TemplateModel::find($id);
        $template->delete();
    
        Session::flash('message', 'Successfully deleted!');
        return Redirect::route('mt.templates.index');
    }
    

}
