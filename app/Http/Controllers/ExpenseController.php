<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Category;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $Category = Category::all();
        
        return view('expensesview',compact('Category'));
    }
    public function getData(Request $request)
    {
        $columns = array(
            0 => 'id',
			1 => 'category',
            2 => 'amount',
            3 => 'entry_date',
			4 => 'created_at'
		);
		
		$totalData = Expense::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$posts = Expense::offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();
			$totalFiltered = Expense::count();
		}else{
			$search = $request->input('search.value');
			$posts = Expense::where('name', 'like', "%{$search}%")
                            ->orWhere('email','like',"%{$search}%")
                            ->orWhere('role','like',"%{$search}%")
							->orWhere('created_at','like',"%{$search}%")
							->offset($start)
							->limit($limit)
							->orderBy($order, $dir)
							->get();
			$totalFiltered = Expense::where('name', 'like', "%{$search}%")
							->orWhere('email','like',"%{$search}%")
							->count();
		}		
					
		
		$data = array();
		
		if($posts){
			foreach($posts as $r){
                $nestedData['id'] = $r->id;
				$nestedData['amount'] = $r->amount;
                $nestedData['entry_date'] = $r->entry_date;
                $category=$r->category;
                $cat=Category::find($category);
                $nestedData['category'] = $cat->display_name;
				$nestedData['created_at'] = date('d-m-Y H:i:s',strtotime($r->created_at));
				$data[] = $nestedData;
			}
		}
		
		$json_data = array(
			"draw"			=> intval($request->input('draw')),
			"recordsTotal"	=> intval($totalData),
			"recordsFiltered" => intval($totalFiltered),
			"data"			=> $data
		);
		
		echo json_encode($json_data);

    }
    public function store(Request $request)
    {
        $exp = new Expense;

        $exp->category =   $request->input('category');
        $exp->amount =   $request->input('amount');
        $exp->entry_date =   $request->input('entry_date');

        $exp->save();
    }
    public function display(Request $request)
    {
        $Category = Category::all();
        $id=$request->get('id');
        $expense=Expense::find($id);
        return view('editexpenseview',compact('Category','expense','id'));
    }
    public function updateExpense(Request $request)
    {
        $id=$request->get('e_id');

        $exp=Expense::find($id);

        $exp->category =   $request->get('category');
        $exp->amount =   $request->get('amount');
        $exp->entry_date =   $request->get('entry_date');
        $exp->save();

        echo json_encode($exp);
    }
    public function deleteExpense(Request $request)
    {
        $id=$request->get('e_id');

        $exp=Expense::find($id);
        $exp->delete();
        
        echo json_encode($exp);
    }
}
