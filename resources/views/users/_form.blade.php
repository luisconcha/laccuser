{!! Form::hidden('redirect_to', URL::previous()) !!}

<div class="form-group {{ $errors->first('name')? ' has-error':'' }}">
   {!! Form::label('name','Name', ['class' => 'control-label']) !!}
   {!! Form::text('name', null, ['placeholder'=>'Enter user name','class'=>'form-control', 'id'=>'name']) !!}
</div>

<div class="form-group {{ $errors->first('email')? ' has-error':'' }}">
   {!! Form::label('Email','Email', ['class' => 'control-label']) !!}
   {!! Form::text('email', null, ['placeholder'=>'Enter your email','class'=>'form-control', 'id'=>'email']) !!}
</div>

<div class="form-group {{ $errors->first('num_cpf')? ' has-error':'' }}">
   {!! Form::label('CPF','CPF', ['class' => 'control-label']) !!}
   {!! Form::text('num_cpf', null, ['placeholder'=>'Enter your cpf','class'=>'form-control', 'id'=>'num_cpf']) !!}
</div>

<div class="form-group {{ $errors->first('num_rg')? ' has-error':'' }}">
   {!! Form::label('RG','RG', ['class' => 'control-label']) !!}
   {!! Form::text('num_rg', null, ['placeholder'=>'Enter your rg','class'=>'form-control', 'id'=>'email']) !!}
</div>

<div class="form-group {{ $errors->first('civil_status')? ' has-error':'' }}">
    {!! Form::label('Civil Status','Civil Status', ['class' => 'control-label']) !!}
    {!! Form::select('civil_status', $civilStatus,null, ['class'=>'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Avatar','Avatar', ['class' => 'control-label']) !!}
    {!! Form::file('avatar',null, ['class'=>'form-control']) !!}
</div>

<div class="form-group {{ $errors->first('password')? ' has-error':'' }}">
    {!! Form::label('Password','Password', ['class' => 'control-label']) !!}
    {!! Form::password('password', ['placeholder'=>'Enter your password','class'=>'form-control', 'id'=>'password']) !!}
</div>

<hr>
<h3>Address</h3>
<div class="form-group {{ $errors->first('city_id')? ' has-error':'' }}">
    {!! Form::label('City','City', ['class' => 'control-label']) !!}
    {!! Form::select('city_id', $cities,null, ['class'=>'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('District','District', ['class' => 'control-label']) !!}
    {!! Form::text('district', null, ['placeholder'=>'Enter your district','class'=>'form-control', 'id'=>'district']) !!}
</div>

<div class="form-group {{ $errors->first('cep')? ' has-error':'' }}">
    {!! Form::label('CEP','CEP', ['class' => 'control-label']) !!}
    {!! Form::text('cep', null, ['placeholder'=>'Enter your cep','class'=>'form-control', 'id'=>'cep']) !!}
</div>

<div class="form-group {{ $errors->first('address')? ' has-error':'' }}">
    {!! Form::label('Address','Address', ['class' => 'control-label']) !!}
    {!! Form::text('address', null, ['placeholder'=>'Enter your address','class'=>'form-control', 'id'=>'address']) !!}
</div>

<div class="form-group">
    {!! Form::label('Type address','Type address', ['class' => 'control-label']) !!}
    {!! Form::select('type_address', $typeAddress,null, ['class'=>'form-control']) !!}
</div>
