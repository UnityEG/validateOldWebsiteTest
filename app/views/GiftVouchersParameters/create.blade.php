<?php extract($data); ?>
@extends('layouts.main')

@section('main')

<link rel="stylesheet" href="{{asset('css/image-picker.css')}}">
<link rel="stylesheet" href="{{asset('css/wysiwyg/wysiwyg.css')}}">
<link rel="stylesheet" href="{{asset('css/wysiwyg/editor.css')}}">
<link rel="stylesheet" href="{{asset('css/wysiwyg/stylesheet.css')}}">

<link rel="stylesheet" href="{{asset('css/iconselect.css')}}">
<script type="text/javascript" src="{{ URL::asset('js/iconselect.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('js/iscroll.js') }}"></script>
<script>
var iconSelect;
var selectedText;

window.onload = function () {

    selectedText = document.getElementById('selected-text');

    document.getElementById('my-icon-select').addEventListener('changed', function (e) {
        selectedText.value = iconSelect.getSelectedValue();
    });

    iconSelect = new IconSelect("my-icon-select");
    var icons = [];


<?php
$photos = UserPic::where('type', '=', 'default_gift_voucher_image')->get();

foreach ($photos as $photo) :
    ?>
        icons.push({'iconFilePath': "{{asset($default_voucher_image_path.'/'.$photo->pic.'.'.$photo->extension)}}", 'iconValue': '{{$photo->id}}'});
    <?php
endforeach;
?>

    iconSelect.refresh(icons);

};

</script>

<style>

    #gallery{
        display: none;
    }

    #open_gallery:hover{
        cursor: pointer;
    }
    #ac_MerchantID, .ui-autocomplete-input{
        width:93% !important;
    }
    .custom-combobox a{
        width:6% !important;
    }
    #my-icon-select-box-scroll{
        z-index:999999;
    }

