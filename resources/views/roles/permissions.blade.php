@extends('layouts.app')

@section('title')
    Permissions
@endsection

@section('content')
    <div class="container">


        <div class="row">

        </div>
    </div>

    <div class="container">
        <div class="row">

            <h3>Permissions for : <span class="label" style="background-color:{{$role->cor}}">{{$role->name}}</span>
            </h3>

            @include('errors._check')

            {!! Form::open(['route'=>['laccuser.role.roles.permissions.update',$role->id],'class'=>'form',
            'method'=>'PUT']) !!}

            {!! Form::hidden('redirect_to', URL::previous()) !!}

            <ul class="list-group">
                @foreach($permissionsGroup as $pg)
                    <li class="list-group-item">
                        <h4 class="list-group-item-heading">
                            <strong>{{ $pg->description }}</strong>
                        </h4>
                        <p class="list-group-item-text">
                        <ul class="list-inline">
                            <?php
                            $permissionsSubGroup = $permissions->filter( function ( $value ) use ( $pg ) {
                                return $value->name == $pg->name;
                            } );
                            ?>

                            @foreach($permissionsSubGroup as $permission)
                                <li>
                                    <div class="checkbox">
                                        <label for="">
                                            <input type="checkbox" name="permissions[]"
                                                   {{$role->permissions->contains('id', $permission->id) ?
                                                   'checked="checked"' : ""}}
                                                   value="{{$permission->id}}"/>
                                            {{$permission->resource_description}}
                                        </label>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        </p>
                    </li>
                @endforeach
            </ul>

            <div class="form-group text-center">
                {!! Form::submit('Save', ['class'=>'btn btn-primary btn-sm']) !!}
                <a href="{{ route('laccuser.role.roles.index') }}" class="btn btn-warning btn-sm"> Return </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>
@endsection
