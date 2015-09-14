@extends('layouts.main')
@section('main')

<div class="forms-back">
<p><em>Edit {{ucfirst($owner->first_name)}}'s profile</em></p>
{{Form::model($user_info, ['route'=>array('owner.update', $owner->id), 'method'=>'put', 'role'=>'form'])}}

<div class="form-group">
    {{Form::label('email', 'E-mail: ')}}
    {{Form::email('email', null, array('class'=>'form-control'))}}
    {{$errors->first('email', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('password', 'Password')}}
    {{Form::password('password', array('class'=>'form-control'))}}
    {{$errors->first('password', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('password_confirmation', 'Password Confirmation: ')}}
    {{Form::password('password_confirmation', array('class'=>'form-control'))}}
    {{$errors->first('password_confirmation', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('first_name', 'First Name:')}}
    {{Form::text('first_name', $owner->first_name, array('class'=>'form-control'))}}
    {{$errors->first('first_name', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('last_name', 'Last Name:')}}
    {{Form::text('last_name', $owner->last_name, array('class'=>'form-control'))}}
    {{$errors->first('last_name', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::select('assign_rules_ids[]', Rule::lists('rule_name', 'id'), $data['active_owner_rules'], array('id'=>'assign_rules', 'class'=>'form-control', 'multiple'=>true, 'data-json'=>false, 'data-title'=>'Permissions'))}}
    {{$errors->first('assign_rules_ids', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::submit('Save', array('class'=>'btn btn-primary'))}}
    {{link_to('owner/'.$owner->id, 'Cancel', array('class'=>'btn btn-danger'))}}
</div>
</div>

<script src="{{{asset('/js/dual-list-box.min.js')}}}"></script>

<script>
$("#assign_rules").DualListBox();
</script>

@stop