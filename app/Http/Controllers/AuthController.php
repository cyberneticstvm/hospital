<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;
use Session;
use App\Models\User;
use App\Models\Branch;
use App\Models\doctor;
use Spatie\Permission\Models\Role;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */    

    function __construct()
    {
         $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index','store','show']]);
         $this->middleware('permission:user-create', ['only' => ['create','store']]);
         $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function create()
    {
        $permission = Permission::get();
        return view('user.create',compact('permission'));
    }

    public function index()
    {
        $users = User::orderBy('name','DESC')->get();
        return view('user.index', compact('users'));
    }

    public function store_branch_session(Request $request){
        $this->validate($request, [
            'branch_id' => 'required',
        ]);
        $request->session()->put('branch', $request->branch_id);
        $branches = DB::table('branches')->get();
        
        return redirect()->route('dash')
                        ->with('success','Branch updated successfully');
        //return view('dash', compact('branches'));
    }
    /*public function createform(){
        $roles = Role::pluck('name','name')->all();
        return view('user.create', compact('roles'));
    }*/
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $items = $request->get('branch_id');
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'roles' => 'required',
            'branch_id' => 'required',
            'doctor_id' => 'required_if:roles,==,2'
        ]);       
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);

        foreach($items as $key => $value):
            DB::table('user_branches')->insert([
                'user_id' => $user->id,
                'branch_id' => $value
            ]);
        endforeach;
       
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('user.index')
                        ->with('success','User created successfully');
    }
    /*public function create_user(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'username' => $data['username'],
        'password' => Hash::make($data['password'])
      ]);
    }*/    
    public function userlogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6',
        ]);
   
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            $user_id = Auth::user()->id;
            $branches = DB::table('branches')->leftJoin('user_branches', 'branches.id', '=', 'user_branches.branch_id')->select('branches.id', 'branches.branch_name')->where('user_branches.user_id', '=', $user_id)->get();

            return view('dash', compact('branches'));
            //return redirect()->route('dash')->with(['branches' => $branches]);
        }  
        return redirect("/")->withErrors('Login details are not valid');
    }

    public function show()
    {
        $branches = Branch::get();
 
        $doctors = DB::table('doctors')->whereNotIn('id', function($query) {
            $query->select('doctor_id')->from('users');
        })->get();

        $roles = Role::pluck('name','name')->all();
        return view('user.create', compact('roles', 'branches', 'doctors'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userlogout() {
        Session::flush();
        Auth::logout();  
        return Redirect('/');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        $branches = Branch::get();
        $doctors = DB::table('doctors')->whereIn('id', function($query) use ($id){
            $query->select('doctor_id')->from('users')->where('id', '=', $id);
        })->get();
    
        return view('user.edit',compact('user','roles','userRole', 'branches', 'doctors'));
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
        $items = $request->get('branch_id');
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'roles' => 'required',
            'branch_id' => 'required',
            'doctor_id' => 'required_if:roles,==,2'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);

        DB::table("user_branches")->where('user_id', $id)->delete();
        foreach($items as $key => $value):
            DB::table('user_branches')->insert([
                'user_id' => $id,
                'branch_id' => $value
            ]);
        endforeach;        

        DB::table('model_has_roles')->where('model_id', $id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('user.index')
                        ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('user.index')
                        ->with('success','User deleted successfully');
    }
}
