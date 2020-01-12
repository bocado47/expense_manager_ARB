<?php

namespace App\Http\Controllers;

use App\UserData;
use App\Roles;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Redirect,Response;

class UserController extends Controller
{
    public function index()
    {
        // $users = User::with('city')->get();
        $Roles = Roles::all();
        
        return view('userview',compact('Roles'));
    }
    public function getData(Request $request)
    {
        $columns = array(
            0 => 'id',
			1 => 'name',
            2 => 'email',
            3 => 'role',
			4 => 'created_at'
		);
		
		$totalData = UserData::count();
		$limit = $request->input('length');
		$start = $request->input('start');
		$order = $columns[$request->input('order.0.column')];
		$dir = $request->input('order.0.dir');
		
		if(empty($request->input('search.value'))){
			$posts = UserData::offset($start)
					->limit($limit)
					->orderBy($order,$dir)
					->get();
			$totalFiltered = UserData::count();
		}else{
			$search = $request->input('search.value');
			$posts = UserData::where('name', 'like', "%{$search}%")
                            ->orWhere('email','like',"%{$search}%")
                            ->orWhere('role','like',"%{$search}%")
							->orWhere('created_at','like',"%{$search}%")
							->offset($start)
							->limit($limit)
							->orderBy($order, $dir)
							->get();
			$totalFiltered = UserData::where('name', 'like', "%{$search}%")
							->orWhere('email','like',"%{$search}%")
							->count();
		}		
					
		
		$data = array();
		
		if($posts){
			foreach($posts as $r){
                $nestedData['id'] = $r->id;
				$nestedData['name'] = $r->name;
                $nestedData['email'] = $r->email;
                $role=$r->role;
                $roles=Roles::find($role);
                $nestedData['role'] = $roles->display_name;
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
        $user = new UserData;

        $user->name =   $request->input('name');
        $user->password =   Hash::make('12345678');
        $user->email =   $request->input('email');
        $user->role =   $request->input('roles');

        $user->save();
    }
    public function display(Request $request)
    {
        $Roles = Roles::all();
        $id=$request->get('id');
        $users=UserData::find($id);
        return view('edituserview',compact('Roles','users','id'));
    }
    public function updateUser(Request $request)
    {
        $id=$request->get('e_id');

        $users=UserData::find($id);

        $users->name    =   $request->get('e_name');
        $users->email    =   $request->get('e_email');
        $users->role    =   $request->get('e_roles');
        $users->save();

        echo json_encode($users);
    }
    public function deleteUser(Request $request)
    {
        $id=$request->get('e_id');

        $users=UserData::find($id);
        $users->delete();
        
        echo json_encode($users);
    }
}
