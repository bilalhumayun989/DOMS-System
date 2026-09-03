@extends('layouts.app')
@php $pageTitle = 'New Return Claim'; @endphp

@section('content')
<div class="mb-5"><a href="{{ route('returns.index') }}" class="text-sm font-bold text-blue-600">&larr; Back to Return Claims</a><h2 class="mt-2 text-2xl font-black text-slate-900">New Return Claim</h2><p class="mt-1 text-sm text-slate-500">Create a return record for review and distributor settlement.</p></div>
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">@include('returns._form')</div>
@endsection