</style>
@if ($errors->any())
<ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif
<div class="forms-back">
    <h2>Create Gift Voucher Parameters</h2>
    {{ Form::open(array('route' => 'GiftVouchersParameters.store', 'role' => 'form', 'onsubmit' => 'min_max_check()')) }}
		<div class="row">
        <div class="col-md-6 pull-left">
    @if(Auth::user()->isMerchant())
    <input type="hidden" name="MerchantID" value="{{Auth::user()->merchant->id}}">

    @else
            <div class="form-group">
                <?php
                $options = array('0' => '');
                foreach (Merchant::lists('business_name', 'id') as $key => $val) {
                    $options[$key] = $val;
                }
                ?>
                {{ Form::label('MerchantID', 'Merchant:') }}
                {{ Form::select('MerchantID', $options, null, array('class'=>'form-control', "placeholder"=>"Select Merchant")) }}
            </div>

     @endif

            <div class="form-group ">
                {{ Form::label('Title', 'Title:') }}
                {{ Form::text('Title', 'Gift Voucher', array('class'=>'form-control', 'placeholder'=>'Enter a title for your gift voucher e.g. Gift Voucher','style'=>'width:100% !important;')) }}
            </div>

            <div class="radio">
                <label class="radio-inline">{{ Form::radio('SingleUse', 1, true) }}Single Use</label>
                <label class="radio-inline">{{ Form::radio('SingleUse', 0)       }}Multi Use</label>
            </div>
            <div id="div_NoOfUses" class="form-group">
                {{ Form::text('NoOfUses', null, array('id'=>'NoOfUses', 'class'=>'form-control', 'placeholder'=>'Leave blank if number of uses is unlimited','style'=>'width:100% !important;')) }}
            </div>
            <div id="div_ValidFor" class="form-group">
                {{ Form::label('ValidFor', 'Valid For:', array('style'=>'display: block;')) }}
                {{ Form::text('ValidFor', 12, array('class'=>'form-control', 'id'=>'validfortext', 'style'=>'display:inline-block;width:49%;')) }}
                {{ Form::select('ValidForUnits', array('d'=>'day(s)', 'w'=>'week(s)', 'm'=>'month(s)'), 'm', array('class'=>'form-control', 'id'=>'validforselect','style'=>'display:inline-block;width:49%;')) }}
            </div>

        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">	
            <div class="form-group ">
                <label for="my-icon-select">Choose Voucher Image</label>
                <div id="my-icon-select"></div>  
                <input type="hidden" id="selected-text" name="image_id">

            </div> 

        </div>

    </div>	


    <!--
        <div class="form-group">
        <label for="open_gallery">Choose Voucher Image</label>
        
        <a id="open_gallery">
            @if(!empty($src))

            <img src="{{$src}}" style="width:150px;height: 100px;">

            @else
            Choose Voucher Image
            
            @endif
        </a>
    </div>
    
        <div id="gallery" class="form-group">
        <select name="image_id" class="image-picker">
            <option data-img-src='{{$src}}' value="{{$data['default_voucher_image']->id}}"></option>
    <?php
    $photos = UserPic::where('type', '=', 'default_gift_voucher_image')->get();

    foreach ($photos as $photo) :
        ?>
                                <option data-img-src='{{asset($default_voucher_image_path.'/'.$photo->pic.'.'.$photo->extension)}}' value="{{$photo->id}}"></option>
        <?php
    endforeach;
    ?>
        </select>  
        </div> -->

    <div class="row">  

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">		
            <div class="form-group">
                {{Form::label('short_description', 'Short Description:')}}
                <div id="wysihtml5-editor-toolbar-short-description">
      <header>
        <ul class="commands">
          <li data-wysihtml5-command="bold" title="Make text bold (CTRL + B)" class="command"></li>
          <li data-wysihtml5-command="italic" title="Make text italic (CTRL + I)" class="command"></li>
          <li data-wysihtml5-command="insertUnorderedList" title="Insert an unordered list" class="command"></li>
          <li data-wysihtml5-command="insertOrderedList" title="Insert an ordered list" class="command"></li>
          <li data-wysihtml5-command="createLink" title="Insert a link" class="command"></li>
          <!--<li data-wysihtml5-command="insertImage" title="Insert an image" class="command"></li>-->
          <li data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" title="Insert headline 1" class="command"></li>
          <li data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" title="Insert headline 2" class="command"></li>
          <li data-wysihtml5-command-group="foreColor" class="fore-color" title="Color the selected text" class="command">
            <ul>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="silver"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="gray"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="black"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="maroon"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="red"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="purple"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="green"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="olive"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="navy"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="blue"></li>
            </ul>
          </li>
          <li data-wysihtml5-command="insertSpeech" title="Insert speech" class="command"></li>
          <li data-wysihtml5-action="change_view" title="Show HTML" class="action"></li>
        </ul>
      </header>
      <div data-wysihtml5-dialog="createLink" style="display: none;">
        <label>
          Link:
          <input data-wysihtml5-dialog-field="href" value="http://">
        </label>
        <a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
      </div>

      <div data-wysihtml5-dialog="insertImage" style="display: none;">
        <label>
          Image:
          <input data-wysihtml5-dialog-field="src" value="http://">
        </label>
        <a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
      </div>
    </div>
                {{Form::textarea('short_description', null, array('id'=>'wysihtml5-textarea-short-description', 'class'=>'form-control input-lg', 'placeholder'=>'Short Description of voucher','style'=>'display:inline-block;width:100%;', 'rows'=>1, 'cols'=>20))}}
            </div>
        </div>	
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">	
            <div class="checkbox form-group">
                <label>{{ Form::checkbox('LimitedQuantity', 1, false, array('id'=>'LimitedQuantity')) }}Limited Quantity</label>
            </div>
            <div id="div_Quantity"  class="form-group">
                {{ Form::label('Quantity', 'Quantity:') }}
                {{ Form::text('Quantity',null, array('class'=>'form-control','style'=>'display:inline-block;width:100%;')) }}
            </div>
            <div class="form-group">
                {{ Form::label('MinVal', 'Min Value:') }} <em id="minerr">Minimum value for a Gift Voucher is $20.00</em><br />
                {{ Form::text('MinVal', '$20.00', array('id'=>'minVal', 'class'=>'form-control', 'placeholder'=>'Minimum value for a Gift Voucher is $20.00','style'=>'display:inline-block;width:100%;')) }}
            </div>
            <div class="form-group">
                {{ Form::label('MaxVal', 'Max Value:') }}
                {{ Form::text('MaxVal',null, array('id'=>'maxVal', 'class'=>'form-control', 'placeholder'=>'Enter the Maximum value for a Gift Voucher','style'=>'display:inline-block;width:100%;')) }}
            </div>
        </div>	

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">	   
            <div class="form-group">
        {{Form::label('long_description', 'Long Description:')}}
        <div id="wysihtml5-editor-toolbar">
      <header>
        <ul class="commands">
          <li data-wysihtml5-command="bold" title="Make text bold (CTRL + B)" class="command"></li>
          <li data-wysihtml5-command="italic" title="Make text italic (CTRL + I)" class="command"></li>
          <li data-wysihtml5-command="insertUnorderedList" title="Insert an unordered list" class="command"></li>
          <li data-wysihtml5-command="insertOrderedList" title="Insert an ordered list" class="command"></li>
          <li data-wysihtml5-command="createLink" title="Insert a link" class="command"></li>
          <!--<li data-wysihtml5-command="insertImage" title="Insert an image" class="command"></li>-->
          <li data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" title="Insert headline 1" class="command"></li>
          <li data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" title="Insert headline 2" class="command"></li>
          <li data-wysihtml5-command-group="foreColor" class="fore-color" title="Color the selected text" class="command">
            <ul>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="silver"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="gray"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="black"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="maroon"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="red"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="purple"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="green"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="olive"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="navy"></li>
              <li data-wysihtml5-command="foreColor" data-wysihtml5-command-value="blue"></li>
            </ul>
          </li>
          <li data-wysihtml5-command="insertSpeech" title="Insert speech" class="command"></li>
          <li data-wysihtml5-action="change_view" title="Show HTML" class="action"></li>
        </ul>
      </header>
      <div data-wysihtml5-dialog="createLink" style="display: none;">
        <label>
          Link:
          <input data-wysihtml5-dialog-field="href" value="http://">
        </label>
        <a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
      </div>

      <div data-wysihtml5-dialog="insertImage" style="display: none;">
        <label>
          Image:
          <input data-wysihtml5-dialog-field="src" value="http://">
        </label>
        <a data-wysihtml5-dialog-action="save">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel">Cancel</a>
      </div>
    </div>
        {{Form::textarea('long_description', null, array('id'=>'wysihtml5-textarea', 'class'=>'form-control', 'placeholder'=>'Long Description of voucher'))}}
    </div>
        </div>
    </div>
    <!--  <div class="radio">
          <label class="radio-inline">{{ Form::radio('SingleUse', 1, true) }}Single Use</label>
          <label class="radio-inline">{{ Form::radio('SingleUse', 0)       }}Multi Use</label>
      </div>
      <div id="div_NoOfUses" class="form-group">
          {{ Form::text('NoOfUses', null, array('id'=>'NoOfUses', 'class'=>'form-control', 'placeholder'=>'Leave blank if number of uses is unlimited')) }}
      </div> 
      <div id="div_ValidFor" class="form-group">
         {{ Form::label('ValidFor', 'Valid For:', array('style'=>'display: block;')) }}
         {{ Form::text('ValidFor', 12, array('class'=>'form-control', 'id'=>'validfortext', 'style'=>'display:inline-block;width:25%;')) }}
         {{ Form::select('ValidForUnits', array('d'=>'day(s)', 'w'=>'week(s)', 'm'=>'month(s)'), 'm', array('class'=>'form-control', 'id'=>'validforselect','style'=>'display:inline-block;width:25%;')) }}
     </div> -->

    <div class="form-group">
        {{ Form::label('TermsOfUse', 'Terms of Use:') }}
        {{-- Form::textarea('TermsOfUse',null, array('class'=>'form-control')) --}}
        {{Form::select('selected_terms[]', UseTerm::lists('name','id'), $data['selected_terms'], array(
                            'id'=>'terms', 
                            'class'=>'form-control', 
                            'multiple'=>true, 
                            'data-json'=>false, 
                            'data-title'=>'Terms'
                            ))}}
        <script src="{{{asset('js/dual-list-box.min.js')}}}"></script>
        <style>.selected {display: inherit;}</style>
        <script>$("#terms").DualListBox();</script>
    </div>

    {{ Form::submit('Create', array('class' => 'btn btn-primary')) }}

    {{ Form::close() }}
