<?php $route = 'GiftVoucher';?>
@extends('layouts.main')

@section('main')
<style>
	.form-group{
	margin-bottom: -5px;
	}
</style>
@if ($errors->any())
    <ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif
<div class="forms-back">
<h2>Purchase Gift Voucher</h2>
{{ Form::open(array('route' => $route.'.store', 'role' => 'form', 'onsubmit' => 'vv_check()')) }}
	<?php //include(app_path().'/views/GiftVoucher/_inc_create.php');?>
	<?php //{{ Form::hidden ('customer_id', $customer_id)}} ?>
	{{ Form::hidden ('gift_vouchers_parameters_id', $gift_vouchers_parameters->id)}}
	<h2>{{ $MerchantBusinessName }}</h2>
	<h4>{{ $gift_vouchers_parameters->Title }}</h4>

	<div class="form-group">
	<?php 
	$value_message='Enter a value between $'.number_format($gift_vouchers_parameters->MinVal, 2, '.', ','). ' and $'.number_format($gift_vouchers_parameters->MaxVal, 2, '.', ','); 
	 
	?>
		{{ Form::label('voucher_value', 'Voucher Value:')}}
		{{ Form::text ('voucher_value', null, array('class'=>'form-control', 'id'=>'voucher_value','placeholder'=>$value_message)) }}
	</div>
	<div class="form-group">
		{{ Form::label('delivery_date', 'Delivery Date:') }}
		{{ Form::text ('delivery_date', null, array('class'=>'form-control','id'=>'delivery_date','placeholder'=>'dd/mm/yyyy')) }}
	</div>
	<div class="form-group">
		{{ Form::label('recipient_email', 'Recipient Email:') }}
		{{ Form::text ('recipient_email', null, array('class'=>'form-control', 'placeholder'=>'Email of the person the voucher is for.')) }}
	</div>
	<div class="form-group">
		{{ Form::label(   'message', 'Message to Recipient:') }}
		{{ Form::textarea('message',null, array('class'=>'form-control','style'=>'width:50%;')) }}
	</div>

	{{ Form::submit('Add to Cart', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}
</div>
@if ($errors->any())
<script>
var vv=document.getElementById("voucher_value")
vv.value="$"+vv.value
</script>
@endif
<script>
function vv_check(){
var vv=document.getElementById("voucher_value")
vv.value=vv.value.substring(1, vv.value.length)
}
</script>
<!-- <script src="http://raw.githubusercontent.com/fernandofig/jquery-formatcurrency/master/jquery.formatCurrency.js"> </script> -->
<script>
            $(document).ready(function()
            {
                $('#voucher_value').blur(function()
                {
                    $('#voucher_value').formatCurrency({ groupDigits:false });
		})
            });



</script>
<script>
$(function() {
	$("#delivery_date").datepicker({
dateFormat: "dd/mm/yy",
minDate: "tomorrow"
});
});
</script>
<!--script>
// =============================================================================
$( document ).ready(function() {
	first_run = true;
	ShowHide_div_NoOfUses();
	ShowHide_div_ValidFor();
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
	if(!first_run){
		$('#NoOfUses').focus();
		$('#NoOfUses').val(null);
	};
  }
}
// -----------------------------------------------------------------------------
$('input[Name="SingleUse"]').click(function(){
	ShowHide_div_NoOfUses();
});
// =============================================================================
function ShowHide_div_ValidFor() {
  if ($('#Expires').is(':checked')) {
	$('#div_ValidFor').show();
	if(!first_run){
		$('#ValidFor').focus();
		$('#ValidFor').val(null);
	};
  } else {
	$('#ValidFor').val('0');
	$('#div_ValidFor').hide();
  }
}
// -----------------------------------------------------------------------------
$('#Expires').click(function(){
	ShowHide_div_ValidFor();
});
// =============================================================================

function ShowHide_div_Quantity() {
  if ($('#LimitedQuantity').is(':checked')) {
	$('#div_Quantity').show();
	if(!first_run){
		$('#Quantity').focus();
		$('#Quantity').val(null);
	};
  } else {
	$('#div_Quantity').hide();
	$('#Quantity').val('0');
  }
}
$('#LimitedQuantity').click(function(){
	ShowHide_div_Quantity();
});
// =============================================================================
</script -->
<!-- script>
// =================================================== for Merchant AutoComplete
(function( $ ) {
	$.widget( "custom.combobox", {
		_create: function() {
			this.wrapper = $( "<span>" )
				.addClass( "custom-combobox" )
				.insertAfter( this.element );

			this.element.hide();
			this._createAutocomplete();
			this._createShowAllButton();
		},

		_createAutocomplete: function() {
			var selected = this.element.children( ":selected" ),
				value = selected.val() ? selected.text() : "";

			this.input = $( "<input>" )
				.appendTo( this.wrapper )
				.val( value )
				.attr( "title", "" )
				//.addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
				.addClass( "form-control" )
				.autocomplete({
					delay: 0,
					minLength: 0,
					source: $.proxy( this, "_source" )
				})
				.tooltip({
					tooltipClass: "ui-state-highlight"
				});

			this._on( this.input, {
				autocompleteselect: function( event, ui ) {
					ui.item.option.selected = true;
					this._trigger( "select", event, {
						item: ui.item.option
					});
				},

				autocompletechange: "_removeIfInvalid"
			});
		},

		_createShowAllButton: function() {
			var input = this.input,
				wasOpen = false;

			$( "<a>" )
				.attr( "tabIndex", -1 )
				.attr( "title", "Show All Items" )
				.tooltip()
				.appendTo( this.wrapper )
				.button({
					icons: {
						primary: "ui-icon-triangle-1-s"
					},
					text: false
				})
				.removeClass( "ui-corner-all" )
				.addClass( "custom-combobox-toggle ui-corner-right" )
				.mousedown(function() {
					wasOpen = input.autocomplete( "widget" ).is( ":visible" );
				})
				.click(function() {
					input.focus();

					// Close if already visible
					if ( wasOpen ) {
						return;
					}

					// Pass empty string as value to search for, displaying all results
					input.autocomplete( "search", "" );
				});
		},

		_source: function( request, response ) {
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
			response( this.element.children( "option" ).map(function() {
				var text = $( this ).text();
				if ( this.value && ( !request.term || matcher.test(text) ) )
					return {
						label: text,
						value: text,
						option: this
					};
			}) );
		},

		_removeIfInvalid: function( event, ui ) {

			// Selected an item, nothing to do
			if ( ui.item ) {
				return;
			}

			// Search for a match (case-insensitive)
			var value = this.input.val(),
				valueLowerCase = value.toLowerCase(),
				valid = false;
			this.element.children( "option" ).each(function() {
				if ( $( this ).text().toLowerCase() === valueLowerCase ) {
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
				.val( "" )
				.attr( "title", value + " didn't match any item" )
				.tooltip( "open" );
			this.element.val( "" );
			this._delay(function() {
				this.input.tooltip( "close" ).attr( "title", "" );
			}, 2500 );
			this.input.autocomplete( "instance" ).term = "";
		},

		_destroy: function() {
			this.wrapper.remove();
			this.element.show();
		}
	});
})( jQuery );

$(function() {
	$( "#MerchantID" ).combobox();
	$( "#toggle" ).click(function() {
		$( "#MerchantID" ).toggle();
	});
});
</script -->
@stop