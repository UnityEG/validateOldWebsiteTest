@extends('layouts.users')

@section('main')
@if ($errors->any())
    <ul>
        {{ implode('', $errors->all('<li class="error">:message</li>')) }}
    </ul>
@endif
<div class="forms-back">
<h1>Create User</h1>
{{ Form::open(array('route' => 'users.store')) }}
    <ul>
        <li>
        	{{ Form::label('name', 'Name:') }}
            {{ Form::text('name') }}
        </li>
        <li>
            {{ Form::label('username', 'Username:') }}
            {{ Form::text('username') }}
        </li>
        <li>
            {{ Form::label('password', 'Password:') }}
            {{ Form::password('password') }}
        </li>
        <li>
            {{ Form::label('email', 'Email:') }}
            {{ Form::text('email') }}
        </li>
        <li>
            {{ Form::label('phone', 'Phone:') }}
            {{ Form::text('phone') }}
        </li>
        <li>
            {{ Form::submit('Submit', array('class' => 'btn')) }}
        </li>
    </ul>
{{ Form::close() }}
</div>
@stop