</div>
@if ($errors->any())
<script>
    var minVal = document.getElementById("minVal")
    var maxVal = document.getElementById("maxVal")
    minVal.value = "$" + minVal.value
    maxVal.value = "$" + maxVal.value
</script>
@endif

<!--Image-Picker script-->
<script src="{{asset('js/image-picker.min.js')}}"></script>

<!--WYSIWYG-->
<script src="{{asset('js/wysiwyg/simple.js')}}"></script>
<script src="{{asset('js/wysiwyg/wysihtml5-0.3.0.min.js')}}"></script>

<script src="http://raw.githubusercontent.com/fernandofig/jquery-formatcurrency/master/jquery.formatCurrency.js"></script>


<script>

    $(document).ready(function (e) {
    });
    //no use
    /*try {
     var pages = $("#pages").msDropdown({on:{change:function(data, ui) {
     var val = data.value;
     if(val!="")
     window.location = val;
     }}}).data("dd");
     
     var pagename = document.location.pathname.toString();
     pagename = pagename.split("/");
     pages.setIndexByValue(pagename[pagename.length-1]);
     $("#ver").html(msBeautify.version.msDropdown);
     } catch(e) {
     //console.log(e);	
     }
     
     $("#ver").html(msBeautify.version.msDropdown);
     
     //convert
     $("#gallery > select").msDropdown({roundedBorder:false});
     $("#tech").data("dd");
     });
     function showValue(h) {
     console.log(h.name, h.value);
     }
     $("#tech").change(function() {
     console.log("by jquery: ", this.value);
     })*/
