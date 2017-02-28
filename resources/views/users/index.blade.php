@extends('layouts.app')

@section('title')
    List of Users
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <h3>List of users</h3>

            {!! Form::model(compact($search), ['class' => 'form-search', 'method' => 'GET']) !!}
            <div class="input-group">
                <span class="input-group-btn">
                    {!! Form::submit('Search by:', ['class'=>'btn btn-warning']) !!}
                </span>
                {!! Form::text('search', null, ['placeholder'=> ($search) ? $search : 'id, or name','class'=>'form-control']) !!}
                <span class="input-group-btn">
                    <a href="{{ route( 'laccuser.users.create' )  }}" class="btn btn-primary">New user</a>
            </span>
            </div>
            {!! Form::close() !!}
        </div>

        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <td>Actions</td>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{!! $user->roles->implode('name',' | ') !!}</td>
                        <td>
                            <a href="{{route('laccuser.users.edit',['id'=>$user->id])}}"
                               class="btn btn-warning btn-outline btn-xs">
                                <strong>Edit</strong>
                            </a>
                            @if ($user->id == \Auth::user()->id)
                                <a href="#"
                                   class="btn btn-default btn-outline btn-xs disabled">
                                    <strong>Can not Delete user</strong>
                                </a>
                            @else
                                <a href="{{route('laccuser.users.destroy',['id'=>$user->id])}}"
                                   class="btn btn-danger btn-outline btn-xs">
                                    <strong>Delete</strong>
                                </a>
                            @endif
                            <a href="{{route('laccuser.users.detail',['id'=>$user->id])}}"
                               class="btn btn-warning btn-outline btn-xs">
                                <strong>Detail</strong>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="4"><span class="label label-warning">There are no registered users</span></td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <div class="text-center">{{ $users->links() }}</div>
        </div>
    </div>
@endsection
