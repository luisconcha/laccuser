{!! Form::hidden('redirect_to', URL::previous()) !!}

<div class="form-group {{ $errors->first('name')? ' has-error':'' }}">
   {!! Form::label('name','Name', ['class' => 'control-label']) !!}
   {!! Form::text('name', null, ['placeholder'=>'Enter user name','class'=>'form-control', 'id'=>'name']) !!}
</div>

<div class="form-group {{ $errors->first('description')? ' has-error':'' }}">
    {!! Form::label('Description','Description', ['class' => 'control-label']) !!}
    {!! Form::text('description', null, ['placeholder'=>'Enter your description','class'=>'form-control',
    'id'=>'description'])
     !!}
</div>
