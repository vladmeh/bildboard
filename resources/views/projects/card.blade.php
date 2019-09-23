<div class="card flex flex-col h-full">
    <h3 class="font-normal text-xl py-4 mb-3 -ml-5 border-l-4 border-blue-500 pl-4">
        <a href="{{ $project->path() }}" class="text-black no-underline">{{ $project->title }}</a>
    </h3>
    <div class="text-gray-500 mb-4 flex-1">{{ Illuminate\Support\Str::limit($project->description, 100) }}</div>

    @can('manage', $project)
        <footer>
            <form method="post" action="{{ $project->path() }}" class="text-right">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs">Delete</button>
            </form>
        </footer>
    @endcan
</div>
