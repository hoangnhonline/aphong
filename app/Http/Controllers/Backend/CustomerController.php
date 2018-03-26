<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\DataVideo;
use Helper, File, Session, Auth, Cache;

class CustomerController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function index(Request $request)
    {
        $status = isset($request->status) ? $request->status : 0;
        

        $fullname = isset($request->fullname) && $request->fullname != '' ? $request->fullname : '';
        $email = isset($request->email) && $request->email != '' ? $request->email : '';       
        
        $query = Customer::whereRaw('1');

        $status = 1;

        if( $status > 0){
            $query->where('status', $status);
        }        
        if( $fullname != ''){
            $query->where('fullname', 'LIKE', '%'.$fullname.'%');
        }
        
        if( $email != ''){
            $query->where('email', 'LIKE', '%'.$email.'%');
        }
        $items = $query->orderBy('id', 'desc')->paginate(20);
        
        return view('backend.customer.index', compact( 'items', 'email', 'status' , 'fullname'));
    }    
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  Request  $request
    * @return Response
    */    

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
    public function show($id)
    {
    //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
    public function edit($id)
    {
        $tagSelected = [];

        $detail = Customer::find($id);

        return view('backend.customer.edit', compact('detail'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  Request  $request
    * @param  int  $id
    * @return Response
    */
    public function update(Request $request)
    {
        $dataArr = $request->all();
        
        $this->validate($request,[                              
            'valid_from' => 'required',
            'valid_to' => 'required|after:valid_from',
        ]);
    
        $dataArr['updated_user'] = Auth::user()->id;
        Cache::put("valid-".$dataArr['id'], $dataArr['valid_from'].":".$dataArr['valid_to'], 1800);
        $model = Customer::find($dataArr['id']);

        $model->update($dataArr);

        Session::flash('message', 'Update success');        

        return redirect()->route('customer.edit', $dataArr['id']);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return Response
    */
    public function destroy($id)
    {
        // delete
        $model = Customer::find($id);
        $model->delete();
        DataVideo::where('customer_id', $id)->delete();
        // redirect
        Session::flash('message', 'Delete success');
        return redirect()->route('customer.index');
    }
}
