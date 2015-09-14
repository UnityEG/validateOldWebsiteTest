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
<p><a class="btn btn-success" href="{{route('admin.create')}}"><span class="glyphicon glyphicon-user"></span> Create Admin</a></p>
<h2>Admins</h2>

<div class="form-group">
    <input type="search" name="searchKeys" class="form-control input-lg" placeholder="Search for Admin">
    <div id="searchResult"></div>
</div>

<table class="table table-hover list-table">
	<thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
		<th>Actions</th>
    </tr>
	</thead>
	<tbody>
    @foreach($admins as $admin)
    <?php
    $user_info    = $admin->user()->first();
    ?>
    <tr>
        <td><a href="{{route('admin.show', $admin->id)}}">{{$admin->first_name.' '.$admin->last_name}}</a></td>
        <td>{{{$user_info->user_type}}}</td>

		<td>
		<a href="{{route('admin.edit', $admin->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
		
            {{Form::open(['url'=>'admin/'.$admin->id, 'method'=>'delete'])}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return confirm("Are you sure you want to delete '.$admin->first_name.' ?")'))}}
            {{Form::close()}}
        </td>
    </tr>
    @endforeach
	</tbody>
</table>
{{$admins->links()}}
</div>
<script src="{{asset('js/searchUsers.js')}}"></script>
<script>

    jQuery("document").ready(function () {
        searchProcess.url = "{{route('admin.index')}}";
        searchProcess.searchResult();

    });//jQuery("document").ready(function()

</script>

@stop