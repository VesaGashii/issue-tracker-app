@extends('layouts.app')

@section('title', 'Edit project')

@section('content')
    <div class="mx-auto max-w-3xl">
        <p class="text-sm font-semibold uppercase tracking-wider text-indigo-600">Projects</p>
        <h1 class="mt-1 text-3xl font-bold tracking-tight">Edit {{ $project->name }}</h1>

        <form method="POST" action="{{ route('projects.update', $project) }}" class="mt-8 space-y-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @csrf
            @method('PUT')
            @include('projects._form', ['submitLabel' => 'Save changes'])
        </form>
    </div>
@endsection
