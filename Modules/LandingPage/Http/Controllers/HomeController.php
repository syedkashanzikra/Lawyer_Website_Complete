<?php

namespace Modules\LandingPage\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\LandingPage\Entities\LandingPageSetting;

class HomeController extends Controller
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
            return view('landingpage::landingpage.homesection', compact('settings'));
        }
        else{
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
        $uploadedFiles = $request->all();

        if ($request->home_banner) {
            $home_banner = "home_banner." . $request->home_banner->getClientOriginalExtension();
            $dir        = 'uploads/landing_page_image';
            $path = LandingPageSetting::upload_file($request, 'home_banner', $home_banner, $dir, []);
            if ($path['flag'] == 0) {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $data['home_banner'] = $home_banner;
        }
        $temp_logo = explode(",", $request->savedlogo);

        $stored_home_logo = LandingPageSetting::settings()['home_logo'];
        $home_logo = array_intersect($temp_logo, explode(",", $stored_home_logo));


        if ($request->home_logo) {

            $files = $request->home_logo;


            foreach ($files as $key => $file) {

                $file_data = $file['home_logo'];


                $file_name = md5(time()) . "_" . $file_data->getClientOriginalName();
                $dir        = 'uploads/landing_page_image/';

                $path = LandingPageSetting::keyWiseUpload_file($request,'home_logo',$file_name,$dir,$key,[]);


                if ($path['flag'] == 1) {
                    $url = $path['url'];
                    $home_logo[] = $url;
                } else {

                    return redirect()->back()->with('error', __($path['msg']));
                }
            }
        }

        $data['home_logo'] = implode(",", array_filter($home_logo));

        $data['home_status'] = 'on';
        $data['home_offer_text'] = $request->home_offer_text;
        $data['home_title'] = $request->home_title;
        $data['home_heading'] = $request->home_heading;
        $data['home_description'] = $request->home_description;
        $data['home_trusted_by'] = $request->home_trusted_by;
        $data['home_live_demo_link'] = $request->home_live_demo_link;
        $data['home_buy_now_link'] = $request->home_buy_now_link;
        foreach ($data as $key => $value) {

            LandingPageSetting::updateOrCreate(['name' =>  $key], ['value' => $value]);
        }
        return redirect()->back()->with(['success' => 'Setting update successfully']);
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
}
