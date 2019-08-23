
    @csrf
   

    <div class="field mb-6">
        <label class="label text-sm mb-2 block" for="title">Title</label>
        <div class="control">
            <input 
                type="text"
                class="input bg-transparent outline-none border border-gray-500 rounded p-2 text-xs w-full"
                name="title"
                placeholder="title"
                value="{{ $project->title }}"
                required 
            >
        </div>
    </div>
    <div class="field mb-6">
        <label class="label text-sm mb-2 block" for="description">Description</label>
        <div class="form-control">
            <textarea 
                type="text"
                class="input bg-transparent outline-none border border-gray-500 rounded p-2 text-xs w-full"
                name="description"
                placeholder="Description" 
                required >{{$project->description}}</textarea
            >
        </div>
    </div>

    <div>
        <div class="form-control">
            <button type="submit" class="button  mr-2">{{ $buttonText }}</button>
            <a href="{{$project->path()}}" class="button-2">Cancel</a>
        </div>
    </div>
    @include('projects.errors')
