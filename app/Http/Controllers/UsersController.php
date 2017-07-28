<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Mail;
use Session;
class UsersController extends Controller
{
    //过滤未登录用户操作
    public function __construct()
    {
        $this->middleware('auth', [
            'only' => ['edit', 'update', 'destroy']
        ]);

        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    
    public function index()
    {
        $users = User::paginate(5);
        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        return view('users.create');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
         //Session::put('_token','');
        //var_dump(Session::all());exit;
        return view('users.show', compact('user'));
    }
    
    //用户注册
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        
        //自动登录
        //Auth::login($user);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        //return redirect()->route('users.show', [$user]);
        return redirect('/');

    }
    
    //编辑用户资料页
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }
    
    //修改用户信息
    public function update($id,Request $request)
    {
        $this->validate($request, [
             'name' => 'required|max:50',
             'password' => 'confirmed|min:6'
        ]);  
        $user = User::findOrFail($id);
        $this->authorize('update',$user);
        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }
        session()->flash('success', '修改成功');
        return redirect()->route('users.show', $id);
        
        
    }
    
    //删除方法
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
    
    //邮件发送
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'aufree@yousails.com';
        $name = 'Aufree';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";
        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }
    
    //邮件激活
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
    
    
}
