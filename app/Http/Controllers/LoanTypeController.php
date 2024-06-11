<?php

namespace App\Http\Controllers;

use App\LoanType;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LoanTypeController extends Controller
{
    // Loan Types
    public function loanTypes_index()
    {
        $loanTypes = LoanType::all();
        return view('masterfiles.loanType_index', array(
            'header' => 'masterfiles',
            'loanTypes' => $loanTypes,
        ));
    }
    public function store_loanType(Request $request)
    {
        $loanTypes = new LoanType();
        $loanTypes->loan_name = $request->loan_name;
        $loanTypes->status = 'Active';
        $loanTypes->save();
        Alert::success('Successfully Stored')->persistent('Dismiss');
        return back();
    }
    public function disable_loanType($id)
    {

        LoanType::Where('id', $id)->update(['status' => 'Inactive']);
        return back();
    }
    public function enable_loanType($id)
    {
        LoanType::Where('id', $id)->update(['status' => 'Active']);
        return back();
    }
}
