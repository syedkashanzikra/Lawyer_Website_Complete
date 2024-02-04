<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;

class TestimonialsController extends Controller
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
            $testimonials = json_decode($settings['testimonials'], true) ?? [];
            return view('landingpage::landingpage.testimonials.index',compact('settings','testimonials'));
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
        return view('landingpage::landingpage.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data['testimonials_status']= 'on';
        $data['testimonials_heading']= $request->testimonials_heading;
        $data['testimonials_description']= $request->testimonials_description;
        $data['testimonials_long_description']= $request->testimonials_long_description;

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



    public function testimonials_create(){
        $settings = LandingPageSetting::settings();
        return view('landingpage::landingpage.testimonials.create');
    }



    public function testimonials_store(Request $request){

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['testimonials'], true);

        if( $request->testimonials_user_avtar){
            $testimonials_user_avtar = time()."-testimonials_user_avtar." . $request->testimonials_user_avtar->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request,'testimonials_user_avtar',$testimonials_user_avtar,$dir,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $datas['testimonials_user_avtar'] = $testimonials_user_avtar;
        }


        $datas['testimonials_title']= $request->testimonials_title;
        $datas['testimonials_description']= $request->testimonials_description;
        $datas['testimonials_user']= $request->testimonials_user;
        $datas['testimonials_designation']= $request->testimonials_designation;
        $datas['testimonials_star']= $request->testimonials_star;

        $data[] = $datas;
        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'testimonials'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Testimonial add successfully']);
    }

    public function testimonials_edit($key){
        $settings = LandingPageSetting::settings();
        $testimonials = json_decode($settings['testimonials'], true);
        $testimonial = $testimonials[$key];
        return view('landingpage::landingpage.testimonials.edit', compact('testimonial','key'));
    }


    public function testimonials_update(Request $request, $key){

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['testimonials'], true);

        if( $request->testimonials_user_avtar){
            $testimonials_user_avtar = time()."-testimonials_user_avtar." . $request->testimonials_user_avtar->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request,'testimonials_user_avtar',$testimonials_user_avtar,$dir,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $data[$key]['testimonials_user_avtar'] = $testimonials_user_avtar;
        }

        $data[$key]['testimonials_title'] = $request->testimonials_title;
        $data[$key]['testimonials_description'] = $request->testimonials_description;
        $data[$key]['testimonials_user'] = $request->testimonials_user;
        $data[$key]['testimonials_designation'] = $request->testimonials_designation;
        $data[$key]['testimonials_star'] = $request->testimonials_star;


        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'testimonials'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Testimonial update successfully']);
    }


    public function testimonials_delete($key){

        $settings = LandingPageSetting::settings();

        $pages = json_decode($settings['testimonials'], true);
        unset($pages[$key]);
        LandingPageSetting::updateOrCreate(['name' =>  'testimonials'],['value' => $pages]);

        return redirect()->back()->with(['success'=> 'Testimonial delete successfully']);
    }

}
