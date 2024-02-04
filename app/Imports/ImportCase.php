<?php

namespace App\Imports;

use App\Models\Cases;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
class ImportCase implements ToModel
{
    use Importable;

    public function model(array $row)
    {
    }
}
