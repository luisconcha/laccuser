@extends('layouts.app')

@section('title')
    Update password
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <h3>Hello! <strong>{{$user->name}}</strong>, please update your password</h3>

            @include('errors._check')

            {!! Form::open(['route'=>'laccuser.user_password.update',  'method' => 'PUT' ]) !!}

            <div class="form-group {{ $errors->first('password')? ' has-error':'' }}">
                {!! Form::label('Password','Password', ['class' => 'control-label']) !!}
                {!! Form::password('password', ['placeholder'=>'Enter your password','class'=>'form-control', 'id'=>'password']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('password_confirmation','Confirm the password', ['class' => 'control-label']) !!}
                {!! Form::password('password_confirmation', ['placeholder'=>'Confirm the password','class'=>'form-control', 'id'=>'password']) !!}
            </div>

            <div class="form-group text-center">
                {!! Form::submit('Update password', ['class'=>'btn btn-primary btn-sm']) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection


