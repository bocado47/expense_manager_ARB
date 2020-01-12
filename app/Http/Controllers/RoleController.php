<?php

namespace App\Http\Controllers;

use App\Roles;
use App\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        return view('rolesview');
    }
    public function getData(Request $request)
    {
        $columns = array(
            0 => 'id',
			1 => 'name',
            2 => 'desc'
		);
		
		$totalData = Roles::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$posts = Roles::offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();
			$totalFiltered = Roles::count();
		}else{
			$search = $request->input('search.value');
			$posts = Roles::where('display_name', 'like', "%{$search}%")
                            ->orWhere('description','like',"%{$search}%")
							->offset($start)
							->limit($limit)
							->orderBy($order, $dir)
							->get();
			$totalFiltered = Roles::where('display_name', 'like', "%{$search}%")
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
        $roles = new Roles;

        $roles->display_name =   $request->input('name');
        $roles->description =   $request->input('desc');

        $roles->save();
    }
    public function display(Request $request)
    {
        $id=$request->get('id');
        $Roles=Roles::find($id);
        return view('editroleview',compact('Roles','id'));
    }
    public function updateRole(Request $request)
    {
        $id=$request->get('e_id');

        $role=Roles::find($id);

        $role->display_name =   $request->get('name');
        $role->description =   $request->get('desc');

        $role->save();

        echo json_encode($role);
    }
    public function deleteRole(Request $request)
    {
        $id=$request->get('e_id');

        $role=Roles::find($id);
        $role->delete();
        
        echo json_encode($role);
    }
}
