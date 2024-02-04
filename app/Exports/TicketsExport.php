<?php

namespace App\Exports;

use App\Models\Ticket;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TicketsExport implements FromCollection ,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = Ticket::get();
        foreach($data as $k => $tickets)
        {
            $category = Ticket::category($tickets->category);
            $priority = Ticket::Managepriority($tickets->priority);

            unset(
                $tickets->id,
                $tickets->created_by,
                $tickets->attachments,
                $tickets->note,
                $tickets->created_at,
                $tickets->updated_at
            );

            $data[$k]['category'] = $category;
            $data[$k]['priority'] = $priority;

        }

        return $data;
    }

    public function headings(): array
    {
        return [
            "Ticket_ID",
            "Name",
            "Email",
            "Category",
            "Priority",
            "Subject",
            "Status",
            "Description",
            "Reslove_At",
        ];
    }
}