//
</script>


<script>
    function min_max_check() {
        var minVal = document.getElementById("minVal")
        var maxVal = document.getElementById("maxVal")
        if (minVal.value.charAt(0) == '$') {
            minVal.value = minVal.value.substring(1, minVal.value.length)
        }
        if (maxVal.value.charAt(0) == '$') {
            maxVal.value = maxVal.value.substring(1, maxVal.value.length)
        }
    }
</script>
<script>
    $(document).ready(function ()
    {
        $('#minVal').blur(function ()
        {
            if ($('#minVal').val() < 20) {
                $('#minVal').val('');
                $('#minerr').css('color', '#ff0000');
            }
            else {
                $('#minVal').formatCurrency({groupDigits: false});
                $('#minerr').css('color', '');
            }
        });
        $('#maxVal').blur(function ()
        {
            $('#maxVal').formatCurrency({groupDigits: false});
        });
    });</script>

<!-- Valid For (Limitations) -->
<script>
    $(document).ready(function ()
    {
        $('#validfortext').blur(function ()
        {

            if ($('#validforselect').val() == 'd' && $('#validfortext').val() > 7) {
                $('#validfortext').val('');
                $('#validfortext').attr("placeholder", "Maximum number of days is 7");
            }
            else if ($('#validforselect').val() == 'w' && $('#validfortext').val() > 52) {
                $('#validfortext').val('');
                $('#validfortext').attr("placeholder", "Maximum number of weeks is 52");
            }
            else if ($('#validforselect').val() == 'm' && $('#validfortext').val() > 12) {
                $('#validfortext').val('');
                $('#validfortext').attr("placeholder", "Maximum number of months is 12");
            }

        });
        $('#validforselect').change(function () {

            if ($('#validforselect').val() == 'd' && $('#validfortext').val() > 7) {
                $('#validfortext').val('');
                $('#validfortext').attr("placeholder", "Maximum number of days is 7");
            }
            else if ($('#validforselect').val() == 'w' && $('#validfortext').val() > 52) {
                $('#validfortext').val('');
                $('#validfortext').attr("placeholder", "Maximum number of weeks is 52");
            }
            else if ($('#validforselect').val() == 'm' && $('#validfortext').val() > 12) {
                $('#validfortext').val('');
                $('#validfortext').attr("placeholder", "Maximum number of months is 12");
            }

        });
    });</script>


