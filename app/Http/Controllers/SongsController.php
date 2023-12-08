<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Song;

class SongsController extends Controller
{
    function index(Request $request){
        if ($request->ajax()) {
            $data = Song::with('user')->orderBy('id','DESC')->get();
            // $data = Song::orderBy('id','DESC')->get();
            echo "<pre>"; print_r($data); die;
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
                ->rawColumns(['rownum','status', 'action'])
                ->make(true);
        }
        $data['pageTitle'] = 'Generouses';
        $data['playlistsListActive'] = 'active';
        $data['playlistsOpening'] = 'menu-is-opening';  
        $data['playlistsOpend'] = 'menu-open';
        return view('admin.Playlist.index', $data);
    }
    public function edit($id)
    {
        $playlist = Song::find($id);
        return view('admin.Playlist.edit', compact('playlist'));
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
        return redirect('/admin/playlists');
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
