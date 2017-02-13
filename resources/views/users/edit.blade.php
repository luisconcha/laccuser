@extends('layouts.app')

@section('title')
    Edit User
@endsection

@section('content')
    <div class="container">
        <h1>Edit User: <strong>{{$user->name}}</strong></h1>

        @include('errors._check')

        {!! Form::model($user,['route'=>['users.update','id'=>$user->id],'method'=>'put']) !!}

        @include('laccuser::users._form')

        <div class="form-group text-center">
            {!! Form::submit('Edit', ['class'=>'btn btn-primary btn-sm']) !!}
            <a href="{{ route('users.index') }}" class="btn btn-warning btn-sm"> Return </a>
        </div>

        {!! Form::close() !!}
    </div>
@endsection