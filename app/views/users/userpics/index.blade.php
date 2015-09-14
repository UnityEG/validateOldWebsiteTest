@extends('layouts.main')

@section('main')

{{Form::open(array('route'=>array('userpics.store'), 'files'=>true, 'method'=>'post', 'role'=>'form'))}}

<div class="form-group">
<table class="table table-bordered table-hover table-striped">
    <tr>
        <th class="text-center">Default Gift Voucher Image</th>
<!--        <th class="text-center">Active</th>-->
        <th class="text-center">Delete</th>
    </tr>
    
    <?php 
        $default_gift_voucher_images = UserPic::where('type', '=', 'default_gift_voucher_image')->get();
        
        foreach ( $default_gift_voucher_images as $image):
    ?>
    <tr>
        <td class="text-center">
            <img src="{{asset(UserPicController::$defaultImagesPath.'/'.$image->pic.'.'.$image->extension)}}" style="width:50px;height: 50px;">
        </td>
        
<!--        <td class="text-center">
            {{Form::radio('active_default_voucher_image', $image->id, $image->active_pic)}}
        </td>-->
        
        <td class="text-center">
                {{Form::checkbox('delete_default_voucher_image[]', $image->id, false)}}
                {{$errors->first('delete_default_voucher_image', '<div class="alert alert-danger">:message</div>')}}
            </td>
        
    </tr>
    <?php
        endforeach; 
    ?>
    
    <tr>
        <td colspan="2">
        {{Form::file('default_gift_voucher_image')}}
        </td>
    </tr>
</table>
</div>


<div class="form-group">
    {{Form::submit('Save', array('class'=>'btn btn-primary'))}}
</div>
{{Form::close()}}

@stop