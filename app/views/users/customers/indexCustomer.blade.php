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
<p><a class="btn btn-success" href="{{route('customer.create')}}"><span class="glyphicon glyphicon-user"></span> Create Customer</a></p>
<h2>Customers</h2>

<div class="form-group">
    <input type="search" name="searchKeys" class="form-control input-lg" placeholder="Search for Customer">
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
    @foreach($group as $item)
    <?php
    $item_info_from_user_table = $item->user()->first();
    $user_type                 = '';
    if ( $item_info_from_user_table != null ) {
        $user_type = $item_info_from_user_table->user_type;
    }
    ?>
    <tr>
        <td><a href="{{route('customer.show', $item->id)}}">{{{ucfirst($item->first_name.' '.$item->last_name)}}}</a></td>
        <td>{{{$user_type}}}</td>
        <td>
		<a href="{{route('customer.edit', $item->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
		
            {{Form::open(['url'=>'customer/'.$item->id, 'method'=>'delete'])}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>'return confirm("Are you sure you want to delete '.$item->name.' ?")'))}}
            {{Form::close()}}
        </td>
    </tr>
    @endforeach
	</tbody>
</table>
{{$group->links()}}
</div>
<script src="{{asset('js/searchUsers.js')}}"></script>
<script>

    jQuery("document").ready(function () {
        searchProcess.url = "{{route('customer.index')}}";
        
        searchProcess.searchResult();
    });//jQuery("document").ready(function()

</script>

@stop
