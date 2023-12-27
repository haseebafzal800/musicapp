<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Songs;

class PlaylistsController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:playlist-list|playlist-create|playlist-edit|playlist-delete', ['only' => ['index','store']]);
         $this->middleware('permission:playlist-create', ['only' => ['create','store']]);
         $this->middleware('permission:playlist-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:playlist-delete', ['only' => ['delete']]);
    }
    function index(Request $request){
        if ($request->ajax()) {
            // $data = Playlist::orderBy('id','DESC')->get();
            $data = Playlist::orderBy('id','DESC')->with('user')->get();
            // echo "<pre>"; print_r($data); die;
            return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                $status='';
                if($row->status==1){
                        $status.='<label class="badge badge-success">Active</label>';
                    } else {
                    $status.='<label class="badge badge-danger">InActive</label>';
                }
                return $status;
            })
            ->addColumn('action', function($row){
                $btn='';
                $btn .= ' <a class="btn btn-xs btn-primary" href="'.route('playlist.edit',$row->id).'"><i class="fas fa-pencil-alt"></i></a>';
                $url = route("playlist.delete", $row->id);
                $btn .= ' <a href="javascript:void(0)" onclick="DeleteMe(this, '."'".$url."'".')" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></a>';
                    
                return $btn;
            })
            ->addColumn('rownum', function ($row) {
                return '';
            })
            ->addColumn('username', function ($row) {
                return $row->user->name??'';
            })
            ->rawColumns(['rownum','status', 'username', 'action'])
            ->make(true);
        }
        $data['pageTitle'] = 'Playlists';
        $data['playlistsListActive'] = 'active';
        $data['playlistsOpening'] = 'menu-is-opening';  
        $data['playlistsOpend'] = 'menu-open';
        return view('admin.Playlist.index', $data);
    }
    public function edit($id)
    {
        $data['pageTitle'] = 'Edit Playlist';
        $data['playlistsListActive'] = 'active';
        $data['playlistsOpening'] = 'menu-is-opening';  
        $data['playlistsOpend'] = 'menu-open';
        $data['playlist'] = Playlist::find($id);
        return view('admin.Playlist.edit', $data);
    }
    public function update(Request $request)
    {
        $input = $request->all();
        
        $validated = request()->validate([
            'title' => 'required|max:255',
            'status' => 'required',
        ]);
        
        $Playlist = Playlist::where('id', $input['id'])->update([
            'title'    => $input['title'],
            'status'    => $input['status'],
        ]);
        return redirect('/admin/playlists')->with('success','playlist updated successfully');
    }
    public function delete($id)
    {
        if(Playlist::find($id)->delete()){
            return 'ok';
        }else{
            return 'notok';
        }
    }
}
