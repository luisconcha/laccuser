@extends('layouts.app')

@section('title')
    List of Roles
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <h3>List of roles</h3>

            {!! Form::model(compact($search), ['class' => 'form-search', 'method' => 'GET']) !!}
            <div class="input-group">
                <span class="input-group-btn">
                    {!! Form::submit('Search by:', ['class'=>'btn btn-warning']) !!}
                </span>
                {!! Form::text('search', null, ['placeholder'=> ($search) ? $search : 'name or description',
                'class'=>'form-control']) !!}
                <span class="input-group-btn">
                    <a href="{{ route( 'laccuser.role.roles.create' )  }}" class="btn btn-primary">New Role</a>
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
                    <td>Description</td>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->description }}</td>
                        <td>
                            <a href="{{route('laccuser.role.roles.edit',['id'=>$role->id])}}"
                               class="btn btn-warning btn-outline btn-xs">
                                <strong>Edit</strong>
                            </a>
                            @if( $role->name == 'Admin' )
                                <a href="#"
                                   class="btn btn-default btn-outline btn-xs disabled">
                                    <strong>You can not delete the default system role</strong>
                                </a>
                            @else
                                <a href="{{route('laccuser.role.roles.destroy',['id'=>$role->id])}}"
                                   class="btn btn-danger btn-outline btn-xs">
                                    <strong>Delete</strong>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-center">{{ $roles->links() }}</div>

        </div>
    </div>
@endsection
