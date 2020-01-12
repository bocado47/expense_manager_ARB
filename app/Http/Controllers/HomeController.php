<?php

namespace App\Http\Controllers;

use DB;
use App\Expense;
use App\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $date=date('Y-m-d');
        $expenses = DB::table('expenses')
                    ->select(DB::raw('sum(amount) as total, category'))
                    ->where('entry_date',$date)
                    ->groupBy('category')
                    ->get();
        $cat=Category::all();
        $final=array();
        foreach($expenses as $vl)
        {
            $id=$vl->category;
            $cat=Category::find($id);
            $final[]=array('name'=>$cat->display_name,'y'=>$vl->total);
        }
        // print_r($final);
        // die();
        return view('home',compact('expenses','cat','final'));
    }
}
