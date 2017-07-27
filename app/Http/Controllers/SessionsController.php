<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth;

class SessionsController extends Controller
{
    //登录页面
    public function create()
    {
        return view('sessions.create');
    }
    
    //登录操作
    public function store(Request $request)
    {
        //验证数据是否规则
        $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' =>'required|min:6'
        ]);
        //提交数据
        $credentials = [
           'email' => $request->email,
            'password' => $request->password
        ] ;
        //提交动作、
        if(Auth::attempt($credentials,$request->has('remember'))){
            session()->flash('success','欢迎回来');
            return redirect()->route('users.show',[Auth::user()]);
        }else{
            session()->flash('danger','用户名或密码错误');
            return redirect()->back();
        }
        
        
    }
    
    //退出方法
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
    
    
    
    
    
}
