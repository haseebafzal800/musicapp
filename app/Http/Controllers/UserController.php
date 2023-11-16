<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use DataTables;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

    
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Request $request): View
    // {
    //     $data = User::latest()->paginate(5);
  
    //     return view('admin.users.index',compact('data'))
    //         ->with('i', ($request->input('page', 1) - 1) * 5);
    // }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            if(Auth::user()->hasRole('Admin')){
                $data = User::orderBy('id','DESC')->with('roles')->get();
            }elseif(Auth::user()->hasRole('Client')){
                $data = User::orderBy('id','DESC')->with('roles')->where('client_id', Auth::user()->client_id)->get();
            }
            // echo "<pre>"; print_r($data); die;
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('roles', function ($row) {
                    $roles='';
                    $mroles = $row->getRoleNames();
                    if(!empty($mroles)){
                        foreach($mroles as $v){
                            $roles .='<label class="badge badge-success">'.$v.'</label>';
                        }
                    }
                    return $roles ;
                })
                ->addColumn('action', function($row){
                    // $url = "/notification/delete/".$row->id;
                    // $btn = '<a href="javascript:void(0)" onclick="DeleteMe(this, '."'".$url."'".')" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></a>';
                    // $btn .= ' <a href="'.@url("/meeting/$row->meeting_id/participent".$row->id).'" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>';
                    $btn='';
                    if (Gate::allows('approve-user')){
                        $btn .=' <a class="btn btn-xs btn-info" href="'.route('users.show',$row->id).'"><i class="fas fa-eye"></i></a>';
                        if($row->client_id > 0 && $row->is_approved=='on'){
                        $btn .=' <a class="btn btn-xs btn-primary" href="'.route('users.unapprove',$row->id).'"><i class="fas fa-check"></i></a>';
                        }elseif($row->client_id == '' && $row->is_approved=='ban'){
                        $btn .= ' <a class="btn btn-xs btn-danger" href="'.route('users.unapprove',$row->id).'"><i class="fas fa-ban"></i></a>';
                        }else{
                            $btn .= ' <a class="btn btn-xs btn-primary" href="'.route('users.approved',$row->id).'"><i class="fas fa-check"></i></a>';
                        $btn .= ' <a class="btn btn-xs btn-danger" href="'.route('users.unapprove',$row->id).'"><i class="fas fa-ban"></i></a>';
                        }
                    }
                    $btn .= ' <a class="btn btn-xs btn-primary" href="'.route('users.edit',$row->id).'"><i class="fas fa-pencil-alt"></i></a>';
                    $url = url("/admin/users/destroy/".$row->id);
                    $btn .= ' <a href="javascript:void(0)" onclick="DeleteMe(this, '."'".$url."'".')" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></a>';
                       
                    return $btn;
                })
                ->addColumn('rownum', function ($row) {
                    return '';
                })
                ->rawColumns(['rownum','roles', 'action'])
                ->make(true);
        }
        $data['pageTitle'] = 'Users';
        $data['userListActive'] = 'active';
        $data['userOpening'] = 'menu-is-opening';  
        $data['userOpend'] = 'menu-open';
        return view('admin.users.index', $data);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name','name')->all();
        return view('admin.users.create',compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);
        return view('admin.users.show',compact('user'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('admin.users.edit',compact('user','roles','userRole'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}

?>