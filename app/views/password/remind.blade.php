@extends('layouts.main')

@section('main')
<div class="forms-back">
{{Form::open(array('action'=>'RemindersController@postRemind', 'method'=>'post', 'role'=>'form'))}}
    <div class="form-group">
        {{Form::label('email', 'Email: ')}}
        {{Form::email('email', null, array('class'=>'form-control'))}}
    </div>

    <div class="form-group">
        {{Form::submit('Send Reminder')}}
    </div>
</form>
</div>
@stop