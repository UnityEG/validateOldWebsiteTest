@extends('layouts.main')
@section('main
<div class="forms-back">
{{Form::open(array('url'=>route('vouchers-admin.update', $user->id), 'method'=>'put', 'role'=>'form'))}}
<p><strong>{{{$user->user_type}}}</strong></p>
<div class="form-group">
    {{Form::label('email', 'Email: ')}}
    {{Form::email('email', $user->email, array('class'=>'form-control'))}}
    {{$errors->first('email')}}
</div>

<div class="form-group">
    {{Form::label('password', 'Password')}}
    {{Form::password('password', array('class'=>'form-control'))}}
    {{$errors->first('password')}}
</div>

<div class="form-group">
    {{Form::label('password_confirmation', 'Password confirmation')}}
    {{Form::password('password_confirmation', array('class'=>'form-control'))}}
    {{$errors->first('password_confirmation')}}
</div>

<div class='form-group'>
    {{Form::label('active', 'Active User')}}
    <?php $checked = (isset( $user->active ) && $user->active == true) ? TRUE : false; ?>
    {{Form::checkbox('active', 'on', $checked);}}
    {{$errors->first('active')}}
</div>

<div class="form-group">
    {{Form::submit('Update user', array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}
</div>
@stop

