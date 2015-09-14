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
<p><a class="btn btn-success" href="{{route('supplier.create')}}"><span class="glyphicon glyphicon-user"></span> Create Supplier</a></p>
<h2>Suppliers</h2>

<div class="form-group">
    <input type="search" name="searchKeys" class="form-control input-lg" placeholder="Search for Supplier">
    <div id="searchResult"></div>
</div>

<table class="table table-hover list-table">
<thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Merchants</th>
		<th>Actions</th>
    </tr>
</thead>    
    <?php 
    foreach($group as $item):
        $item_info_user = $item->user()->first();
    
        $item_info_merchant = $item->merchant;
        
        $merchants_list = "<ul>";
        foreach ( $item_info_merchant as $merchant) {
            $merchants_list .= "<li>";
            $merchants_list .= link_to_route('merchant.show', $merchant->first_name, array($merchant->id));
            $merchants_list .= "</li>";
        }
        $merchants_list .= "</ul>";
    ?>
	<tbody>
    <tr>
        <td>{{link_to_route('supplier.show', ucfirst($item->first_name).' '.$item->last_name, array($item->id))}}</td>
        
        <td>{{$item_info_user->user_type}}</td>
        
        <td>{{$merchants_list}}</td>
        		
		<td>
		<a href="{{route('supplier.edit', $item->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
		
            {{Form::open(array('route'=>array('supplier.destroy', $item->id), 'method'=>'delete', 'role'=>'form'))}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>"return confirm('are you sure you watn to delete ".$item->first_name." ".$item->last_name."?')"))}}
            {{Form::close()}}
        </td>
    </tr>
    
    <?php endforeach; ?>
	</tbody>
</table>
{{$group->links()}}
</div>
<script src="{{asset('js/searchUsers.js')}}"></script>
<script>

jQuery("document").ready(function(){
    searchProcess.url = "{{route('supplier.index')}}";
    searchProcess.searchResult();
});//jQuery("document").ready(function()

</script>

@stop