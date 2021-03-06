@extends('layouts.main')

@section('main')
@if ($errors->any())
    <ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif
<div class="forms-back">
<div align="right">{{ link_to_route('GiftVoucher.index', 'List all Gift Vouchers') }}</div>
<h1>Edit Gift Vouchers Parameters</h1>
{{ Form::model($GiftVouchersParameter, array('method' => 'PATCH', 'route' => array('GiftVouchersParameters.update', $GiftVouchersParameter->id))) }}
	<div class="form-group">
		{{ Form::label('MerchantID', 'Merchant:') }}
		{{ Form::select('MerchantID', Merchant::lists('business_name','id'), null, array('class'=>'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('Title', 'Title:') }}
		{{ Form::text('Title', null, array('class'=>'form-control')) }}
	</div>
	<div class="radio">
		<label class="radio-inline">{{ Form::radio('SingleUse', 1, true) }}Single Use</label>
		<label class="radio-inline">{{ Form::radio('SingleUse', 0)       }}Multi Use</label>
	</div>
	<div id="div_NoOfUses" class="form-group">
		{{ Form::label('NoOfUses', 'Number of uses:') }} <em>Leave blank if number of uses is unlimited</em>
		{{ Form::text('NoOfUses', null, array('id'=>'NoOfUses', 'class'=>'form-control', 'placeholder'=>'Leave blank if number of uses is unlimited')) }}
	</div>
	<div class="checkbox">
		<label>{{ Form::checkbox('Expires', 1, true, array('id'=>'Expires')) }}Expires</label>
	</div>
	<div id="div_ValidFor" class="form-group">
		{{ Form::label('ValidFor', 'Valid For:') }}
		{{ Form::text('ValidFor',null, array('class'=>'form-control')) }}
		{{ Form::select('ValidForUnits', array('d'=>'day(s)', 'w'=>'week(s)', 'm'=>'month(s)'), null, array('class'=>'form-control')) }}
	</div>
	<div class="checkbox">
		<label>{{ Form::checkbox('LimitedQuantity', 1, true, array('id'=>'LimitedQuantity')) }}Limited Quantity</label>
	</div>
	<div id="div_Quantity"  class="form-group">
		{{ Form::label('Quantity', 'Quantity:') }}
		{{ Form::text('Quantity',null, array('class'=>'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('MinVal', 'Min Value:') }}
		{{ Form::text('MinVal',null, array('class'=>'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('MaxVal', 'Max Value:') }}
		{{ Form::text('MaxVal',null, array('class'=>'form-control')) }}
	</div>
	<div class="form-group">
		{{ Form::label('TermsOfUse', 'Terms of Use:') }}
		{{ Form::textarea('TermsOfUse',null, array('class'=>'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::submit('Update', array('class' => 'btn btn-info')) }}
		{{ link_to_route('GiftVouchersParameters.show', 'Cancel', $GiftVouchersParameter->id, array('class' => 'btn')) }}
	</div>

{{ Form::close() }}
</div>
<script>
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
</script>
<script>
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
</script>
@stop