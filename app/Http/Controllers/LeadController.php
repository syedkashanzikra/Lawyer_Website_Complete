<?php

namespace App\Http\Controllers;

use App\Exports\LeadsExport;
use App\Imports\LeadsImport;
use App\Models\ClientDeal;
use App\Models\Deal;
use App\Models\DealCall;
use App\Models\DealDiscussion;
use App\Models\DealEmail;
use App\Models\DealFile;
use App\Models\DealStage;
use App\Models\Label;
use App\Models\Lead;
use App\Models\LeadActivityLog;
use App\Models\LeadCall;
use App\Models\LeadDiscussions;
use App\Models\LeadEmail;
use App\Models\LeadFile;
use App\Models\LeadStage;
use App\Models\Pipeline;
use App\Models\Source;
use App\Models\Stage;
use App\Models\User;
use App\Models\UserDeal;
use App\Models\UserDefualtView;
use App\Models\UserLead;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{

    public function index()
    {
        $premission_arr = [];
        if (\Auth::user()->super_admin_employee == 1) {
            $premission = json_decode(\Auth::user()->permission_json);
            $premission_arr = get_object_vars($premission);
        }
        if((Auth::user()->super_admin_employee == 1 && array_search('manage crm', $premission_arr))||Auth::user()->type=="company")
        {

            if(\Auth::user()->default_pipeline)
            {
                $pipeline = Pipeline::where('created_by', '=', \Auth::user()->crmcreatorId())->where('id', '=', \Auth::user()->default_pipeline)->first();
                if(!$pipeline)
                {
                    $pipeline = Pipeline::where('created_by', '=', \Auth::user()->crmcreatorId())->first();
                }
            }
            else
            {
                $pipeline = Pipeline::where('created_by', '=', \Auth::user()->crmcreatorId())->first();
            }

             $pipelines = Pipeline::where('created_by', '=', \Auth::user()->crmcreatorId())->get();

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
        $defualtView         = new UserDefualtView();
        $defualtView->route  = \Request::route()->getName();
        $defualtView->module = 'lead';
        $defualtView->view   = 'kanban';
        User::userDefualtView($defualtView);

        return view('lead.index', compact('pipelines', 'pipeline'));
    }


    public function grid()
    {
        if(\Auth::user()->super_admin_employee == 1 ||Auth::user()->type=="company")
        {
            $usr = \Auth::user();

            if($usr->default_pipeline)
            {
                $pipeline = Pipeline::where('created_by', '=', $usr->crmcreatorId())->where('id', '=', $usr->default_pipeline)->first();
                if(!$pipeline)
                {
                    $pipeline = Pipeline::where('created_by', '=', $usr->crmcreatorId())->first();
                }
            }
            else
            {
                $pipeline = Pipeline::where('created_by', '=', $usr->crmcreatorId())->first();
            }

            $pipelines = Pipeline::where('created_by', '=', $usr->crmcreatorId())->get()->pluck('name', 'id');
            $leads     = Lead::select('leads.*')->join('user_leads', 'user_leads.lead_id', '=', 'leads.id')->where('user_leads.user_id', '=', $usr->id)->orderBy('leads.order')->get();

            $defualtView         = new UserDefualtView();
            $defualtView->route  = \Request::route()->getName();
            $defualtView->module = 'lead';
            $defualtView->view   = 'list';
            User::userDefualtView($defualtView);
            return view('lead.grid', compact('pipelines', 'pipeline', 'leads'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function create()
    {
        $employees = User::where('created_by', '=', \Auth::user()->crmcreatorId())->where('type', '=', 'company')->get()->pluck('name', 'id');
        $employees->prepend(__('Select Employee'), '');

        return view('lead.create', compact('employees'));
    }


    public function store(Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1 ||Auth::user()->type=="company")
        {
            $usr       = \Auth::user();
            $validator = \Validator::make(
                $request->all(), [
                                   'subject' => 'required',
                                   'name' => 'required',
                                   'email' => 'required|email|unique:leads',
                                   'phone_no' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $pipeline = Pipeline::where('created_by', '=', $usr->crmcreatorId())->first();

            if(empty($pipeline))
            {
                return redirect()->back()->with('error', __('Please add constant pipeline.'));
            }

            // Default Field Value
            if($usr->default_pipeline)
            {
                $pipeline = Pipeline::where('created_by', '=', $usr->crmcreatorId())->where('id', '=', $usr->default_pipeline)->first();
                if(!$pipeline)
                {
                    $pipeline = Pipeline::where('created_by', '=', $usr->crmcreatorId())->first();
                }
            }
            else
            {
                $pipeline = Pipeline::where('created_by', '=', $usr->crmcreatorId())->first();
            }


            $stage = LeadStage::where('pipeline_id', '=', $pipeline->id)->first();

            // End Default Field Value

            if(empty($stage))
            {
                return redirect()->back()->with('error', __('Please add constant lead stage.'));
            }
            else
            {

                $lead              = new Lead();
                $lead->name        = $request->name;
                $lead->email       = $request->email;
                $lead->subject     = $request->subject;
                $lead->user_id     = $request->user_id;
                $lead->pipeline_id = $pipeline->id;
                $lead->stage_id    = $stage->id;
                $lead->phone_no    = $request->phone_no;
                $lead->created_by  = $usr->crmcreatorId();
                $lead->date        = date('Y-m-d');
                $lead->save();

                $usrLeads = [
                    $usr->id,
                    $request->user_id,
                ];

                foreach($usrLeads as $usrLead)
                {
                    UserLead::create(
                        [
                            'user_id' => $usrLead,
                            'lead_id' => $lead->id,
                        ]
                    );
                }

                $lArr = [
                    'lead_name' => $lead->name,
                    'lead_email' => $lead->email,
                    'lead_subject' => $lead->subject,
                    'lead_pipeline' => $pipeline->name,
                    'lead_stage' => $stage->name,
                ];

                $usrEmail = User::find($request->user_id);

                // Send Email******
                //$resp = Utility::sendEmailTemplate('lead_assigned', [$usrEmail->id => $usrEmail->email], $lArr);

                $settings  = Utility::settings();
                // if(isset($settings['lead_create_notification']) && $settings['lead_create_notification'] ==1){
                //     $msg = __('New Lead created by the ').\Auth::user()->name.'.';
                //     //dd($msg);
                //     Utility::send_slack_msg($msg);
                // }

                if (isset($settings['lead_create_notification']) && $settings['lead_create_notification'] == 1) {
                    $uArr = [
                        'user_name' => \Auth::user()->name,
                         'lead_name' => $lead->name,
                        'lead_email' => $lead->email,
                    ];// dd($uArr);
                    Utility::send_slack_msg('new_lead', $uArr);
                    }
                    if (isset($settings['telegram_lead_create_notification']) && $settings['telegram_lead_create_notification'] == 1) {
                        $uArr = [
                            'user_name' => \Auth::user()->name,
                             'lead_name' => $lead->name,
                            'lead_email' => $lead->email,
                        ];// dd($uArr);
                        Utility::send_telegram_msg('new_lead', $uArr);
                        }

                // if(isset($settings['telegram_lead_create_notification']) && $settings['telegram_lead_create_notification'] ==1){
                //     $response = __('New Lead created by the ').\Auth::user()->name.'.';
                //      Utility::send_telegram_msg($response);
                // }
                // **********
                //webhook
                // $module = "New Lead";

                // $webhook = Utility::webhookSetting($module);
                // if($webhook)
                // {
                //     $parameter = json_encode($lead);

                //     // 1 parameter is URL , 2  (Lead Data) parameter is data , 3 parameter is method
                //     $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
                //     if($status == true)
                //     {
                //         return redirect()->back()->with('success', __('Lead successfully created.'));
                //     }
                //     else
                //     {
                //         return redirect()->back()->with('error', __('Lead call failed.'));
                //     }
                // }
                // **********
               //return redirect()->back()->with('success', __('Lead successfully created.') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
               return redirect()->back()->with('success', __('Lead successfully created.'));

            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($id)
    {
         if(\Auth::user()->super_admin_employee == 1 ||Auth::user()->type=="company")
        {
            if($id=='null')
            {
                return redirect()->back()->with('error', 'Lead is not available.');
            }
            $calenderTasks = [];

            $ids           = \Crypt::decrypt($id);
            $lead          = Lead::find($ids);
            $deal          = Deal::where('id', '=', $lead->is_converted)->first();
            $stageCnt      = LeadStage::where('pipeline_id', '=', $lead->pipeline_id)->where('created_by', '=', $lead->created_by)->get();

            $i             = 0;
            foreach($stageCnt as $stage)
            {
                $i++;
                if($stage->id == $lead->stage_id)
                {
                    break;
                }
            }
            $precentage = $i != 0 ? number_format(($i * 100) / count($stageCnt)) : 0;

            return view('lead.view', compact('lead', 'calenderTasks', 'deal', 'precentage'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit(Lead $lead)
    {

        $pipelines = Pipeline::where('created_by', '=', \Auth::user()->crmcreatorId())->get()->pluck('name', 'id');
        $pipelines->prepend(__('Select Pipeline'), '');
        $stages = LeadStage::where('created_by', '=', \Auth::user()->crmcreatorId())->get()->pluck('name', 'id');

        $sources   = Source::where('created_by', '=', \Auth::user()->crmcreatorId())->get()->pluck('name', 'id');
        //$products  = Item::where('created_by', '=', \Auth::user()->crmcreatorId())->get()->pluck('name', 'id');
        $employees = User::where('created_by', '=', \Auth::user()->crmcreatorId())->where('type', '=', 'company')->get()->pluck('name', 'id');
        $employees->prepend(__('Select Employee'), '');

        $lead->sources  = explode(',', $lead->sources);
        $lead->products = explode(',', $lead->products);

        return view('lead.edit', compact('lead', 'pipelines','stages', 'sources', 'employees'));
    }


    public function update(Request $request, Lead $lead)
    {


        if(\Auth::user()->super_admin_employee == 1 || Auth::user()->type=="company")
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'subject' => 'required',
                                   'name' => 'required',
                                   'email' => 'required',
                                   'pipeline_id' => 'required',
                                   'user_id' => 'required',
                                   'stage_id' => 'required',
                                   'sources' => 'required',
                                   'phone_no' =>'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $lead->name        = $request->name;
            $lead->email       = $request->email;
            $lead->subject     = $request->subject;
            $lead->user_id     = $request->user_id;
            $lead->pipeline_id = $request->pipeline_id;
            $lead->stage_id    = $request->stage_id;
            $lead->phone_no    = $request->phone_no;
            $lead->sources     = implode(",", array_filter($request->sources));
            $lead->items       = null;
            $lead->notes       = $request->notes;
            $lead->save();
            return redirect()->back()->with('success', __('Lead successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy(Lead $lead)
    {
        if(\Auth::user()->super_admin_employee == 1 || Auth::user()->type=="company")
        {
            LeadFile::where('lead_id', '=', $lead->id)->delete();
            UserLead::where('lead_id', '=', $lead->id)->delete();
            LeadActivityLog::where('lead_id', '=', $lead->id)->delete();
            $lead->delete();

            return redirect()->route('lead.index')->with('success', __('Lead successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function order(Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1 ||Auth::user()->type=="company")
        {

            $usr = \Auth::user();

            $post       = $request->all();
            $lead       = Lead::find($post['lead_id']);
            $lead_users = $lead->users->pluck('email', 'id')->toArray();

            if($lead->stage_id != $post['stage_id'])
            {
                $newStage = LeadStage::find($post['stage_id']);

                LeadActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'lead_id' => $lead->id,
                        'log_type' => 'Move',
                        'remark' => json_encode(
                            [
                                'title' => $lead->name,
                                'old_status' => $lead->stage->name,
                                'new_status' => $newStage->name,
                            ]
                        ),
                    ]
                );
            }

            foreach($post['order'] as $key => $item)
            {
                $lead           = Lead::find($item);
                $lead->order    = $key;
                $lead->stage_id = $post['stage_id'];
                $lead->save();
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function json(Request $request)
    {
        $lead_stages = new LeadStage();
        if($request->pipeline_id && !empty($request->pipeline_id))
        {
            $lead_stages = $lead_stages->where('pipeline_id', '=', $request->pipeline_id);
            $lead_stages = $lead_stages->get()->pluck('name', 'id');

        }
        else
        {
            $lead_stages = [];
        }

        return response()->json($lead_stages);
    }

    public function userDestroy($id, $user_id)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead = Lead::find($id);
            UserLead::where('lead_id', '=', $lead->id)->where('user_id', '=', $user_id)->delete();

            return redirect()->back()->with('success', __('User successfully deleted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function userEdit($id)
    {
        $lead = Lead::find($id);

        $users = User::where('created_by', '=', \Auth::user()->crmcreatorId())->where('type', 'company')->whereNOTIn(
            'id', function ($q) use ($lead){
            $q->select('user_id')->from('user_leads')->where('lead_id', '=', $lead->id);
        }
        )->get();

        $users = $users->pluck('name', 'id');


        return view('lead.users', compact('lead', 'users'));
    }

    public function userUpdate($id, Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $usr  = \Auth::user();
            $lead = Lead::find($id);

            if(!empty($request->users))
            {
                $users   = array_filter($request->users);
                $leadArr = [
                    'lead_id' => $lead->id,
                    'name' => $lead->name,
                    'updated_by' => $usr->id,
                ];

                foreach($users as $user)
                {
                    UserLead::create(
                        [
                            'lead_id' => $lead->id,
                            'user_id' => $user,
                        ]
                    );

                }
            }

            if(!empty($users) && !empty($request->users))
            {
                return redirect()->back()->with('success', __('Users successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Please select valid user.'));
            }

        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function productEdit($id)
    {
        $lead     = Lead::find($id);
        $products = Item::where('created_by', '=', \Auth::user()->crmcreatorId())->whereNOTIn('id', explode(',', $lead->items))->get()->pluck('name', 'id');

        return view('lead.items', compact('lead', 'products'));
    }

    public function productUpdate($id, Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $usr        = \Auth::user();
            $lead       = Lead::find($id);
            $lead_users = $lead->users->pluck('id')->toArray();

            if(!empty($request->items))
            {
                $products     = array_filter($request->items);
                $old_products = explode(',', $lead->items);
                $lead->items  = !empty($old_products) ? implode(',', array_merge($old_products, $products)) : $products;

                $lead->save();

                $objProduct = Item::whereIN('id', $products)->get()->pluck('name', 'id')->toArray();

                LeadActivityLog::create(
                    [
                        'user_id' => $usr->id,
                        'lead_id' => $lead->id,
                        'log_type' => 'Add Product',
                        'remark' => json_encode(['title' => implode(",", $objProduct)]),
                    ]
                );

            }

            if(!empty($products) && !empty($request->items))
            {
                return redirect()->back()->with('success', __('Products successfully updated.'))->with('status', 'products');
            }
            else
            {
                return redirect()->back()->with('error', __('Please select valid product.'))->with('status', 'general');
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    public function productDestroy($id, $product_id)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead     = Lead::find($id);
            $products = explode(',', $lead->items);
            foreach($products as $key => $product)
            {
                if($product_id == $product)
                {
                    unset($products[$key]);
                }
            }
            $lead->items = implode(',', $products);
            $lead->save();

            return redirect()->back()->with('success', __('Products successfully deleted.'))->with('status', 'products');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileUpload($id, Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead = Lead::find($id);
            $request->validate(['file' => 'required']);
            $file_name = $request->file->getClientOriginalName();
            $file_path = $id . "_" . md5(time()) . "_" . $request->file->getClientOriginalName();
            $request->file->storeAs('uploads/lead_files', $file_path);

            $dir = 'lead_files/';

            $path = Utility::upload_file($request,'file',$file_name,$dir,[]);
                if($path['flag'] == 1){
                $file = $path['url'];
            }
            else{
                return redirect()->back()->with('error', __($path['msg']));
            }

            $file                 = LeadFile::create(
                [
                    'lead_id' => $id,
                    'file_name' => $file_name,
                    'file_path' => $file_path,
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['download']   = route(
                'lead.file.download', [
                                        $lead->id,
                                        $file->id,
                                    ]
            );
            $return['delete']     = route(
                'lead.file.delete', [
                                      $lead->id,
                                      $file->id,
                                  ]
            );

            LeadActivityLog::create(
                [
                    'user_id' => \Auth::user()->id,
                    'lead_id' => $lead->id,
                    'log_type' => 'Upload File',
                    'remark' => json_encode(['file_name' => $file_name]),
                ]
            );

            return response()->json($return);
        }
        else
        {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission denied.'),
                ], 200
            );
        }
    }

    public function fileDownload($id, $file_id)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead = Lead::find($id);

            $file = LeadFile::find($file_id);
            if($file)
            {
                $file_path = storage_path('uploads/lead_files/' . $file->file_path);
                $filename  = $file->file_name;

                return \Response::download(
                    $file_path, $filename, [
                                  'Content-Length: ' . filesize($file_path),
                              ]
                );
            }
            else
            {
                return redirect()->back()->with('error', __('File is not exist.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function fileDelete($id, $file_id)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead = Lead::find($id);
            $file = LeadFile::find($file_id);
            if($file)
            {

                $path = storage_path('uploads/lead_files/' . $file->file_path);
                if(file_exists($path))
                {
                    \File::delete($path);
                }
                $file->delete();

                return redirect()->back()->with('success', __('Lead file successfully deleted.'));

            }
            else
            {
                return redirect()->back()->with('error', __('File is not exist.'));

            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));

        }
    }


    public function noteStore($id, Request $request)
    {

        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead        = Lead::find($id);
            $lead->notes = $request->notes;
            $lead->save();

            return redirect()->back()->with('success', __('Note successfully saved.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied'));

        }
    }

    public function sourceEdit($id)
    {
        $lead    = Lead::find($id);
        $sources = Source::where('created_by', '=', \Auth::user()->crmcreatorId())->get();

        $selected = $lead->sources();
        if($selected)
        {
            $selected = $selected->pluck('name', 'id')->toArray();
        }

        return view('lead.sources', compact('lead', 'sources', 'selected'));
    }

    public function sourceUpdate($id, Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $usr        = \Auth::user();
            $lead       = Lead::find($id);
            $lead_users = $lead->users->pluck('id')->toArray();
            if(!empty($request->sources) && count($request->sources) > 0)
            {
                $lead->sources = implode(',', $request->sources);
            }
            else
            {
                $lead->sources = "";
            }
            $lead->save();
            LeadActivityLog::create(
                [
                    'user_id' => $usr->id,
                    'lead_id' => $lead->id,
                    'log_type' => 'Update Sources',
                    'remark' => json_encode(['title' => 'Update Sources']),
                ]
            );

            return redirect()->back()->with('success', __('Sources successfully updated.'))->with('status', 'sources');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function sourceDestroy($id, $source_id)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead    = Lead::find($id);
            $sources = explode(',', $lead->sources);
            foreach($sources as $key => $source)
            {
                if($source_id == $source)
                {
                    unset($sources[$key]);
                }
            }
            $lead->sources = implode(',', $sources);
            $lead->save();

            return redirect()->back()->with('success', __('Sources successfully deleted.'))->with('status', 'sources');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function discussionCreate($id)
    {
        $lead = Lead::find($id);

        if($lead)
            {
                return view('lead.discussions', compact('lead'));
            }
            else
            {
                return redirect()->back()->with('error', __('Lead Not Found.'));
            }
    }

    public function discussionStore($id, Request $request)
    {
        $validator = \Validator::make(
            $request->all(), [
                               'comment' => 'required',
                           ]
        );

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        if(\Auth::user()->super_admin_employee == 1 ||Auth::user()->type=="company")
        {
            $usr        = \Auth::user();
            $lead       = Lead::find($id);
            $lead_users = $lead->users->pluck('id')->toArray();

            $discussion             = new LeadDiscussions();
            $discussion->comment    = $request->comment;
            $discussion->lead_id    = $lead->id;
            $discussion->created_by = \Auth::user()->id;
            $discussion->save();

            return redirect()->back()->with('success', __('Message successfully added.'))->with('status', 'discussion');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    // Lead Calls
    public function callCreate($id)
    {
        $lead = Lead::find($id);

        $users = UserLead::where('lead_id', '=', $lead->id)->get();

        return view('lead.calls', compact('lead', 'users'));
    }

    public function callStore($id, Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $usr       = \Auth::user();
            $lead      = Lead::find($id);
            $validator = \Validator::make(
                $request->all(), [
                                   'subject' => 'required',
                                   'call_type' => 'required',
                                   'user_id' => 'required',
                                   'duration' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $leadCall = LeadCall::create(
                [
                    'lead_id' => $lead->id,
                    'subject' => $request->subject,
                    'call_type' => $request->call_type,
                    'duration' => $request->duration,
                    'user_id' => $request->user_id,
                    'description' => $request->description,
                    'call_result' => $request->call_result,
                ]
            );

            LeadActivityLog::create(
                [
                    'user_id' => $usr->id,
                    'lead_id' => $lead->id,
                    'log_type' => 'Create Lead Call',
                    'remark' => json_encode(['title' => 'Create new Lead Call']),
                ]
            );


            return redirect()->back()->with('success', __('Call successfully created.'))->with('status', 'calls');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function callEdit($id, $call_id)
    {
        $lead = Lead::find($id);

        $call  = LeadCall::find($call_id);
        $users = UserLead::where('lead_id', '=', $id)->get();

        return view('lead.calls', compact('call', 'lead', 'users'));
    }

    public function callUpdate($id, $call_id, Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead      = Lead::find($id);
            $validator = \Validator::make(
                $request->all(), [
                                   'subject' => 'required',
                                   'call_type' => 'required',
                                   'user_id' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $call = LeadCall::find($call_id);

            $call->update(
                [
                    'subject' => $request->subject,
                    'call_type' => $request->call_type,
                    'duration' => $request->duration,
                    'user_id' => $request->user_id,
                    'description' => $request->description,
                    'call_result' => $request->call_result,
                ]
            );

            return redirect()->back()->with('success', __('Call successfully updated.'))->with('status', 'calls');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function callDestroy($id, $call_id)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead = Lead::find($id);
            $task = LeadCall::find($call_id);
            $task->delete();

            return redirect()->back()->with('success', __('Call successfully deleted.'))->with('status', 'calls');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function emailCreate($id)
    {
        $lead = Lead::find($id);
        return view('lead.emails', compact('lead'));
    }

    public function emailStore($id, Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead      = Lead::find($id);
            $settings  = Utility::settings();
            $validator = \Validator::make(
                $request->all(), [
                                   'to' => 'required|email',
                                   'subject' => 'required',
                                   'description' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $leadEmail = LeadEmail::create(
                [
                    'lead_id' => $lead->id,
                    'to' => $request->to,
                    'subject' => $request->subject,
                    'description' => $request->description,
                ]
            );

            //        try
            //        {
            //            Mail::to($request->to)->send(new SendLeadEmail($leadEmail, $settings));
            //        }
            //        catch(\Exception $e)
            //        {
            //            $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            //        }


            LeadActivityLog::create(
                [
                    'user_id' => \Auth::user()->id,
                    'lead_id' => $lead->id,
                    'log_type' => 'Create Lead Email',
                    'remark' => json_encode(['title' => 'Create new Deal Email']),
                ]
            );

            return redirect()->back()->with('success', __('Email successfully created!') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''))->with('status', 'emails');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function showConvertToDeal($id)
    {
         if(\Auth::user()->super_admin_employee == 1)
        {
            $lead         = Lead::findOrFail($id);
            $users=UserLead::select('user_id')->where('lead_id',$lead->id)->get();
            $arr=[];
            foreach ($users as $key => $value) {
                $arr[]=$value->user_id;
            }

            $exist_client = User::where('type', '=', 'advocate')->where('email', '=', $lead->email)->whereIn('created_by',$arr)->first();
            $company=User::where('type','company')->get();

            $clients      = User::where('type', '=', 'advocate')->whereIn('created_by',$arr)->get();

            return view('lead.convert', compact('lead','company','exist_client', 'clients'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function convertToDeal($id, Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $lead = Lead::findOrFail($id);
            $empUsers=UserLead::select('user_id')->where('lead_id',$lead->id)->get();
            $userarr=[];
            foreach ($empUsers as $key => $value) {
                $userarr[]=$value->user_id;
            }
             $usr  = \Auth::user();
            if(!empty($request->clients) && $request->client_check == 'exist')
            {
                $client = User::where('type', '=', 'advocate')->where('email', '=', $request->clients)->whereIn('created_by',$userarr)->first();

                if(empty($client))
                {
                    return redirect()->back()->with('error', 'Client is not available now.');
                }
            }
            else
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'client_name' => 'required',
                                       'client_email' => 'required|email|unique:users,email',
                                       'client_password' => 'required',
                                   ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $user             = new User();
                $user->name       = $request->client_name;
                $user->email      = $request->client_email;
                $user->password   = \Hash::make($request->client_password);
                $user->type       = 'client';
                $user->lang       = 'en';
                $user->created_by = $usr->crmcreatorId();
                $user->save();


                // if(!empty($user))
                // {
                //     $client             = new User();
                //     $client->user_id    = $user->id;
                //     $client->client_id  = $this->clientNumber();
                //     $client->created_by = \Auth::user()->crmcreatorId();
                //     $client->save();
                // }

            }

            // Create Deal
            $stage = DealStage::where('pipeline_id', '=', $lead->pipeline_id)->first();
            if(empty($stage))
            {
                return redirect()->back()->with('error', __('Please create stage for this pipeline.'));
            }

            $deal              = new Deal();
            $deal->name        = $request->name;
            $deal->price       = empty($request->price) ? 0 : $request->price;
            $deal->pipeline_id = $lead->pipeline_id;
            $deal->stage_id    = $stage->id;
            $deal->sources     = in_array('sources', $request->is_transfer) ? $lead->sources : '';
            $deal->products    = in_array('products', $request->is_transfer) ? $lead->products : '';
            $deal->notes       = in_array('notes', $request->is_transfer) ? $lead->notes : '';
            $deal->labels      = $lead->labels;
            $deal->status      = 'Active';
            $deal->created_by  = $lead->created_by;
            $deal->save();
            // end create deal

            // Make entry in ClientDeal Table
            ClientDeal::create(
                [
                    'deal_id' => $deal->id,
                    'client_id' => $client->id,
                ]
            );
            // end

            // Make Entry in UserDeal Table
            $leadUsers = UserLead::where('lead_id', '=', $lead->id)->get();
            foreach($leadUsers as $leadUser)
            {
                UserDeal::create(
                    [
                        'user_id' => $leadUser->user_id,
                        'deal_id' => $deal->id,
                    ]
                );
            }
            // end

            //Transfer Lead Discussion to Deal
            if(in_array('discussion', $request->is_transfer))
            {
                $discussions = LeadDiscussions::where('lead_id', '=', $lead->id)->where('created_by', '=', $usr->crmcreatorId())->get();
                if(!empty($discussions))
                {
                    foreach($discussions as $discussion)
                    {
                        DealDiscussion::create(
                            [
                                'deal_id' => $deal->id,
                                'comment' => $discussion->comment,
                                'created_by' => $discussion->created_by,
                            ]
                        );
                    }
                }
            }
            // end Transfer Discussion

            // Transfer Lead Files to Deal
            if(in_array('files', $request->is_transfer))
            {
                $files = LeadFile::where('lead_id', '=', $lead->id)->get();
                if(!empty($files))
                {
                    foreach($files as $file)
                    {
                        $location     = base_path() . '/storage/uploads/lead_files/' . $file->file_path;
                        $new_location = base_path() . '/storage/uploads/deal_files/' . $file->file_path;
                        $copied       = copy($location, $new_location);

                        if($copied)
                        {
                            DealFile::create(
                                [
                                    'deal_id' => $deal->id,
                                    'file_name' => $file->file_name,
                                    'file_path' => $file->file_path,
                                ]
                            );
                        }
                    }
                }
            }
            // end Transfer Files

            // Transfer Lead Calls to Deal
            if(in_array('calls', $request->is_transfer))
            {
                $calls = LeadCall::where('lead_id', '=', $lead->id)->get();
                if(!empty($calls))
                {
                    foreach($calls as $call)
                    {
                        DealCall::create(
                            [
                                'deal_id' => $deal->id,
                                'subject' => $call->subject,
                                'call_type' => $call->call_type,
                                'duration' => $call->duration,
                                'user_id' => $call->user_id,
                                'description' => $call->description,
                                'call_result' => $call->call_result,
                            ]
                        );
                    }
                }
            }
            //end

            // Transfer Lead Emails to Deal
            if(in_array('emails', $request->is_transfer))
            {
                $emails = LeadEmail::where('lead_id', '=', $lead->id)->get();
                if(!empty($emails))
                {
                    foreach($emails as $email)
                    {
                        DealEmail::create(
                            [
                                'deal_id' => $deal->id,
                                'to' => $email->to,
                                'subject' => $email->subject,
                                'description' => $email->description,
                            ]
                        );
                    }
                }
            }

            // Update is_converted field as deal_id
            $lead->is_converted = $deal->id;
            $lead->save();

            $settings  = Utility::settings();

            // if(isset($settings['convert_lead_to_deal_notification']) && $settings['convert_lead_to_deal_notification'] ==1){
            //    $msg = __("Deal converted through lead")." ".$lead->name.'.';
            //     Utility::send_slack_msg($msg);
            // }

            if (isset($settings['convert_lead_to_deal_notification']) && $settings['convert_lead_to_deal_notification'] == 1) {
                $uArr = [
                    'user_name' => \Auth::user()->name,
                     'lead_name' => $lead->name,
                    'lead_email' => $lead->email,
                ];// dd($uArr);
                Utility::send_slack_msg('lead_to_deal_conversion', $uArr);
            }
            if (isset($settings['telegram_convert_lead_to_deal_notification']) && $settings['telegram_convert_lead_to_deal_notification'] == 1) {
                $uArr = [
                    'user_name' => \Auth::user()->name,
                     'lead_name' => $lead->name,
                    'lead_email' => $lead->email,
                ];// dd($uArr);
                Utility::send_telegram_msg('lead_to_deal_conversion', $uArr);
            }

            // if(isset($settings['telegram_convert_lead_to_deal_notification']) && $settings['telegram_convert_lead_to_deal_notification'] ==1){
            //     $resp = __("Deal converted through lead")." ".$lead->name.'.';
            //     Utility::send_telegram_msg($resp);
            // }


            //webhook*******
            // $module = "Lead to Deal Conversion";

            // $webhook = Utility::webhookSetting($module);
            // if($webhook)
            // {
            //     $parameter = json_encode($lead);

            //     // 1 parameter is URL , 2  (Lead Data) parameter is data , 3 parameter is method
            //     $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
            //     if($status == true)
            //     {
            //         return redirect()->back()->with('success', __('Lead to deal conversion  successfully created.'));
            //     }
            //     else
            //     {
            //         return redirect()->back()->with('error', __('Lead to deal conversion call failed.'));
            //     }
            // }

            return redirect()->back()->with('success', __('Lead to deal conversion successfully converted.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function labels($id)
    {
        $lead = Lead::find($id);

        $labels   = Label::where('pipeline_id', '=', $lead->pipeline_id)->get();

        $selected = $lead->labels();
        if($selected)
        {
            $selected = $selected->pluck('name', 'id')->toArray();
        }
        else
        {
            $selected = [];
        }

        return view('lead.labels', compact('lead', 'labels', 'selected'));
    }

    public function labelStore($id, Request $request)
    {
        if(\Auth::user()->super_admin_employee == 1)
        {
            $leads = Lead::find($id);
            if($request->labels)
            {
                $leads->labels = implode(',', $request->labels);
            }
            else
            {
                $leads->labels = $request->labels;
            }
            $leads->save();

            return redirect()->back()->with('success', __('Labels successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }



    public function changePipeline(Request $request)
    {
        $user                   = \Auth::user();
        $user->default_pipeline = $request->pipeline_id;
        $user->save();

        return redirect()->back();
    }

    function clientNumber()
    {
        $latest = Client::where('created_by', '=', \Auth::user()->crmcreatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->client_id + 1;
    }

    public function fileExports()
    {
        $name = 'leads_' . date('Y-m-d i:h:s');
        $data = Excel::download(new LeadsExport(), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
    public function fileImportExport()
    {
        return view('lead.import');
    }
    public function fileImport(Request $request)
    {
        $rules      = ['file' => 'required|mimes:csv,txt,xlsx',];
        $validator  = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $leads        = (new LeadsImport())->toArray(request()->file('file'))[0];

        $totalleads    = count($leads) - 1;
        $errorArray     = [];
        for ($i = 1; $i <= count($leads) - 1; $i++) {
            $lead          = $leads[$i];
            $leadByEmail   = Lead::where('email', $lead[2])->first();
            if (!empty($leadByEmail)) {
                $errorArray[$i] = $leadByEmail->email;
            } else {
                $leadData = new Lead();
                $leadData->name = $lead[0];
                $leadData->email = $lead[1];
                $leadData->subject = $lead[2];
                $leadData->user_id = $lead[4];
                $leadData->pipeline_id = $lead[5];
                $leadData->stage_id = $lead[6];
                $leadData->phone_no = $lead[3];
                $leadData->created_by = \Auth::user()->id;
                $leadData->is_active = (int)$lead[7];
                $leadData->date = $lead[8];
                $leadData->save();
            }
        }
        if (!empty($errorArray)) {
            $data['status'] = 'error';
            $data['msg'] = $totalleads . '  ' . __('Record imported fail out of' . ' ' . count($errorArray) . ' ' . 'record');
        } else {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        }
        return redirect()->back()->with($data['status'], $data['msg']);
    }
}
