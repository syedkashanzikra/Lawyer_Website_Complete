<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomField extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'placeholder',
        'width',
        'order',
        'status',
        'is_required',
        'custom_id',
        'created_by',
    ];

    public static $fieldTypes = [
        'text' => 'Text',
        'email' => 'Email',
        'number' => 'Number',
        'date' => 'Date',
        'textarea' => 'Textarea',
        'file' => 'File',
        'select' => 'Select',
    ];

    public static function saveData($obj, $data)
    {
        if(!empty($data) && count($data) > 0)
        {
            $RecordId = $obj->id;
            foreach($data as $fieldId => $value)
            {
                if(!empty($fieldId) && !empty($value))
                {
                    \DB::insert(
                        'insert into custom_field_values (`record_id`, `field_id`,`value`,`created_at`,`updated_at`) values (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`),`updated_at` = VALUES(`updated_at`) ', [$RecordId,$fieldId,$value,date('Y-m-d H:i:s'),date('Y-m-d H:i:s'), ]);
                }
            }
        }
    }

    public static function getData($obj)
    {
        return \DB::table('custom_field_values')->select(
            [
                'custom_field_values.value',
                'custom_fields.id',
            ]
        )->join('custom_fields', 'custom_field_values.field_id', '=', 'custom_fields.id')->where('record_id', '=', $obj->id)->get()->pluck('value', 'id');
    }
}
