@extends('layouts.app')

@section('title')
    Advanced user search
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <h3>Advanced user search</h3>

            {!! Form::model(compact($arrSearch), ['class' => 'form-search', 'method' => 'GET']) !!}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('name','Name', ['class' => 'control-label']) !!}
                        {!! Form::text('name', null, ['placeholder'=> (isset($arrSearch['name'] ))? $arrSearch['name'] : 'Enter user name','class'=>'form-control', 'id'=>'name']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('CPF','CPF', ['class' => 'control-label']) !!}
                        {!! Form::text('num_cpf', null, ['placeholder'=> (isset($arrSearch['num_cpf'] )) ? $arrSearch['num_cpf'] : 'Enter cpf','class'=>'form-control', 'id'=>'num_cpf']) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('RG','RG', ['class' => 'control-label']) !!}
                        {!! Form::text('num_rg', null, ['placeholder'=> (isset($arrSearch['num_rg'] )) ? $arrSearch['num_rg'] : 'Enter rg','class'=>'form-control', 'id'=>'num_rg']) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('Email','Email', ['class' => 'control-label']) !!}
                        {!! Form::text('email', null, ['placeholder'=> (isset($arrSearch['email'] )) ? $arrSearch['email'] : 'Enter your email','class'=>'form-control', 'id'=>'email']) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('District','District', ['class' => 'control-label']) !!}
                        {!! Form::text('district', null, ['placeholder'=> (isset($arrSearch['district'] )) ? $arrSearch['district'] :  'Enter district','class'=>'form-control', 'id'=>'district']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('Address','Address', ['class' => 'control-label']) !!}
                        {!! Form::text('address', null, ['placeholder'=> (isset($arrSearch['address'] ))? $arrSearch['address'] :  'Enter address','class'=>'form-control', 'id'=>'address']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('CEP','CEP', ['class' => 'control-label']) !!}
                        {!! Form::text('cep', null, ['placeholder'=> (isset($arrSearch['cep'] )) ? $arrSearch['cep'] :  'Enter cep','class'=>'form-control', 'id'=>'cep']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group text-center">
                {!! Form::submit('Search...', ['class'=>'btn btn-primary btn-sm']) !!}
                <a href="{{ route('users.index') }}" class="btn btn-warning btn-sm"> Return </a>
            </div>
            {!! Form::close() !!}
        </div>
        <hr>
        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <td>CPF</td>
                    <td>RG</td>
                    <td>District</td>
                    <th>Address</th>
                    <th>Cep</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->num_cpf }}</td>
                        <td>{{ $user->num_rg }}</td>
                        <td>{{ $user->address->district }}</td>
                        <td>{{ $user->address->address }}</td>
                        <td>{{ $user->address->cep }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">
                            <span class="label label-warning">Not found</span>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="text-center">{{ $users->links() }}</div>

        </div>
    </div>
@endsection
