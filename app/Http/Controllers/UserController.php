<?php

namespace App\Http\Controllers;

use App\Company;
use App\matchpair;
use App\Order;
use App\User;
use App\ComStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
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
        $stu =User::create([
            'name'=>$request->name,
        ]);
        return response()->json(['result'=>$stu],200);
    }
    public function setorder(Request $request)
    {
        $stu_wishes =Order::create([
            'user_id'=>$request->user_id,
            'company_id'=>$request->company_id,
            'wishes'=>$request->wishes,
        ]);
        return response()->json(['result'=>$stu_wishes],200);
    }

    public function descide(Request $request)
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();

        //com_students user_id order=0
//        $pick_list=ComStudent::where(['order'=>0])->get()->groupBy('user_id');
////        return response()->json(['result'=>$pick_list],200);
//        $users=[];
//        foreach ($pick_list as $student){
//
//            $companies=[];
//            foreach ($student as $an_order){
//                $companies[]=$an_order->company_id;
//            }
//            $stu_wishes = Order::where(['user_id'=>$student[0]->user_id])->whereIn('company_id',$companies)->orderBy('wishes', 'asc')->first();
//            $users[]=$stu_wishes->user_id;
//
//            //DB transitaion
//            $matched = matchpair::create([
//                'user_id'=>$stu_wishes->user_id,
//                'company_id'=>$stu_wishes->company_id
//            ]);
//        }
        // 備取

        $sofar=matchpair::select('company_id', DB::raw('count(*) as total'))->groupBy('company_id')->get();
        $Remainder=[];
        $remainder_student=[];
        foreach ($sofar as $key=>$value){ //check the Remainder of company
            $quota = Company::where('id',$value->company_id)->select('quota')->first();

                if($value->total<$quota->quota){
                    $value->total=$quota->quota-$value->total;
                    $Remainder[]=$value;
                }
        }
        foreach ($Remainder as $com){
            $students = ComStudent::where([['chosen','=',0],['com_students.company_id','=',$com->company_id]])
                ->orderBy('order')
                ->join('orders','orders.user_id', '=','com_students.user_id' )
                ->where( 'orders.company_id', '=',$com->company_id)
                ->select('orders.user_id','orders.wishes','orders.company_id','com_students.order')
                ->get();
            $remainder_student[]=$students[0];
        }

//        $out->writeln($remainder_student);
        foreach ($remainder_student as $data){
            $temp_chose=$data;
            foreach ($remainder_student as $data_info){
                if ($data_info->user_id==$temp_chose->user_id){
                    if ($data_info->wishes < $data->wishes){
                        $temp_chose=$data_info;
                    }
                }
            }
            $matched = matchpair::create([
                'user_id'=>$temp_chose->user_id,
                'company_id'=>$temp_chose->company_id
            ]);

            foreach ($Remainder as $key_=>$item){
                if($item->company_id==$temp_chose->company_id){
                    $item->total-=1;
                    if ($item->total==0){
                        unset($Remainder[$key_]);
                        $out->writeln("com: ".$item->company_id." empty");
                    }
                    $out->writeln("same");

                }

            }
            return response()->json(['result'=>$Remainder],200);


        }
        $out->writeln($remainder_student);
        return response()->json(['result'=>$Remainder],200);

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
