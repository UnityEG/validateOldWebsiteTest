<?php extract($data); ?>
@extends('layouts.main')

@section('main')

<!--sales and validations reports-->
<div class="row">
    <!--sales report-->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        @include('users.reports.owner.sales')
    </div>
    <!--validations report-->
    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"></div>
</div>

<div class="row"></div>






@stop