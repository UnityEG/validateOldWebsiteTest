@extends('layouts.main')
@section('main')
<div class="forms-back">
<table class="table table-hover list-table">
<thead>
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td >{{{$owner->first_name. ' '.$owner->last_name}}}</td>
        <td class="text-capitalize">owner</td>
        <td>
            <a href='{{url('owner/'.$owner->id.'/edit')}}' class="btn btn-primary"><span class="glyphicon glyphicon-edit"></a>
        </td>
        
    </tr>
</tbody>
</table>
</div>
@stop
