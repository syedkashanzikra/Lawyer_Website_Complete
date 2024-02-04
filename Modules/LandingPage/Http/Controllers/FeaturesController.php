<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;

class FeaturesController extends Controller
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
            $feature_of_features = json_decode($settings['feature_of_features'], true) ?? [];
            $other_features = json_decode($settings['other_features'], true) ?? [];
            return view('landingpage::landingpage.features.index', compact('settings', 'feature_of_features', 'other_features'));
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

        $data['feature_status']= 'on';
        $data['feature_title']= $request->feature_title;
        $data['feature_heading']= $request->feature_heading;
        $data['feature_description']= $request->feature_description;
        $data['feature_buy_now_link']= $request->feature_buy_now_link;


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


    public function feature_create()
    {
        $settings = LandingPageSetting::settings();
        return view('landingpage::landingpage.features.create');
    }



    public function feature_store(Request $request)
    {

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['feature_of_features'], true);

        if( $request->feature_logo){
            $feature_logo = time()."-feature_logo." . $request->feature_logo->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request,'feature_logo',$feature_logo,$dir,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $datas['feature_logo'] = $feature_logo;
        }

        $datas['feature_heading']= $request->feature_heading;
        $datas['feature_description']= $request->feature_description;

        $data[] = $datas;
        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'feature_of_features'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Feature add successfully']);
    }



    public function feature_edit($key)
    {
        $settings = LandingPageSetting::settings();
        $features = json_decode($settings['feature_of_features'], true);
        $feature = $features[$key];
        return view('landingpage::landingpage.features.edit', compact('feature','key'));
    }



    public function feature_update(Request $request, $key)
    {

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['feature_of_features'], true);

        if( $request->feature_logo){
            $feature_logo = time()."-feature_logo." . $request->feature_logo->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request,'feature_logo',$feature_logo,$dir,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $data[$key]['feature_logo'] = $feature_logo;
        }

        $data[$key]['feature_heading'] = $request->feature_heading;
        $data[$key]['feature_description'] = $request->feature_description;

        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'feature_of_features'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Feature update successfully']);
    }



    public function feature_delete($key)
    {
        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['feature_of_features'], true);
        unset($pages[$key]);
        LandingPageSetting::updateOrCreate(['name' =>  'feature_of_features'],['value' => $pages]);
        return redirect()->back()->with(['success'=> 'Feature delete successfully']);
    }




    public function feature_highlight_create(Request $request)
    {

        if( $request->highlight_feature_image){
            $highlight_feature_image = "highlight_feature_image." . $request->highlight_feature_image->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request,'highlight_feature_image',$highlight_feature_image,$dir,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $data['highlight_feature_image'] = $highlight_feature_image;
        }

        $data['highlight_feature_heading']= $request->highlight_feature_heading;
        $data['highlight_feature_description']= $request->highlight_feature_description;


        foreach($data as $key => $value){

            LandingPageSetting::updateOrCreate(['name' =>  $key],['value' => $value]);
        }

        return redirect()->back()->with(['success'=> 'Setting update successfully']);
    }






    public function features_create()
    {
        $settings = LandingPageSetting::settings();
        return view('landingpage::landingpage.features.features_create');
    }



    public function features_store(Request $request){
        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['other_features'], true);

        if( $request->other_features_image){
            $other_features_image = time()."-other_features_image." . $request->other_features_image->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request,'other_features_image',$other_features_image,$dir,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $datas['other_features_image'] = $other_features_image;
        }else{

        }

        $datas['other_features_heading']= $request->other_features_heading;
        $datas['other_featured_description']= $request->other_featured_description;
        $datas['other_feature_buy_now_link']= $request->other_feature_buy_now_link;


        $data[] = $datas;
        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'other_features'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Feature add successfully']);
    }



    public function features_edit($key)
    {
        $settings = LandingPageSetting::settings();
        $other_features = json_decode($settings['other_features'], true);
        $other_features = $other_features[$key];
        return view('landingpage::landingpage.features.features_edit', compact('other_features','key'));
    }




    public function features_update(Request $request, $key)
    {

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['other_features'], true);

        if( $request->other_features_image){
            $other_features_image = time()."-other_features_image." . $request->other_features_image->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request,'other_features_image',$other_features_image,$dir,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $data[$key]['other_features_image'] = $other_features_image;
        }

        $data[$key]['other_features_heading']= $request->other_features_heading;
        $data[$key]['other_featured_description']= $request->other_featured_description;
        $data[$key]['other_feature_buy_now_link']= $request->other_feature_buy_now_link;

        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'other_features'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Feature update successfully']);
    }



    public function features_delete($key)
    {
        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['other_features'], true);
        unset($pages[$key]);
        LandingPageSetting::updateOrCreate(['name' =>  'other_features'],['value' => $pages]);
        return redirect()->back()->with(['success'=> 'Feature delete successfully']);
    }
}
