<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('id','email','name','role')->get();
    }

    
    
    public function headings(): array
    {
        return [
            'ID',
            'Email',
            'Name',
            'Role'
        ];
    }
}
