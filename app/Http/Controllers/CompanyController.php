<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class CompanyController extends Controller
{
    public function company_index()
    {
        $companies = Company::all();

        return view(
            'masterfiles.company_index',
            array(
                'header' => 'masterfiles',
                'companies' => $companies,

            )
        );
    }
    // Save company
    public function store_company(Request $request)
    {
        // dd($request->all());
        $new_company = new Company();
        $new_company->company_name = $request->company_name;
        $new_company->company_code = $request->company_code;
        $new_company->encoded_by = Auth::user()->id;

        // Save Logo
        $attachment = $request->file('logo_file');
        $original_name = $attachment->getClientOriginalName();
        $name = time() . '_' . $attachment->getClientOriginalName();
        $attachment->move(public_path() . '/images/', $name);
        $file_name = '/images/' . $name;
        $new_company->logo = $file_name;

        // Save Icon
        $attachment = $request->file('icon_file');
        $original_name = $attachment->getClientOriginalName();
        $name = time() . '_' . $attachment->getClientOriginalName();
        $attachment->move(public_path() . '/images/', $name);
        $file_name = '/images/' . $name;
        $new_company->icon = $file_name;

        $new_company->save();

        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
    }
}
