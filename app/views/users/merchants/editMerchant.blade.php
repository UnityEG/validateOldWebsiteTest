@extends('layouts.main')
@section('main')
<style>
	input[type=text]:not(.filter),input[type=email],
	input[type=password],input[type=date],
	input[type=search],input[type=tel],input[type=phone]
	{
		width:100%;
	}
	.ui-autocomplete-input{
		width:90% !important;
		height: 40px !important;
	}
	.ui-button-icon-only{
		width:9% !important;
	}
	.custom-combobox a{
		height:40px !important;
	}
	.custom-form-group{
		margin-bottom:4px !important;
	}
	.form-group{
	margin-bottom: -5px;
	}
</style>
<div class="forms-back">
{{Form::model($item, array('url'=>'merchant/'.$item->id, 'method'=>'put', 'files'=>true, 'role'=>'form'))}}
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
<div class="form-group">
    {{Form::label('email', 'Email: ')}}
    {{Form::email('email', $item_information_from_user_table->email, array('class'=>'form-control'))}}
    {{$errors->first('email', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('password', 'Password')}}
    {{Form::password('password', array('class'=>'form-control'))}}
    {{$errors->first('password', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('password_confirmation', 'Password confirmation')}}
    {{Form::password('password_confirmation', array('class'=>'form-control'))}}
    {{$errors->first('password_confirmation', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('first_name', 'First Name: ')}}
    {{Form::text('first_name', null, array('class'=>'form-control'))}}
    {{$errors->first('first_name', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('last_name', 'Last Name: ')}}
    {{Form::text('last_name', null, array('class'=>'form-control'))}}
    {{$errors->first('last_name')}}
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">
<div class="form-group">
    {{Form::label('business_name', 'Bussiness Name: ')}}
    {{Form::text('business_name', null, array('class'=>'form-control'))}}
    {{$errors->first('buisness_name', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('trading_name', 'Trading Name: ')}}
    {{Form::text('trading_name', null, array('class'=>'form-control'))}}
    {{$errors->first('trading_name', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group custom-form-group">
    <?php 
    $options = array('0'=>'');
    foreach(Industry::lists('industry', 'id') as $key=>$val){
        $options[$key] = $val;
    }
    ?>
    {{Form::label('industry_id', 'Industry: ')}}
    {{Form::select('industry_id', $options, null, array('class'=>'form-control', 'placeholder'=>'Select Industry'))}}
    {{$errors->first('industry_id', '<div class="alert alert-danger">:message</div>')}}
</div>

@if(!is_null(Auth::user()) && (Auth::user()->hasRule('franchisor_assign')))
<div class="form-group custom-form-group">
    <?php 
    $options = array('0'=>'');
    foreach ( Franchisor::lists( 'franchisor_name', 'id') as $key => $value ) {
        $options[$key] = $value;
    }
    ?>
    {{Form::label('franchisor_id', 'Franchisor: ')}}
    {{Form::select('franchisor_id', $options, null, array('class'=>'form-control', 'placeholder'=>'Select Franchisor'))}}
    {{$errors->first('franchisor_id', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif
</div>
</div>
@if(!is_null(Auth::user()) &&(Auth::user()->hasRule('supplier_assign')))
<div class="form-group">
    {{Form::select('supplier_ids[]', Supplier::lists('first_name', 'id'), $supplier_ids, array('id'=>'supplier_ids', 'class'=>'form-control', 'multiple'=>true, 'data-json'=>false, 'data-title'=>"Suppliers"))}}
    {{$errors->first('supplier_ids', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
<div class="form-group custom-form-group">
    {{Form::label('region_id', 'Region: ')}}
    {{Form::select('region_id', Region::lists('region', 'id'), null, array('class'=>'form-control'))}}
    {{$errors->first('region_id', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group custom-form-group">
    {{Form::label('suburb_id', 'Subrub: ')}}
    {{Form::select('suburb_id', Postcode::lists('suburb', 'id'), null, array('class'=>'form-control'))}}
    {{$errors->first('suburb_id', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group custom-form-group">
    {{Form::label('postal_code_id', 'Postal Code: ')}}
    {{Form::select('postal_code_id', Postcode::lists('post_code', 'id'), null, array('class'=>'form-control'))}}
    {{$errors->first('postal_code_id', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('address1', 'Address 1: ')}}
    {{Form::text('address1', null, array('class'=>'form-control'))}}
    {{$errors->first('address1', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('address2', 'Address 2: ')}}
    {{Form::text('address2', null, array('class'=>'form-control'))}}
    {{$errors->first('address2', '<div class="alert alert-danger">:message</div>')}}
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">
<div class="form-group">
    {{Form::label('phone', 'Phone: ')}}
    {{Form::text('phone', null, array('class'=>'form-control'))}}
    {{$errors->first('phone', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('website', 'Website: ')}}
    {{Form::input('url', 'website', null, array('class'=>'form-control'))}}
    {{$errors->first('website', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('business_email', 'Business E-mail: ')}}
    {{Form::email('business_email', null, array('class'=>'form-control'))}}
    {{$errors->first('business_email', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('contact_name', 'Contact Name: ')}}
    {{Form::text('contact_name', null, array('class'=>'form-control'))}}
    {{$errors->first('contact_name', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('facebook_page_id', 'Facebook Page ID:')}}
    {{Form::text('facebook_page_id', null, array('class'=>'form-control input-lg'))}}
    <span id="facebook_page_button" style="display: none;"><a href="http://www.facebook.com/dialog/pagetab?app_id=490614574435398&next=http://www.facebook.com" target="_blank" class="btn btn-facebook">Click here to create vouchers tab page in your Facebook page</a></span>
    {{$errors->first('facebook_page_id', '<div class="alert alert-danger">:message</div>')}}
</div>
</div>
</div>
<br />
<div class="form-group">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th class="text-center">Logo</th>
            <th class="text-center">Active</th>
            <th class="text-center">Delete</th>
        </tr>
        <?php 
        
        for($i = 0; $i < 2; $i++):
            
        if ( !empty( $data['logos'][$i] ) && is_object( $data['logos'][$i] )) :
            $logo = $data['logos'][$i];
        ?>
        <tr>
            <td class="text-center">
                <img src="{{{$data['uri_merchant_logo_path'].'/'.$logo->pic.'.'.$logo->extension}}}" style="width:50px;height: 50px;">
            </td>
            <td class="text-center">
                {{Form::radio('active_logo', $logo->id, $logo->active_pic)}}
                {{$errors->first('active_logo', '<div class="alert alert-danger">:message</div>')}}
            </td>
            <td class="text-center">
                {{Form::checkbox('delete_logo[]', $logo->id, false)}}
                {{$errors->first('delete_logo', '<div class="alert alert-danger">:message</div>')}}
            </td>
        </tr>
        <?php
        else:
        ?>
        <tr>
            <td class="text-center" colspan="3">
                {{Form::file('logo'.$i)}}
                {{$errors->first('image', '<div class="alert alert-danger">:message</div>')}}
            </td>
        </tr>
        <?php
        endif;
        endfor;
        ?>
    </table>
</div>

<div class="form-group">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th class="text-center">Photo</th>
            <th class="text-center">Active</th>
            <th class="text-center">Delete</th>
        </tr>
        <?php 
        
        for($i = 0; $i < 5; $i++):
            
        if ( !empty( $data['photos'][$i] ) && is_object( $data['photos'][$i] )) :
            $photo = $data['photos'][$i];
        ?>
        <tr>
            <td class="text-center">
                <img src="{{{$data['uri_merchant_photo_path'].'/'.$photo->pic.'.'.$photo->extension}}}" style="width:50px;height: 50px;">
            </td>
            <td class="text-center">
                {{Form::radio('active_photo', $photo->id, $photo->active_pic)}}
                {{$errors->first('active_photo', '<div class="alert alert-danger">:message</div>')}}
            </td>
            <td class="text-center">
                {{Form::checkbox('delete_photo[]', $photo->id, false)}}
                {{$errors->first('delete_photo', '<div class="alert alert-danger">:message</div>')}}
            </td>
        </tr>
        <?php
        else:
        ?>
        <tr>
            <td class="text-center" colspan="3">
                {{Form::file('photo'.$i)}}
                {{$errors->first('image', '<div class="alert alert-danger">:message</div>')}}
            </td>
        </tr>
        <?php
        endif;
        endfor;
        ?>
    </table>
</div>

@if(Auth::user()->hasRule('assign_rules'))

<div class="form-group">
    {{Form::select('assign_rules_ids[]', Rule::lists('rule_name', 'id'), $data['assigned_rules_ids'], array('id'=>'assign_rules', 'class'=>'form-control', 'multiple'=>true, 'data-json'=>false, 'data-title'=>'Permissions'))}}
    {{$errors->first('assign_rules_ids', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('reset_rules', 'Reset Rules:')}}
    {{Form::checkbox('reset_rules', true, false)}}
    {{$errors->first('reset_rules', '<div class="alert alert-danger">:message</div>')}}
</div>

@endif

@if(Auth::user()->hasRule('user_activate'))
<div class='form-group'>
    {{Form::label('active', 'Active User: ')}}
    <?php $checked = (isset( $item_information_from_user_table->active ) && $item_information_from_user_table->active == true) ? TRUE : false; ?>
    {{Form::checkbox('active', true, $checked);}}
    {{$errors->first('active', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif

@if(Auth::user()->hasRule('merchant_activate'))
<div class='form-group'>
    {{Form::label('active_merchant', 'Active Merchant: ')}}
    <?php $checked = (isset( $item->active_merchant ) && $item->active == true) ? TRUE : false; ?>
    {{Form::checkbox('active_merchant', true, $checked);}}
    {{$errors->first('active_merchant', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif

<div class='form-group'>
    {{Form::label('featured', 'Featured: ')}}
    <?php $checked = (isset( $item->featured ) && $item->featured == true) ? TRUE : false; ?>
    {{Form::checkbox('featured', true, $checked);}}
    {{$errors->first('featured', '<div class="alert alert-danger">:messanger</div>')}}
</div>

<div class='form-group'>
    {{Form::label('display', 'display: ')}}
    <?php $checked = (isset( $item->display ) && $item->display == true) ? TRUE : false; ?>
    {{Form::checkbox('display', true, $checked);}}
    {{$errors->first('display', '<div class="alert alert-danger">:message</div>')}}
</div>



<div class="form-group">
    {{Form::submit('Save', array('class'=>'btn btn-primary'))}}
    {{link_to('merchant/'.$item->id, 'Cancel', array('class'=>'btn btn-danger'))}}
</div>

    {{Form::close()}}
</div>
<script src="{{{asset('js/dual-list-box.min.js')}}}"></script>

<script>
// =================================================== for Merchant AutoComplete
    (function ($) {
    $.widget("custom.combobox", {
        _create: function () {
            this.wrapper = $("<span>")
                    .addClass("custom-combobox")
                    .insertAfter(this.element);

            this.element.hide();
            this._createAutocomplete(this.element[0].id, this.element[0].getAttribute("placeholder"));
            this._createShowAllButton();
        },
        _createAutocomplete: function (original_id, original_placeholder) {
            var selected = this.element.children(":selected"),
                    value = selected.val() ? selected.text() : "";

            this.input = $("<input>")
                    .appendTo(this.wrapper)
                    .val(value)
                    .attr("title", "")
                    .attr("id", "ac_" + original_id)
                    .attr("placeholder", original_placeholder)
                    //.addClass( "form-control custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
                    //.css("width","50%")
                    .css("display", "inline-block")
                    .css("border-top-right-radius", "0px")
                    .css("border-bottom-right-radius", "0px")
                    .addClass("form-control")
                    .css("width", "95%")
                    .autocomplete({
                        delay: 0,
                        minLength: 0,
                        source: $.proxy(this, "_source")
                    })
                    .tooltip({
                        tooltipClass: "ui-state-highlight"
                    });

            this._on(this.input, {
                autocompleteselect: function (event, ui) {
                    ui.item.option.selected = true;
                    this._trigger("select", event, {
                        item: ui.item.option
                    });
                },
                autocompletechange: "_removeIfInvalid"
            });
        },
        _createShowAllButton: function () {
            var input = this.input,
                    wasOpen = false;

            $("<a>")
                    .attr("tabIndex", -1)
                    .attr("title", "Show All Items")
                    .tooltip()
                    .appendTo(this.wrapper)
                    .button({
                        icons: {
                            primary: "ui-icon-triangle-1-s"
                        },
                        text: false
                    })
                    .removeClass("ui-corner-all")
                    .addClass("custom-combobox-toggle ui-corner-right")


                    .css("height", "34px")


                    .mousedown(function () {
                        wasOpen = input.autocomplete("widget").is(":visible");
                    })
                    .click(function () {
                        input.focus();

                        // Close if already visible
                        if ( wasOpen ) {
                            return;
                        }

                        // Pass empty string as value to search for, displaying all results
                        input.autocomplete("search", "");
                    });
        },
        _source: function (request, response) {
            var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
            response(this.element.children("option").map(function () {
                var text = $(this).text();
                if ( this.value && (!request.term || matcher.test(text)) )
                    return {
                        label: text,
                        value: text,
                        option: this
                    };
            }));
        },
        _removeIfInvalid: function (event, ui) {

            // Selected an item, nothing to do
            if ( ui.item ) {
                return;
            }

            // Search for a match (case-insensitive)
            var value = this.input.val(),
                    valueLowerCase = value.toLowerCase(),
                    valid = false;
            this.element.children("option").each(function () {
                if ( $(this).text().toLowerCase() === valueLowerCase ) {
                    this.selected = valid = true;
                    return false;
                }
            });

            // Found a match, nothing to do
            if ( valid ) {
                return;
            }

            // Remove invalid value
            this.input
                    .val("")
                    .attr("title", value + " didn't match any item")
                    .tooltip("open");
            this.element.val("");
            this._delay(function () {
                this.input.tooltip("close").attr("title", "");
            }, 2500);
            this.input.autocomplete("instance").term = "";
        },
        _destroy: function () {
            this.wrapper.remove();
            this.element.show();
        }
    });
})(jQuery);

    $(function () {
        $("#industry_id").combobox();
        $("#franchisor_id").combobox();
        $("#region_id").combobox();
        $("#suburb_id").combobox();
        $("#postal_code_id").combobox();
        
//        multi select
        $("#supplier_ids").DualListBox();
        $("#assign_rules").DualListBox();
        
//        show or hide facebook page button
        var facebook_page_id = $("input[name=facebook_page_id]").val();
        if ('' !== facebook_page_id) {
            $("#facebook_page_button").show();
        }//if ('' !== facebook_page_id)
        else{
            $("#facebook_page_button").hide();  
        }//else
        $("input[name=facebook_page_id]").keyup(function(){
            if ($(this).val() !== '') {
                $("#facebook_page_button").fadeIn();
            }else{
                $("#facebook_page_button").fadeOut();
            }
        });//$("#facebook_page_button").change(function()
    });
</script>


@stop

