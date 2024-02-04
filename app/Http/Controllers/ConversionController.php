<?php

namespace App\Http\Controllers;

use App\Models\Conversion;
use App\Mail\SendTicketAdminReply;
use App\Models\Ticket;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConversionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$ticket_id)
    {

        $user = \Auth::user();
        $ticket = Ticket::find($ticket_id);
        if($ticket) {
            $validation = ['reply_description' => ['required']];

            $this->validate($request, $validation);

            $post = [];
            $post['sender'] = ($user)?$user->id:'user';
            $post['ticket_id'] = $ticket->id;
            $post['description'] = $request->reply_description;
            $data = [];
            if($request->hasfile('reply_attachments'))
            {
                $errors=[];
                foreach($request->file('reply_attachments') as $filekey => $file)
                {
                    $name = $file->getClientOriginalName();
                    $dir        = ('reply_tickets/' . $post['ticket_id'].'/');
                    $path = Utility::keyWiseUpload_file($request,'reply_attachments',$name,$dir,$filekey,[]);

                    if($path['flag'] == 1){
                        $temp=explode('/',$path['url']);
                        $data[] = end($temp);
                    }
                    elseif($path['flag'] == 0){
                        $errors = __($path['msg']);

                    }

                }
            }
            $post['attachments'] = json_encode($data);
            $conversion = Conversion::create($post);
            Conversion::change_status($ticket_id);

                // // slack //

                // $settings  = Utility::settings(\Auth::user()->supportTicketCreatorId());
                // if(isset($settings['reply_notification']) && $settings['reply_notification'] ==1){
                //     $uArr = [
                //         'name' => $request->name,
                //         'ticket_id' => $ticket->id,
                //         'email' => $ticket->email,
                //         'description' => $request->reply_description,
                //         'user_name'  => \Auth::user()->name,
                //     ];
                //     Utility::send_slack_msg('new_ticket_reply', $uArr);
                // }

                // // telegram //
                // $settings  = Utility::settings(\Auth::user()->supportTicketCreatorId());
                // if(isset($settings['telegram_reply_notification']) && $settings['telegram_reply_notification'] ==1){
                //     $uArr = [
                //         'name' => $request->name,
                //         'ticket_id' => $ticket->id,
                //         'email' => $ticket->email,
                //         'description' => $request->reply_description,
                //         'user_name'  => \Auth::user()->name,
                //     ];
                //     Utility::send_telegram_msg('new_ticket_reply', $uArr);
                // }


            // Send Email to User
            $uArr = [
                'name' => $request->name,
                'ticket_id' => $ticket->id,
                'email' => $ticket->email,
                'description' => $request->reply_description,
            ];

            // $module = 'New Ticket Reply';
            // $webhook =  Utility::webhookSetting($module,$user->created_by);

            // if ($webhook) {
            //     $parameter = json_encode($conversion);
            //     // 1 parameter is  URL , 2 parameter is data , 3 parameter is method
            //     $status = Utility::WebhookCall($webhook['url'], $parameter, $webhook['method']);
            //     if ($status == true) {

            //         return redirect()->back()->with('success', __('Reply successfully added!'));
            //     } else {
            //         return redirect()->back()->with('error', __('Webhook call failed.'));
            //     }
            // }

            try {

                Mail::to($ticket->email)->send(new SendTicketAdminReply($ticket,$conversion));
            }catch (\Exception $e){
                $error_msg = "E-Mail has been not sent due to SMTP configuration ";
            }
            return redirect()->back()->with('success', __('Reply added successfully').((isset($error_msg))?'<br> <span class="text-danger">'.$error_msg.'</span>':''));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Conversion  $conversion
     * @return \Illuminate\Http\Response
     */
    public function show(Conversion $conversion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Conversion  $conversion
     * @return \Illuminate\Http\Response
     */
    public function edit(Conversion $conversion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Conversion  $conversion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Conversion $conversion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Conversion  $conversion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conversion $conversion)
    {
        //
    }
}
