<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        return view('categoryview');
    }
    public function getData(Request $request)
    {
        $columns = array(
            0 => 'id',
			1 => 'name',
            2 => 'desc'
		);
		
		$totalData = Category::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$posts = Category::offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();
			$totalFiltered = Category::count();
		}else{
			$search = $request->input('search.value');
			$posts = Category::where('display_name', 'like', "%{$search}%")
                            ->orWhere('description','like',"%{$search}%")
							->offset($start)
							->limit($limit)
							->orderBy($order, $dir)
							->get();
			$totalFiltered = Category::where('display_name', 'like', "%{$search}%")
							->orWhere('description','like',"%{$search}%")
							->count();
		}		
					
		
		$data = array();
		
		if($posts){
			foreach($posts as $r){
                $nestedData['id'] = $r->id;
				$nestedData['name'] = $r->display_name;
                $nestedData['desc'] = $r->description;
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
        $cat = new Category;

        $cat->display_name =   $request->input('name');
        $cat->description =   $request->input('desc');

        $cat->save();
    }
    public function display(Request $request)
    {
        $id=$request->get('id');
        $category=Category::find($id);
        return view('editcatView',compact('category','id'));
    }
    public function updateCategory(Request $request)
    {
        $id=$request->get('e_id');

        $cat=Category::find($id);

        $cat->display_name =   $request->get('name');
        $cat->description =   $request->get('desc');

        $cat->save();

        echo json_encode($cat);
    }
    public function deleteCategory(Request $request)
    {
        $id=$request->get('e_id');

        $cat=Category::find($id);
        $cat->delete();
        
        echo json_encode($cat);
    }
}
