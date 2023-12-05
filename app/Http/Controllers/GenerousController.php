<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\Models\Generous;
class GenerousController extends Controller
{
    function index(Request $request){
        if ($request->ajax()) {
            $data = Generous::orderBy('id','DESC')->get();
            // echo "<pre>"; print_r($data); die;
            return Datatables::of($data)->addIndexColumn()
            ->addColumn('status', function ($row) {
                $status='';
                // $mroles = $row->getRoleNames();
                if($row->status==1){
                        $status.='<label class="badge badge-success">Active</label>';
                    } else {
                    $status.='<label class="badge badge-danger">InActive</label>';
                }
                return $status;
            })
                ->addColumn('action', function($row){
                    $btn='';
                    $btn .= ' <a class="btn btn-xs btn-primary" href="'.route('generous.edit',$row->id).'"><i class="fas fa-pencil-alt"></i></a>';
                    $url = route("generous.delete", $row->id);
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
        $data['generousListActive'] = 'active';
        $data['generousOpening'] = 'menu-is-opening';  
        $data['generousOpend'] = 'menu-open';
        return view('admin.Generous.index', $data);
    }
    public function edit($id)
    {
        $generous = Generous::find($id);
        return view('admin.Generous.edit', compact('generous'));
    }
    public function update(Request $request)
    {
        $input = $request->all();
        
        $validated = request()->validate([
            'title' => 'required|max:255',
            'status' => 'required',
        ]);
        
        $album = Generous::where('id', $input['id'])->update([
            'title'    => $input['title'],
            'status'    => $input['status'],
        ]);
        return redirect('/admin/generous');
    }
    public function delete($id)
    {
        if(Generous::find($id)->delete()){
            return 'ok';
        }else{
            return 'notok';
        }
    }
}
