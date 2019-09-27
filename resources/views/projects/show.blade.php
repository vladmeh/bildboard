@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-3 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-muted text-sm font-light">
                <a href="{{ url('/projects') }}" class="text-default text-sm font-normal no-underline">My Projects</a>
                / {{ $project->title }}
            </p>
            <div class="flex justify-between items-center">
                @foreach($project->members as $member)
                    <img
                            src="{{ gravatar_url($member->email) }}"
                            alt="{{ $member->name }}`s avatar"
                            class="rounded-full mr-2 w-8"
                    >
                @endforeach
                <img
                        src="{{ gravatar_url($project->owner->email) }}"
                        alt="{{ $project->owner->name }}`s avatar"
                        class="rounded-full mr-2 w-8"
                >
                <a href="{{url($project->path() . '/edit')}}" class="btn-blue ml-6">Edit Project</a>
            </div>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <h2 class="text-default text-lg font-normal mb-3">Tasks</h2>
                    {{-- tasks --}}
                    @foreach ($project->tasks as $task)
                        <div class="card mb-3">
                            <form action="{{ $task->path() }}" method="post">
                                @method('PATCH')
                                @csrf
                                <div class="flex items-center">
                                    <input type="text" value="{{ $task->body }}" name="body"
                                           class="bg-card text-default w-full {{$task->completed ? 'text-default line-through' : ''}}">
                                    <input type="checkbox" name="completed"
                                           onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                                </div>
                            </form>
                        </div>
                    @endforeach
                    <div class="card mb-3">
                        <form action="{{ $project->path() . '/tasks' }}" method="post">
                            @csrf
                            <input type="text" placeholder="Add a new task..."
                                   class="bg-card text-default w-full" name="body">
                        </form>
                    </div>
                </div>
                <div>
                    <h2 class="text-default text-lg font-normal mb-3">General Notes</h2>
                    {{-- general notes --}}
                    <form action="{{ $project->path() }}" method="post">
                        @method('PATCH')
                        @csrf
                        <textarea name="notes"
                                  class="card w-full mb-4"
                                  style="min-height: 200px;"
                                  placeholder="Anything special that you want to make a note of?"
                        >{{ $project->notes }}</textarea>

                        <button type="submit" class="btn-blue">Save</button>
                    </form>
                    @include('errors')
                </div>
            </div>
            <div class="lg:w-1/4 px-3">
                <div class="mt-10">
                    @include('projects.card')
                </div>
                @include('projects.activity.card')

                @can ('manage', $project)
                    @include('projects.invite')
                @endcan
            </div>
        </div>
    </main>


@endsection
