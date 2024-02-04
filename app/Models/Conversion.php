<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id','description', 'attachments', 'sender'
    ];

    public function replyBy(){
        if($this->sender=='user'){
            return $this->ticket;
        }
        else{
            return $this->hasOne('App\Models\User','id','sender')->first();
        }
    }

    public function ticket(){
        return $this->hasOne('App\Models\Ticket','id','ticket_id');
    }


    public  static function change_status($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        $ticket->status = 'In Progress';
        $ticket->update();
        return $ticket;
    }
}
