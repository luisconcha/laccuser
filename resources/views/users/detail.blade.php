@extends('layouts.app')

@section('title')
    Detail of user
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <h3>Detail the user: <strong><b>{{ $user->name}}</b></strong></h3>
            <div class="row">
                <div class="col-md-6">
                    <span><b>Email</b></span>
                    <p>{{ $user->email }}</p>

                    <span><b>Registration Date</b></span>
                    <p>{{ $user->created_at }}</p>

                    <span><b>CPF</b></span>
                    <p>{{ $user->num_cpf }}</p>

                    <span><b>RG:</b></span>
                    <p>{{ $user->num_rg }}</p>

                    <span><b>Civil Status:</b></span>
                    <p>{{ $user->civil_status }}</p>
                </div>
                <div class="col-md-6">
                    <span><b>Address:</b></span>
                    <p>{{ ($user->address) ? $user->address->address : '------------' }}</p>

                    <span><b>District:</b></span>
                    <p> {{ ($user->address) ? $user->address->district: '-----------' }}</p>

                    <span><b>CEP:</b></span>
                    <p> {{ ($user->address) ? $user->address->cep : '-------------' }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <a href="{{ route('laccuser.users.index') }}" class="btn btn-primary">Return list of users</a>

            <a href="{{route('laccuser.users.edit',['id'=>$user->id])}}" class="btn btn-warning">Edit the of user</a>
        </div>
    </div>

@endsection