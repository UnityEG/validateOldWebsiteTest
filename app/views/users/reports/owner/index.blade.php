<?php extract($data); ?>

@extends('layouts.main')

@section('main')

<h1>Owner Reports</h1>

{{link_to_route('reports.owner.sales', 'Sales')}}


@stop

