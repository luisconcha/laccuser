@extends('layouts.app')

@section('title')
    Edit Role
@endsection

@section('content')
    <div class="container">
        <h1>Edit Role: <strong>{{$role->name}}</strong></h1>

        @include('errors._check')

        {!! Form::model($role,['route'=>['laccuser.role.roles.update','id'=>$role->id],'method'=>'put']) !!}

        @include('laccuser::roles._form')

        <div class="form-group text-center">
            {!! Form::submit('Edit', ['class'=>'btn btn-primary btn-sm']) !!}
            <a href="{{ route('laccuser.role.roles.index') }}" class="btn btn-warning btn-sm"> Return </a>
        </div>

        {!! Form::close() !!}
    </div>
@endsection