<script>
// =============================================================================
    $(document).ready(function () {
        first_run = true;
        ShowHide_div_NoOfUses();
        ShowHide_div_Quantity();
        first_run = false;
    });
// =============================================================================
    function ShowHide_div_NoOfUses() {
        if ($('input[Name="SingleUse"]:checked').val() === '1') {
            $('#NoOfUses').val('1');
            $('#div_NoOfUses').hide();
        } else {
            $('#div_NoOfUses').show();
            if (!first_run) {
                $('#NoOfUses').focus();
                $('#NoOfUses').val(null);
            }
            ;
        }
    }
// -----------------------------------------------------------------------------
    $('input[Name="SingleUse"]').click(function () {
        ShowHide_div_NoOfUses();
    });
// =============================================================================
    function ShowHide_div_Quantity() {
        if ($('#LimitedQuantity').is(':checked')) {
            $('#div_Quantity').show();
            if (!first_run) {
                $('#Quantity').focus();
                $('#Quantity').val(null);
            }
            ;
        } else {
            $('#div_Quantity').hide();
            $('#Quantity').val('0');
        }
    }
    $('#LimitedQuantity').click(function () {
        ShowHide_div_Quantity();
    });
// =============================================================================
</script>
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
                            if (wasOpen) {
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
                    if (this.value && (!request.term || matcher.test(text)))
                        return {
                            label: text,
                            value: text,
                            option: this
                        };
                }));
            },
            _removeIfInvalid: function (event, ui) {

                // Selected an item, nothing to do
                if (ui.item) {
                    return;
                }

                // Search for a match (case-insensitive)
                var value = this.input.val(),
                        valueLowerCase = value.toLowerCase(),
                        valid = false;
                this.element.children("option").each(function () {
                    if ($(this).text().toLowerCase() === valueLowerCase) {
                        this.selected = valid = true;
                        return false;
                    }
                });
                // Found a match, nothing to do
                if (valid) {
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
        $("#MerchantID").combobox();
        $("#toggle").click(function () {
            $("#MerchantID").toggle();
        });
        $('#ac_MerchantID').focus();
//        image-picker
        $("select[name=image_id]").imagepicker();
        $("#open_gallery").click(function (e) {
            e.preventDefault();
            $("#gallery").toggle();
        });
    });
</script>

<script>
    $(function () {
        var editor = new wysihtml5.Editor("wysihtml5-textarea", {// id of textarea element
            toolbar: "wysihtml5-editor-toolbar", // id of toolbar element
            style:                true,
            parserRules: wysihtml5ParserRules, // defined in parser rules set 
            stylesheets: ["{{asset('css/wysiwyg/editor.css')}}"]
        });
    });

</script>

<script>
    $(function () {
        var editor = new wysihtml5.Editor("wysihtml5-textarea-short-description", {// id of textarea element
            toolbar: "wysihtml5-editor-toolbar-short-description", // id of toolbar element
            style:                true,
            parserRules: wysihtml5ParserRules, // defined in parser rules set 
            stylesheets: ["{{asset('css/wysiwyg/editor.css')}}"]
        });
    });

</script>
@stop