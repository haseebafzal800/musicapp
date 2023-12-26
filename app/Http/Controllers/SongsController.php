<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Song;

class SongsController extends Controller
{
    function index(Request $request){
        if ($request->ajax()) {
            $data = Song::with('user', 'playlists')->orderBy('id','DESC')->get();
            // $data = Song::orderBy('id','DESC')->get();
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
                $btn .= ' <a class="btn btn-xs btn-primary" href="'.route('song.edit',$row->id).'"><i class="fas fa-pencil-alt"></i></a>';
                $url = route("song.delete", $row->id);
                $btn .= ' <a href="javascript:void(0)" onclick="DeleteMe(this, '."'".$url."'".')" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></a>';
                    
                return $btn;
            })
            ->addIndexColumn()
            ->addColumn('username', function ($row) {
                return $row->user->name??'';
            })
            ->addColumn('playlist', function ($row) {
                $playlistName='';
                foreach ($row->playlists as $playlist) {
                    $playlistName .= ' <label class="badge badge-success">'.$playlist->title.'</label>'; // Replace 'name' with the actual attribute you want to retrieve from the Playlist model
                    // Access other playlist information...
                }
                return $playlistName;
            })
            ->rawColumns(['status', 'username', 'playlist', 'action'])
            ->make(true);
        }
        $data['pageTitle'] = 'Songs';
        $data['songsListActive'] = 'active';
        $data['songsOpening'] = 'menu-is-opening';  
        $data['songsOpend'] = 'menu-open';
        return view('admin.songs.index', $data);
    }
    public function edit($id)
    {
        $data['pageTitle'] = 'Generouses';
        $data['songsListActive'] = 'active';
        $data['songsOpening'] = 'menu-is-opening';  
        $data['songsOpend'] = 'menu-open';
        $data['song'] = Song::find($id);
        return view('admin.songs.edit', $data);
    }
    public function update(Request $request)
    {
        $input = $request->all();
        
        $validated = request()->validate([
            'title' => 'required|max:255',
            'status' => 'required',
        ]);
        
        $Playlist = Song::where('id', $input['id'])->update([
            'title'    => $input['title'],
            'status'    => $input['status'],
        ]);
        return redirect('/admin/songs');
    }
    public function delete($id)
    {
        if(Song::find($id)->delete()){
            return 'ok';
        }else{
            return 'notok';
        }
    }
}
