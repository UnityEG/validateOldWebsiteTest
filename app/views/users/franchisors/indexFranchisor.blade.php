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
<p><a class="btn btn-success" href="{{route('franchisor.create')}}"><span class="glyphicon glyphicon-user"></span> Create Franchisor</a></p>

<h2>Franchisors</h2>

<div class="form-group">
    <input type="search" name="searchKeys" class="form-control input-lg" placeholder="Search for Franchisor" />
    <div id="searchResult"></div>
</div>

<table class="table table-hover list-table">
	<thead>
    <tr>
        <th>Franchisor Name</th>
        <th>Contact Name</th>
        <th>Phone</th>
        <th>Type</th>
        <th>Merchants</th>
		<th>Actions</th>
    </tr>
	</thead>
	<tbody>
    <?php
    foreach ( $group as $item ) :
        $item_info_from_user = $item->user()->first();
    
        $item_info_from_merchant = $item->merchant;
        
        $merchant_list = "<ul>";
        foreach ( $item_info_from_merchant as $merchant) {
            $merchant_list .= "<li>";
            $merchant_list .= link_to_route('merchant.show', ucfirst($merchant->first_name).' '.$merchant->last_name, array($merchant->id));
            $merchant_list .= "</li>";
        }
        $merchant_list .= "</ul>";
    ?>
        <tr>
            <td>
                {{link_to_route('franchisor.show', $item->franchisor_name, array($item->id))}}
            </td>
            
            <td>
                {{{$item->contact}}}
            </td>
            
            <td>
                {{{$item->phone}}}
            </td>

            <td>
                {{$item_info_from_user->user_type}}
            </td>

            <td>{{$merchant_list}}</td>
			
		<td>
		<a href="{{route('franchisor.edit', $item->id)}}" class="btn btn-primary"><span class="glyphicon glyphicon-edit"></span></a>
		
            {{Form::open(array('route'=>array('franchisor.destroy', $item->id), 'method'=>'delete', 'role'=>'form'))}}
            {{Form::button('<span class="glyphicon glyphicon-trash"></span>',array ('type'=>'submit','class'=>'btn btn-danger', 'onclick'=>"return confirm('are you sure you want to delete $item->first_name $item->last_name?')"))}}
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

    jQuery("document").ready(function () {
        searchProcess.url = "{{route('franchisor.index')}}";

        searchProcess.searchResult();

    });//jQuery("document").ready(function()

</script>

@stop