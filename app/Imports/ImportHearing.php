<?php

namespace App\Imports;

use App\Models\Hearing;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;

class ImportHearing implements ToModel
{
    use Importable;

    public function model(array $row)
    {
    }
}
