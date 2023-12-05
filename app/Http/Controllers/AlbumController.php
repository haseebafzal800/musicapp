<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Album;

class AlbumController extends Controller
{
    function index(Request $request){
        if ($request->ajax()) {
            $data = Album::orderBy('id','DESC')->get();
            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn='';
                    $btn .= ' <a class="btn btn-xs btn-primary" href="'.route('album.edit',$row->id).'"><i class="fas fa-pencil-alt"></i></a>';
                    $url = route("album.delete", $row->id);
                    $btn .= ' <a href="javascript:void(0)" onclick="DeleteMe(this, '."'".$url."'".')" class="btn btn-danger btn-xs btn-delete"><i class="fa fa-trash"></i></a>';
                       
                    return $btn;
                })
                ->addColumn('rownum', function ($row) {
                    return '';
                })
                ->rawColumns(['rownum', 'action'])
                ->make(true);
        }
        $data['pageTitle'] = 'Albums';
        $data['albumsListActive'] = 'active';
        $data['albumsOpening'] = 'menu-is-opening';  
        $data['albumsOpend'] = 'menu-open';
        return view('admin.album.index', $data);
    }
    function store(Request $request){
        echo '<pre>'; print_r($request); die();
    }
    public function edit($id)
    {
        $album = Album::find($id);
        return view('admin.album.edit', compact('album'));
    }
    public function update(Request $request)
    {
        $input = $request->all();
        
        $validated = request()->validate([
            'title' => 'required|max:255',
        ]);
        
        $album = Album::where('id', $input['id'])->update([
            'title'    => $input['title'],
        ]);
        return redirect('/admin/albums');
    }
    public function delete($id)
    {
        if(Album::find($id)->delete()){
            return 'ok';
        }else{
            return 'notok';
        }
    }
}
