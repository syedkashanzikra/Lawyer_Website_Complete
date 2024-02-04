<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Knowledge extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'description', 'category'
    ];

    public function getCategoryInfo()
    {
       $th = $this->hasOne('App\Models\Knowledgebasecategory', 'id', 'category');
        return $th;
    }

    public static function knowlege_details($id)
    {        
        $knowledge = Knowledgebasecategory::where('id',$id)->first();
        // dd($knowledge->title);
        if($knowledge)
        {
            return $knowledge->title;      
        }
    }

    public static function category_count($id)
    {        
        $knowledge = Knowledge::where('category',$id)->count();
        if($knowledge)
        {
            return $knowledge;   
        }
    }
}
