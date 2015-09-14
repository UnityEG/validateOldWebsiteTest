<?php extract($data); ?>

@extends('layouts.main')

@section('main')
<h2>Edit ( <em style="color:red;">{{{$user->email}}}</em> ) Rules</h2>
{{Form::open(array('route'=>array('rules.user_update', $user->id), 'method'=>'put', 'role'=>'form'))}}

@if(Auth::user()->hasRule('assign_rules'))

<div class="form-group">
    {{Form::select('assign_rules_ids[]', Rule::lists('rule_name', 'id'), $data['assigned_rules_ids'], array('id'=>'assign_rules', 'class'=>'form-control', 'multiple'=>true, 'data-json'=>false, 'data-title'=>'Permissions'))}}
    {{$errors->first('assign_rules_ids', '<div class="alert alert-danger">:message</div>')}}
</div>

@if(!in_array('Owner', $user->getTypes()))
<div class="form-group">
    {{Form::label('reset_rules', 'Reset Rules:')}}
    {{Form::checkbox('reset_rules', true, false)}}
    {{$errors->first('reset_rules', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif
@endif

{{Form::submit('Save', array('class'=>'btn btn-primary'))}}

{{Form::close()}}

<script src="{{{asset('js/dual-list-box.min.js')}}}"></script>

<script>
$("#assign_rules").DualListBox();
</script>
@stop

