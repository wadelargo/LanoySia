<?php

// app/Imports/MembersImport.php

namespace App\Imports;

use App\Models\Membership;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MembersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Membership::updateOrCreate(
                ['name' => $row['name']],
                [
                    'description' => $row['description'],
                    'type_id' => $this->getTypeId($row['type']),
                    'price' => $row['price'],
                ]
            );
        }
    }

    private function getTypeId($typeName)
    {
        // Assuming you have a Type model to fetch the type ID by name
        return \App\Models\Type::where('name', $typeName)->first()->id;
    }
}

