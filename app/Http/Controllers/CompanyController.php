<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use App\Company;
use App\User;
use App\ComStudent;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $com = Company::create([
            'code'=>$request->code,
            'name'=>$request->name,
        ]);
        return response()->json(['result'=>$com],200);
    }
    public function pick(Request $request)
    {
        $user=User::where('code',$request->user_name);
        $com=Company::where('code',$request->code);
//        $picked =ComStudent::create([
//            'user_id'=>$user->id,
//            'company_id'=>$com->id,
//            'order'=>$request->order,//0為正取
//        ]);
        $picked =ComStudent::create([
            'user_id'=>$request->user_id,
            'company_id'=>$request->company_id,
            'order'=>$request->order,//0為正取
        ]);
        return response()->json(['result'=>$picked],200);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $stu_list=ComStudent::where([['company_id','=',$id],['order','=',0]])->get();
        foreach ($stu_list as $student){
            //
        }
        return response()->json(['result'=>$stu_list],200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
