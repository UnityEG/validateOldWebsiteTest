<?php extract( $data); ?>

@extends('layouts.main')

@section('main')

<style>
    label[for="agreement"]{
        color: #00aff0;
    }
    
    label[for="agreement"]:hover{
        cursor: pointer;
    }
    
    #agreement-paragraph{
        display: none;
    }
	input[type=text]:not(.filter),input[type=email],
	input[type=password],input[type=date],
	input[type=search],input[type=tel],input[type=phone]
	{
		width:100%;
	}
	.ui-autocomplete-input{
		width:90%;
		height: 40px;
	}
	.ui-button-icon-only{
		width:9%;
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
<h2>customer sign up</h2>
{{Form::open(array('route'=>'customer.store', 'method'=>'post', 'role'=>'form'))}}
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
@if($user_exist)
{{Form::hidden('user_id', $user_exist)}}
@else
<div class="form-group">
    {{Form::label('email', 'Email: ')}}
    {{Form::email('email', null, array('class'=>'form-control'))}}
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

@endif
<div class="form-group">
    {{Form::label('first_name', 'First Name: ')}}
    {{Form::text('first_name', null, array('class'=>'form-control'))}}
    {{$errors->first('first_name', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('last_name', 'Last Name: ')}}
    {{Form::text('last_name', null, array('class'=>'form-control'))}}
    {{$errors->first('last_name', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group" style="margin-bottom:22px; margin-top:22px">
    <label style="margin-right:50px">Gender: </label>
    {{Form::label('male', 'Male ')}}
    {{Form::radio('gender', 'male', true, array('id'=>'male'))}}
    &nbsp; &nbsp; &nbsp; &nbsp;
    {{Form::label('female', 'Female')}}
    {{Form::radio('gender', 'female', false, array('id'=>'female'))}}
    {{$errors->first('gender', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('title', 'Title: ')}}
    {{Form::select('title', array('Mr'=>"Mr", 'Mrs'=>"Mrs", 'Miss'=>"Miss", 'Ms'=>"Ms"), null, array('class'=>'form-control'))}}
    {{$errors->first('title', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('dob', 'Date of Birth: ')}}
    {{Form::text('dob', null, array('id'=>'dob', 'class'=>'form-control', 'placeholder'=>'dd/mm/yyyy'))}}
    {{$errors->first('dob', '<div class="alert alert-danger">:message</div>')}}
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">

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

<div class="form-group">
    {{Form::label('phone', 'Phone: ')}}
    {{Form::text('phone', null, array('class'=>'form-control'))}}
    {{$errors->first('phone', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('mobile', 'Mobile: ')}}
    {{Form::text('mobile', null, array('class'=>'form-control'))}}
    {{$errors->first('mobile', '<div class="alert alert-danger">:message</div>')}}
</div>
</div>
</div>


@if(Auth::user() && Auth::user()->hasRule('assign_rules'))
<div class="form-group rules-box">
    {{Form::select('assign_rules_ids[]', Rule::lists('rule_name', 'id'), $data['default_assigned_rules'], array('id'=>'assign_rules', 'class'=>'form-control', 'multiple'=>true, 'data-json'=>false, 'data-title'=>'Permissions'))}}
    {{$errors->first('assign_rules_ids', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif

<div class='form-group'>
    {{Form::label('notify_deal', 'Notify Deal: ')}}
    {{Form::checkbox('notify_deal', true, true);}}
    {{$errors->first('notify_deal', '<div class="alert alert-danger">:message</div>')}}
</div>


@if(Auth::user() && Auth::user()->hasRule('user_activate'))
<div class='form-group'>
    {{Form::label('active', 'Active User: ')}}
    {{Form::checkbox('active', true, true);}}
    {{$errors->first('active', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif

@if(Auth::user() && Auth::user()->hasRule('customer_activate'))
<div class='form-group'>
    {{Form::label('active_customer', 'Active Customer: ')}}
    {{Form::checkbox('active_customer', true, true);}}
    {{$errors->first('active_customer', '<div class="alert alert-danger">:message</div>')}}
</div>

@endif


<div class="form-group">
    {{Form::label('agreement', 'I have read and accept the terms and conditions of validate')}}
    {{Form::checkbox('agreement', true, false)}}
    {{$errors->first('agreement', '<div class="alert alert-danger">:message</div>')}}
</div>

<div id="agreement-paragraph">
    <p>Agreement of working with Validate Company.</p>
</div>

<div class="form-group">
    {{Form::submit('Create Customer', array('class'=>'btn btn-primary'))}}
    {{link_to('customer/', 'Cancel', array('class'=>'btn btn-danger'))}}
</div>


{{ Form::close() }}
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
                this._createAutocomplete();
                this._createShowAllButton();
            },
            _createAutocomplete: function () {
                var selected = this.element.children(":selected"),
                        value = selected.val() ? selected.text() : "";

                this.input = $("<input>")
                        .appendTo(this.wrapper)
                        .val(value)
                        .attr("title", "")
                        //.addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
                        .addClass("form-control")
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
        $("#region_id").combobox();
        $("#suburb_id").combobox();
        $("#postal_code_id").combobox();
        
//        multi select
        $("#assign_rules").DualListBox();
        
//        agreement dialog box
        $("label[for=agreement]").click(function (){
            $("#agreement-paragraph").dialog();
        });
        
    });
</script>

<script>
$(function() {
    $("#dob").datepicker({
            dateFormat: "dd/mm/yy",
            defaultDate: "1/1/1993",
            changeMonth: true,
            changeYear: true,
            yearRange: "-115:-18"
        });
    });
</script>


@stop




