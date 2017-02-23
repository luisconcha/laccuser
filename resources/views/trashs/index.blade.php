@extends('layouts.app')

@section('title')
    Trash Users
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <h3>List of users in the trash</h3>

            {!! Form::model(compact($search), ['class' => 'form-search', 'method' => 'GET']) !!}
            <div class="input-group">
                <span class="input-group-btn">
                    {!! Form::submit('Search by:', ['class'=>'btn btn-warning']) !!}
                </span>
                {!! Form::text('search', null, ['placeholder'=> ($search) ? $search : 'id, name','class'=>'form-control']) !!}
                <span class="input-group-btn">
                    <a href="{{ route( 'laccuser.users.index' )  }}" class="btn btn-primary">
Return to the active users</a>
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
                    <th>Creation date</th>
                    <th>Update date</th>
                    <th>Date of removal</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td>{{ $user->deleted_at }}</td>
                        <td>
                            <a href="{{route('laccuser.trashed.users.restore',['id'=>$user->id])}}"
                               class="btn btn-danger btn-outline btn-xs"
                               onclick="event.preventDefault();document.getElementById('restore-user').submit();">
                                <strong>Restore</strong>
                            </a>
                            {!! Form::open(['route' => ['laccuser.trashed.users.restore', 'id' =>$user->id] ,'method'=>'GET', 'id' => 'restore-user', 'style' => 'display:none']) !!}
                            {!! Form::hidden('redirect_to', URL::previous()) !!}
                            {!! Form::hidden('_token', csrf_token()) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center"><span class="label label-warning">No records</span></td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="text-center">{{ $users->links() }}</div>
        </div>
    </div>
@endsection