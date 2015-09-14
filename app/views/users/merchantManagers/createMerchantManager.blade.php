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
{{Form::open(array('route'=>'merchant_manager.store', 'method'=>'post', 'role'=>'form'))}}
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-left">
<div class="form-group">
    {{Form::label('email', 'E-mail: ')}}
    {{Form::email('email', null, array('class'=>'form-control'))}}
    {{$errors->first('email', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('password', 'Password: ')}}
    {{Form::password('password', array('class'=>'form-control'))}}
    {{$errors->first('password', '<div class="alert alert-danger">:message</div>')}}
</div>

<div class="form-group">
    {{Form::label('password_confirmation', 'Password Confirmation: ')}}
    {{Form::password('password_confirmation', array('class'=>'form-control'))}}
    {{$errors->first('password_confirmation', '<div class="alert alert-danger">:message</div>')}}
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 pull-right">
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

@unless(Auth::user()->user_type == 'merchant')
<div class="form-group custom-form-group">
    {{Form::label('merchant_id', 'Merchant')}}
    {{Form::select('merchant_id', Merchant::lists('business_name', 'id'), null, array('class'=>'form-control'))}}
    {{$errors->first('merchant_id', '<div class="alert alert-danger">:message</div>')}}
</div>
@endunless
</div>
</div>
@if(Auth::user() && Auth::user()->hasRule('assign_rules'))

<div class="form-group">
    {{Form::select('assign_rules_ids[]', Rule::lists('rule_name', 'id'), $data['default_assigned_rules'], array('id'=>'assign_rules', 'class'=>'form-control', 'multiple'=>true, 'data-json'=>false, 'data-title'=>'Permissions'))}}
    {{$errors->first('assign_rules_ids', '<div class="alert alert-danger">:message</div>')}}
</div>

@endif

@if(Auth::user() && Auth::user()->hasRule('user_activate'))
<div class="form-group">
    {{Form::label('active', 'Active User: ')}}
    {{Form::checkbox('active', true, true)}}
    {{$errors->first('active', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif

@if(Auth::user() && Auth::user()->hasRule('merchant_manager_activate'))
<div class="form-group">
    {{Form::label('active_merchant_manager', 'Active Merchant Manager: ')}}
    {{Form::checkbox('active_merchant_manager', true, true)}}
    {{$errors->first('active_merchant_manager', '<div class="alert alert-danger">:message</div>')}}
</div>
@endif
<div class="form-group">
    {{Form::submit('Create Merchant Manager', array('class'=>'btn btn-primary'))}}
    {{link_to_route('merchant_manager.index', 'Cancel', null, array('class'=>'btn btn-danger'))}}
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
        $("#merchant_id").combobox();
        $("#assign_rules").DualListBox();
    });
</script>

@stop