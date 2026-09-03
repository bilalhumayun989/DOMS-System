@extends('layouts.app')
@php $pageTitle = 'Edit '.$claim['return_ref']; @endphp

@section('content')
<div class="mb-5"><a href="{{ route('returns.show', $claim['id']) }}" class="text-sm font-bold text-blue-600">&larr; Back to Return Details</a><h2 class="mt-2 text-2xl font-black text-slate-900">Edit {{ $claim['return_ref'] }}</h2><p class="mt-1 text-sm text-slate-500">Update the return claim and save the revised record.</p></div>
<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">@include('returns._form')</div>
@endsection
