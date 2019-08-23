@extends('layouts.app')

@section('content')
<header class="flex  mb-4 py-3">
        <div class="flex justify-between items-end w-full">
                <div class="text-gray-500 text-lg font-normal">
                        <a href="/projects" class="text-gray-500 text-lg font-normal">
                                @if(auth()->user() == $project->owner)
                                        My Project
                                @else
                                        {{$project->owner->name}} project
                                @endif
                        </a>
                        / {{ $project->title}}
                </div>
                <div class="flex items-center ">   
                        @if(count($project->members) > 0)
                                @foreach($project->members as $member)
                                        <img src="{{ gravatarUrl($member->email)}} " 
                                        alt="{{$member->name}}" class="rounded-full w-8 mr-4">                                   
                                @endforeach                                
                        @endif
                        
                        <img src="{{ gravatarUrl($project->owner->email)}} " 
                        alt="{{$project->owner->name}}" class="rounded-full w-8 mr-4">   
                        <a class="button"  href="{{$project->path().'/edit'}}">Edit Project</a>
                </div>
        </div>
</header>

        <main class="w-full">
                <div class="lg:flex -mx-3   ">
                        <div class="lg:w-3/4 px-3 mb-6 " >
                                <div class="mb-8">
                                        <h3 class="text-gray-500 text-lg font-normal mb-3">Tasks</h3>
                                        @foreach($project->tasks as $task)
                                                <div class="card mb-3">
                                                        <form action="{{$task->path()}}" method="POST" >
                                                                @method('patch')
                                                                @csrf
                                                                <div class="flex">
                                                                        <input class="w-full {{$task->completed ? 'text-gray-600' : ''}}" name="body" value="{{ $task->body }}">
                                                                        <input type="checkbox" name="completed" onchange="this.form.submit()" {{$task->completed ? 'checked' : ''}}>
                                                                </div>
                                                        </form>
                                                        
                                                </div>
                                        @endforeach    
                                        <div class="card mb-3 ">
                                                <form action="{{ $project->path().'/tasks' }}" method="post">
                                                        @csrf
                                                        <input type="text" class="w-full" name="body" placeholder="Add a new Task">
                                                </form>
                                        </div>    
                                </div>
                                <div>
                                        <h3 class="text-gray-500 text-lg font-normal">General Notes</h3>

                                        <form action="{{$project->path()}}" method="POST">
                                                @method('patch')
                                                @csrf
                                                <textarea name="notes" class="card w-full text-lg mb-6" style="min-height: 200px " placeholder="General Notes">{{$project->notes}}</textarea>
                                                <input type="submit" value="Save" class="button cursor-pointer" >
                                        </form>
                                        @include('projects.errors')
                                </div>


                        </div>
                        <div class="lg:w-1/4 px-3">
                                @include('projects.card')
                                @include('projects.activity.card')

                                @can('manage', $project)
                                        @include('projects.invite')
                                @endcan
                       </div>
                </div>
        </main>
  
@endsection