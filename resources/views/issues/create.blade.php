@extends('layouts.app')

@section('title', 'Create issue')

@section('content')
    <div class="mx-auto max-w-3xl">
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Issues</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight">Create an issue</h1>

        <form method="POST" action="{{ route('issues.store') }}" class="mt-8 space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            @include('issues._form', ['issue' => null, 'submitLabel' => 'Create issue'])
        </form>
    </div>
@endsection
