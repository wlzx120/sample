<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    //修改操作权限验证
    public function update(User $currentUser,User $user)
    {
        return $currentUser->id === $user->id;
    }
    
    //删除用户策略(当前用户为管理员且删除对象不是自己)
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }
    
    
    
    
    
}
