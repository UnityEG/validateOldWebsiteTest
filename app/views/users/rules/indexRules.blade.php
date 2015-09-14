<?php extract( $data ); ?>
@extends('layouts.main')

@section('main')

<style>
    #searchResult{
        display: none;
        background-color: #CFFCC7;
        border: 1px solid #aaaaaa;
        position: absolute;
        top: 40px;
        width: 100%;
        z-index: 1000;
    }
    #searchResult div{
        margin-top: 10px;
        font-family: Verdana,Arial,sans-serif;
        font-size: 1.1em;
        margin: 0;
        padding: 3px 1em 3px .4em;
        cursor: pointer;
        min-height: 0;
        line-height: 1.7;
    }
</style>

<div class="forms-back">
    @if(Auth::user()->user_type == 'developer')
    <p><a class="btn btn-success" href="{{route('rules.create')}}"><span class="glyphicon glyphicon-user"></span> Create New Rule</a></p>
    @endif

    <h2>Rules of all users</h2>

    <div class="form-group">
        <input type="search" name="searchKeys" class="form-control input-lg" placeholder="Search for User">
        <div id="searchResult"></div>
    </div>

    <table class="table table-hover list-table">
        <thead>
            <tr>
                <th>Rule Name</th>
                @if(g::isDeveloper() || $delete_rule==true)
                <th>Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($group as $item)

            <tr>
                <td>
                    {{link_to_route('rules.show', $item->rule_name, array($item->id))}}
                </td>


                @if(g::isDeveloper() && $delete_rule==false)
                <td> 
                    <a href="{{route('rules.edit', $item->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
                </td>
                @elseif(!g::isDeveloper() && $delete_rule==true)
                <td>
                    {{Form::open(array('route'=>array('rules.destroy', $item->id), 'method'=>'delete', 'role'=>'form'))}}
                    {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>"return confirm('are you sure you want to delete $item->rule_name ?')"))}}
                    {{Form::close()}}
                </td>
                @elseif(g::isDeveloper() && $delete_rule==true)
                <td> 
                    <a href="{{route('rules.edit', $item->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
                    {{Form::open(array('route'=>array('rules.destroy', $item->id), 'method'=>'delete', 'role'=>'form'))}}
                    {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>"return confirm('are you sure you want to delete $item->rule_name ?')"))}}
                    {{Form::close()}}
                </td>
                @endif

            </tr>

            @endforeach
        </tbody>
    </table>

    {{$group->links()}}
</div>
<script src="{{asset('js/searchUsers.js')}}"></script>
<script>
"use strict";
jQuery("document").ready(function ($) {
    searchProcess.url = "{{route('user.index')}}";
    searchProcess.searchResult();
});//jQuery("document").ready(function($)

</script>

@stop