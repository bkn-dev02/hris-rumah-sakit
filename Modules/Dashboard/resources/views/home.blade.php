@extends('shared::layouts.app')

@section('title', 'Dashboard')

@section('content')
@foreach ($sections as $key => $data)
@include($sectionViews[$key], $data)
@endforeach
@endsection