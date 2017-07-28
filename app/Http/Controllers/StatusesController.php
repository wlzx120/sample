<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
    public function __destruct() 
    {
        $this->middleware('auth',[
            'only' => [ 'store','destroy']
        ]);
    }
    
    //添加微博动作
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);
        Auth::user()->statuses()->create([
            'content' => $request->content
        ]);
        return redirect()->back();
    }
    
    //删除微博动作
    public function destroy($id)
    {
        $status = Status::findOrFail($id);
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('sucess','删除成功');
        return redirect()->back();
    }
    
}
