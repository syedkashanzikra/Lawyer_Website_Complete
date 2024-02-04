<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(\Auth::user()->type == 'super admin')
        {
            $settings = LandingPageSetting::landingPageSetting();
            $faqs = json_decode($settings['faqs'], true) ?? [];
            return view('landingpage::landingpage.faq.index', compact('settings','faqs'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('landingpage::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        if($request->faq_status){
            $faq_status = 'on';
        }else{
            $faq_status = 'off';
        }


        $data['faq_status']= $faq_status;
        $data['faq_title']= $request->faq_title;
        $data['faq_heading']= $request->faq_heading;
        $data['faq_description']= $request->faq_description;

        foreach($data as $key => $value){
            LandingPageSetting::updateOrCreate(['name' =>  $key],['value' => $value]);
        }

        return redirect()->back()->with(['success'=> 'Setting update successfully']);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('landingpage::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('landingpage::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }



    public function faq_create(){
        $settings = LandingPageSetting::settings();
        return view('landingpage::landingpage.faq.create');
    }



    public function faq_store(Request $request){

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['faqs'], true);

        $datas['faq_questions']= $request->faq_questions;
        $datas['faq_answer']= $request->faq_answer;

        $data[] = $datas;
        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'faqs'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Faq add successfully']);
    }



    public function faq_edit($key){
        $settings = LandingPageSetting::settings();
        $faqs = json_decode($settings['faqs'], true);
        $faq = $faqs[$key];
        return view('landingpage::landingpage.faq.edit', compact('faq','key'));
    }



    public function faq_update(Request $request, $key){

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['faqs'], true);

        $data[$key]['faq_questions'] = $request->faq_questions;
        $data[$key]['faq_answer'] = $request->faq_answer;

        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'faqs'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Faq update successfully']);
    }



    public function faq_delete($key)
    {
        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['faqs'], true);
        unset($pages[$key]);
        LandingPageSetting::updateOrCreate(['name' =>  'faqs'],['value' => $pages]);
        return redirect()->back()->with(['success'=> 'Faq delete successfully']);
    }

}
