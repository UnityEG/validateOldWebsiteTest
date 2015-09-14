@extends('layouts.main')
@section('main')	
<style>
.form-group{
margin-bottom: 0px;
}
</style>
@if ($errors->any())
    <ul>{{ implode('', $errors->all('<li class="error">:message</li>')) }}</ul>
@endif		

<?php if(isset($isSent)){
		if($isSent=='yes'){
 ?>
<div class="alert alert-success" role="alert">Message Sent Successfully</div>
<?php }} ?>
<br />
<h3> Contact Us </h3>
{{ Form::open(array('route' => 'ContactController.index', 'role' => 'form')) }}

        <div  class="form-group">
            <label for="inputName">Name:*</label>

            <input  type="text" class="form-control" id="inputName" name="inputName"   placeholder="Name" >

        </div>

        <div  class="form-group">

            <label for="inputEmail">Email:*</label>

            <input type="text" class="form-control" id="inputEmail" name="inputEmail"  placeholder="Email" >

        </div>
		<div  class="form-group">

            <label for="inputEmail">Phone:</label>

            <input type="text" class="form-control" id="inputPhone" name="inputPhone"  placeholder="Phone">

        </div>
		<div class="form-group">

            <label for="inputEmail">Message:*</label>

            <textarea class="form-control" id="inputMessage" rows="10" name="inputMessage" placeholder="Message" style="width:50%;"></textarea>

        </div>


        <input value="Send" type="submit" name="contactSubmit" class="btn btn-primary" />

    </form>
   
@stop