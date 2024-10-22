<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SssMatrixContribution;
use RealRashid\SweetAlert\Facades\Alert;
use Excel;
use App\Exports\SssMatrixContributionExport;
use App\Imports\SssMatrixContributionImport;

class SssMatrixContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('sss_matrix_contributions.index', array(
            'header' => 'sss_matrix_contributions',
            'contributions' => SssMatrixContribution::all()
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->is_no_limit && $request->min_salary >= $request->max_salary){
            Alert::warning('Warning : Minimum Salary must be greater than Max Salary!')->persistent('Dismiss');
            return back();
        }

        $min_salary = SssMatrixContribution::min('min_salary');
        $max_salary = SssMatrixContribution::min('max_salary');

        $contribution = new SssMatrixContribution;
        $contribution->min_salary = $request->min_salary;
        $contribution->max_salary = $request->max_salary;
        $contribution->employee_share_ee = $request->employee_share_ee;
        $contribution->employer_share_er = $request->employer_share_er;
        $contribution->total_contribution = $request->total_contribution;
        $contribution->is_no_limit = $request->is_no_limit;
        $contribution->save();

        Alert::success('Successfully Store')->persistent('Dismiss');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->is_no_limit && $request->min_salary >= $request->max_salary){
            Alert::warning('Warning : Minimum Salary must be greater than Max Salary!')->persistent('Dismiss');
            return back();
        }

        $contribution = SssMatrixContribution::findOrfail($id);
        $contribution->min_salary = $request->min_salary;
        $contribution->max_salary = $request->max_salary;
        $contribution->employee_share_ee = $request->employee_share_ee;
        $contribution->employer_share_er = $request->employer_share_er;
        $contribution->total_contribution = $request->total_contribution;
        $contribution->is_no_limit = $request->is_no_limit;
        $contribution->save();

        Alert::success('Successfully updated')->persistent('Dismiss');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        SssMatrixContribution::find($id)->delete();
        Alert::success('Successfully Deleted')->persistent('Dismiss');
        return back();
    }

    /**
     * Export to excel
     *
     */
    public function export(Request $request){
        return Excel::download(new SssMatrixContributionExport, ' SSS Matrix Contribution Export.xlsx');
    }

    /**
     * Import for excel for mass upload
     *
     */
    public function import(Request $request)
    {
        ini_set('memory_limit', '-1');
        $path = $request->file('file')->getRealPath();
        $data = Excel::toArray(new SssMatrixContributionImport, $request->file('file'));

        if(count($data[0]) > 0){
            $save_count = 0;
            $not_save = [];
            foreach($data[0] as $key => $value){
                $contribution = SssMatrixContribution::where('min_salary',$value['min_salary'])
                    ->where('max_salary',$value['max_salary'])
                    ->first();

                if($contribution){
                    if(isset($value['min_salary'])) $contribution->min_salary = $value['min_salary'];
                    if(isset($value['max_salary'])) $contribution->max_salary = $value['max_salary'];
                    if(isset($value['employee_share_ee'])) $contribution->employee_share_ee = $value['employee_share_ee'];
                    if(isset($value['employer_share_er'])) $contribution->employer_share_er = $value['employer_share_er'];
                    if(isset($value['total_contribution'])) $contribution->total_contribution = $value['total_contribution'];
                    if(isset($value['mpf_ee'])) $contribution->mpf_ee = $value['mpf_ee'];
                    if(isset($value['mpf_er'])) $contribution->mpf_er = $value['mpf_er'];
                    if(isset($value['total_mpf'])) $contribution->total_mpf = $value['total_mpf'];
                    if(isset($value['is_no_limit'])) $contribution->is_no_limit = $value['is_no_limit'];
                
                    $contribution->save();
                    $save_count+=1;
                }else{
                    $contribution = new SssMatrixContribution;
                    $contribution->min_salary = $value['min_salary'];
                    $contribution->max_salary = $value['max_salary'];
                    $contribution->employee_share_ee = $value['employee_share_ee'];
                    $contribution->employer_share_er = $value['employer_share_er'];
                    $contribution->total_contribution = $value['total_contribution'];
                    $contribution->mpf_ee = $value['mpf_ee'];
                    $contribution->mpf_er = $value['mpf_er'];
                    $contribution->total_mpf = $value['total_mpf'];
                    $contribution->is_no_limit = $value['is_no_limit'];
                    $contribution->save();
                    $save_count+=1;
                }                                         
            }

            Alert::success('Successfully Import SSS Matrix Contributions (' . $save_count. ')')->persistent('Dismiss');
            return redirect('sss-matrix-contributions');
        }
    }
}