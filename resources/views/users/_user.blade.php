<li >
    <img src="{{ $user->gravatar() }}" alt="{{ $user->name }}" class="gravatar" />
    <a href="{{ route('users.show',$user->id) }}" >{{ $user->name }}</a>
    
    @can('destroy',$user)
    <form method="POST" action="{{ route('users.destroy',$user->id) }}">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
        <button type="submit" class="btn btn-sm btn-danger delete-btn">删除</button>
    </form>
    @endcan
   
    
</li>

