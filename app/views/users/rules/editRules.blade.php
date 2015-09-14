@extends('layouts.main')

@section('main')

<style>
    
    h3 span{
         cursor:pointer;
    }
    
    ul li{
        list-style: none;
    }
    
    li label{
        font-weight: normal;
    }
    
    .folded{
        font-style: italic;
        color: #828282 !important;
    }
    
    .checked{
        background-color: #C8C8C8;
        font-style: italic;
        font-weight: bold;
    }
    
</style>
<div class="forms-back">
    <h2><em>{{$data['rule']->rule_name}}</em></h2>
    <p>active/inactive this rule to the following users.</p>
    <div class="form-group">
        <input type="text" id="userSearch" class="form-control" placeholder="Search for user">
    </div>

    {{Form::open(array('route'=>array('rules.update', $data['rule']->id), 'method'=>'put', 'role'=>'form'))}}

    @if($data['edit_rule_name'] == true)
    <div class="form-group">
        {{Form::label('rule_name', 'Rule Name: ')}}
        {{Form::text('rule_name', $data['rule']->rule_name, array('class'=>'form-control'))}}
        {{$errors->first('rule_name')}}
    </div>

    @endif

    <div id="allArea">
    <?php
    foreach ( $data[ 'user_types' ] as $user_type ) :
        ?>

        <div id="{{$user_type}}">
            <h3><input type="checkbox"><span>{{{$user_type}}}s</span></h3>
            <ul>

                <?php
                foreach ( $data[ $user_type ] as $user ):
//    get activated rule from pivot table
                    $user_related_with_rule = $data[ 'rule' ]->users()->where( 'user_id', '=', $user->id )->first();
                    if ( !empty( $user_related_with_rule ) ) {
                        $checked = $user_related_with_rule->pivot->active_rule;
                    } else {
                        $checked = false;
                    }
                    ?>

                <li>
                        {{Form::checkbox('users[]', $user->id, $checked, array('id'=>'user_'.$user->id))}}
                        @if($checked)
                        {{Form::label('user_'.$user->id, $user->email, array('class'=>'checked'))}}
                        @else
                        {{Form::label('user_'.$user->id, $user->email)}}
                        @endif
                    </li>

                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
    </div>

    <div class="form-group">
        {{Form::submit('Save', array('class'=>'btn btn-primary'))}}
        {{link_to_route('rules.show', 'Cancel', array($data['rule']->id), array('class'=>'btn btn-danger'))}}
    </div>

    {{Form::close()}}
</div>


<script>

    jQuery("document").ready(function () {
        var userTypes = ['owner', 'admin', 'customer', 'merchant', 'franchisor', 'supplier', 'merchant_manager'];

        $.each(userTypes, function (key, userType) {
//            toggle show and hide 
            $("#" + userType + " h3 span").click(function () {
                $("#" + userType + " ul").toggle('slow');
                $(this).toggleClass('folded');
            });//$("#" + userType + " h3 span").click(function ()

//          select by category
            $("#" + userType + " h3 input").click(function () {
                $("#" + userType + " ul li label").removeClass('checked');
                if ( $("#" + userType + "  h3 input").is(':checked') ) {
                    $("#" + userType + "  ul li input").prop('checked', true);
                    $("#" + userType + " ul li label").addClass('checked');
                }//if ($(this).is(":checked"))
                else {
                    $("#" + userType + " ul li input").prop('checked', false);
                    $("#" + userType + " ul li label").removeClass('checked');
                }
            });// $("#" + userType + " h3 input").click(function ()
            
            $("#" + userType + " ul li").click(function(){
                $(this).children('label').removeClass('checked');
                if($(this).children('input').is(':checked')){
                    $(this).children().addClass('checked');
                }//if($("#" + userType + " ul li input").is(':checked'))
                else{
                    $(this).children().removeClass('checked');
                }
            });//$("#" + userType + " ul li input").click(function()
            
        });//$.each(userTypes, function (key, userType)
        
//        search functionality
        $("#userSearch").keyup(function(){
            if ($(this).val().length >= 3) {
                $("#allArea h3").hide();
                
                var allUsers = $("#allArea li label");
                
                var reg = new RegExp($(this).val());
                
                $.each(allUsers, function (key, user){
                    if (reg.test($(user).text())) {
                        $(user).parent().show();
                    }//if (reg.test(user.text()))
                    else{
                        $(user).parent().hide();
                    }
                });//$.each(allUsers, function (key, user)
            }//if ($(this).val().length >= 3)
            else{
                $("#allArea h3").show();
                $("#allArea li").show();
            }
        });//$("#userSearch").keyup(function()
    });//jQuery("document").ready(function ()

</script>
@stop