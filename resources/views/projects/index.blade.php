@extends('layouts.app')

@section('content')
    <header class="flex items-end mb-4 py-4">
        <div class="flex justify-between items-end w-full">
            <h3 class="text-gray font-normal text-lg ">Projects</h3>
            <a class="button" href="/projects/create">New Project</a>
        </div>
    </header>

    <div class="flex  w-full flex-wrap  -mx-3">
        @forelse($projects as $project)
            <div class="lg:w-1/3 w-full mx-auto  px-3 pb-6 ">
                <a href="{{  $project->path() }}">
                    @include('projects.card')
                </a>
                
            </div>
        @empty
            <div class="font-normal text-lg text-gray-500">No projects yet</div>
        @endforelse
    </div>
    
@endsection