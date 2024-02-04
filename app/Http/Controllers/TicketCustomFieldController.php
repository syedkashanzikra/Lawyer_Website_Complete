<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomField;
use Illuminate\Support\Facades\Auth;
class TicketCustomFieldController extends Controller
{
    public function index(Request $request)
    {
        $user = \Auth::user();
        if($user->super_admin_employee == 1 || $user->type == 'company')
        {

            $customFields = CustomField::orderBy('order')->get();
            return view('customFields.index', compact('customFields'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function storeCustomFields(Request $request)
    {
        $rules      = [
            'fields' => 'required|present|array',
        ];
        $attributes = [];

        if($request->fields)
        {
            foreach($request->fields as $key => $val)
            {
                $rules['fields.' . $key . '.name']      = 'required|max:255';
                $attributes['fields.' . $key . '.name'] = __('Field Name');
            }
        }

        $validator = \Validator::make($request->all(), $rules, [], $attributes);
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }


        $field_ids = CustomField::orderBy('order')->pluck('id')->toArray();

        $order = 0;

        foreach($request->fields as $key => $field)
        {
            $fieldObj = new CustomField();
            if(isset($field['id']) && !empty($field['id']))
            {
                $fieldObj = CustomField::find($field['id']);
                if(($key = array_search($fieldObj->id, $field_ids)) !== false)
                {
                    unset($field_ids[$key]);
                }
            }
            $fieldObj->name        = $field['name'];

            $fieldObj->placeholder = $field['placeholder'];
            if(isset($field['type']) && !empty($field['type']))
            {
                if(isset($fieldObj->id) && $fieldObj->id > 6)
                {
                    $fieldObj->type = $field['type'];
                }
                elseif(!isset($fieldObj->id))
                {
                    $fieldObj->type = $field['type'];
                }
            }
            $fieldObj->width  = (isset($field['width'])) ? $field['width'] : '12';
            $fieldObj->status = 1;
            if(isset($field['is_required']))
            {
                if(isset($fieldObj->id) && $fieldObj->id > 6)
                {
                    $fieldObj->is_required = $field['is_required'];
                }
                elseif(!isset($fieldObj->id))
                {
                    $fieldObj->is_required = $field['is_required'];
                }
            }
            $fieldObj->created_by = Auth::id();
            $fieldObj->order      = $order++;
            $fieldObj->save();
        }

        if(!empty($field_ids) && count($field_ids) > 0)
        {
            CustomField::whereIn('id', $field_ids)->where('status', 1)->delete();
        }

        return redirect()->back()->with('success', __('Fields Saves Successfully.!'));
    }
}
