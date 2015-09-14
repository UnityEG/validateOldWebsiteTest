@extends('layouts.main')
@section('main')
<h2>Log in</h2>
{{Form::open(['url'=>'login', 'role' => 'form'])}}
<div class="form-group">
    {{Form::label('email', 'Email ')}}
	<div class="input-group" style="width:50%;">
    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
    {{Form::email('email', null, array('class'=>'form-control'))}}
	</div>
</div>

<div class="form-group">
    {{Form::label('password', 'Password')}}
	<div class="input-group" style="width:50%;">
    <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
    {{Form::password('password', array('class'=>'form-control'))}}
	</div>
</div>

<div class="form-group">
    {{Form::label('remember_me', 'Remember Me:')}}
    {{Form::input('checkbox', 'remember_me')}}
</div>

<div class="form-group">
    {{Form::submit('Login',array('class'=>'btn btn-primary'))}} 	
</div>
<a href="{{action("RemindersController@getRemind")}}">Forgot your password?</a> <br/><br/>
<a href="{{ URL::to('login/face') }}" class=" btn btn-facebook pull-left"><i class="fa fa-facebook"></i> | Login with Facebook</a>

@stop
