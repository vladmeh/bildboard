<div class="card flex flex-col mt-3">
    <h3 class="font-normal text-xl py-4 mb-3 -ml-5 border-l-4 border-blue-500 pl-4">
        Invite a User
    </h3>
    <form method="post" action="{{ $project->path() . '/invitations' }}">
        @csrf
        <div class="mb-3">
            <input type="email" name="email" class="border border-gray-300 rounded w-full py-2 px-3"
                   placeholder="Email address">
        </div>

        <button type="submit" class="btn-blue">Invite</button>
    </form>
    @include('errors', ['bag' => 'invitations'])
</div>