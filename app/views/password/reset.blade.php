@extends('layouts.main')

@section('main')
<div class="forms-back">
    {{Form::open(array('action'=>'RemindersController@postReset', 'method'=>'post', 'role'=>'form'))}}
    <input type="hidden" name="token" value="{{ $token }}">
    
    <div class="form-group">
    {{Form::label('email', 'Email: ')}}
    {{Form::email('email', null, array('class'=>'form-control'))}}
    </div>
    
    <div class="form-group">
        {{Form::label('password', 'Password: ')}}
        {{Form::password('password', array('class'=>'form-control'))}}<small> at least 6 characters!.</small>
        {{$errors->first('password')}}
    </div>
    
    <div class="form-group">
        {{Form::label('password_confirmation', 'Password confirmation: ')}}
        {{Form::password('password_confirmation', array('class'=>'form-control'))}}
        {{$errors->first('password_confirmation')}}
    </div>
    <div class="form-group">
        {{Form::submit('Reset Password')}}
    </div>
</form>
</div>
@stop