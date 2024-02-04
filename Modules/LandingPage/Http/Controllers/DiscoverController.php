<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;

class DiscoverController extends Controller
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
            $discover_of_features = json_decode($settings['discover_of_features'], true) ?? [];

            return view('landingpage::landingpage.discover.index', compact('settings', 'discover_of_features'));
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
        return view('landingpage::landingpage.discover.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $data['discover_status']= 'on';
        $data['discover_heading']= $request->discover_heading;
        $data['discover_description']= $request->discover_description;
        $data['discover_live_demo_link']= $request->discover_live_demo_link;
        $data['discover_buy_now_link']= $request->discover_buy_now_link;


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
        return view('landingpage::landingpage.discover.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('landingpage::landingpage.discover.edit');
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




    public function discover_create(){
        $settings = LandingPageSetting::settings();
        return view('landingpage::landingpage.discover.create');
    }



    public function discover_store(Request $request){

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['discover_of_features'], true);

        if( $request->discover_logo){
            $discover_logo = time()."-discover_logo." . $request->discover_logo->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request,'discover_logo',$discover_logo,$dir,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $datas['discover_logo'] = $discover_logo;
        }

        $datas['discover_heading']= $request->discover_heading;
        $datas['discover_description']= $request->discover_description;

        $data[] = $datas;
        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'discover_of_features'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Discover add successfully']);
    }



    public function discover_edit($key){
        $settings = LandingPageSetting::settings();
        $discovers = json_decode($settings['discover_of_features'], true);
        $discover = $discovers[$key];
        return view('landingpage::landingpage.discover.edit', compact('discover','key'));
    }



    public function discover_update(Request $request, $key){

        $settings = LandingPageSetting::settings();
        $data = json_decode($settings['discover_of_features'], true);

        if( $request->discover_logo){
            $discover_logo = time()."-discover_logo." . $request->discover_logo->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request,'discover_logo',$discover_logo,$dir,[]);
            if($path['flag']==0){
                return redirect()->back()->with('error', __($path['msg']));
            }
            $data[$key]['discover_logo'] = $discover_logo;
        }

        $data[$key]['discover_heading'] = $request->discover_heading;
        $data[$key]['discover_description'] = $request->discover_description;

        $data = json_encode($data);
        LandingPageSetting::updateOrCreate(['name' =>  'discover_of_features'],['value' => $data]);

        return redirect()->back()->with(['success'=> 'Discover update successfully']);
    }



    public function discover_delete($key)
    {

        $settings = LandingPageSetting::settings();
        $pages = json_decode($settings['discover_of_features'], true);
        unset($pages[$key]);
        LandingPageSetting::updateOrCreate(['name' =>  'discover_of_features'],['value' => $pages]);
        return redirect()->back()->with(['success'=> 'Discover delete successfully']);
    }




}
