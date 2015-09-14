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
<p><a class="btn btn-success" href="{{route('merchant_manager.create')}}"><span class="glyphicon glyphicon-user"></span> Create Merchant Manager</a></p>

<h2>Merchant Managers</h2>

<div class="form-group">
    <input type="search" name="searchKeys" class="form-control input-lg" placeholder="Search for Merchant Manager">
    <div id="searchResult"></div>
</div>

<table class="table table-hover list-table">
	<thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Merchant</th>
		<th>Actions</th>
    </tr>
	</thead>
	<tbody>
    <?php 
        foreach ( $group as $item) :
            $item_info_from_user_table = $item->user()->first();
            
            if ( $item_info_from_user_table == null ) {
                continue;
            }
    
        $item_info_merchant = $item->merchant()->first();
    ?>
    <tr>
        <td>
            {{link_to_route('merchant_manager.show', ucfirst($item->first_name).' '.$item->last_name, array($item->id))}}
        </td>
        
        <td>{{$item_info_from_user_table->user_type}}</td>
        
        <td>
            @unless(empty($item_info_merchant) || null == $item_info_merchant)
            <a href="{{route('merchant.show', $item_info_merchant->id)}}">{{ucfirst($item_info_merchant->first_name)." ".$item_info_merchant->last_name}}</a>
            @endunless
        </td>
        
		<td>
		<a href="{{route('merchant_manager.edit', $item->id)}}" class="btn btn-primary "><span class="glyphicon glyphicon-edit"></span></a>
		
            {{Form::open(array('route'=>array('merchant_manager.destroy', $item->id), 'role'=>'form', 'method'=>'delete'))}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>"return confirm('are you sure you want to delete $item->first_name')"))}}
            {{Form::close()}}
        </td>
    </tr>
    
    <?php endforeach;?>
    
</tbody>  
</table>
{{$group->links()}}
</div>
<script src="{{asset('js/searchUsers.js')}}"></script>
<script>
    jQuery("document").ready(function(){
        searchProcess.url = "{{route('merchant_manager.index')}}";
        searchProcess.searchResult();
    });//jQuery("document").ready(function()
</script>
@stop
