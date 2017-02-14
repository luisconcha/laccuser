@extends('layouts.app')

@section('title')
    New User
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <h3>New User</h3>

            @include('errors._check')

            {!! Form::open(['route'=>'laccuser.users.store']) !!}

            @include('laccuser::users._form')

            <div class="form-group text-center">
                {!! Form::submit('Save', ['class'=>'btn btn-primary btn-sm']) !!}
                <a href="{{ route('laccuser.users.index') }}" class="btn btn-warning btn-sm"> Return </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>

@endsection