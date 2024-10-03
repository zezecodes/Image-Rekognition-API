<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Posts
        </h2>
    </x-slot>

    <div class="py-12">
        <div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <form actions={{ route('posts.store') }} method="post" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="title">Post Table</label>
                <input type="text" id='title' name='title'>
            </div>
            <div>
                <label for="image">Post Image (Optional)</label>
                <input type="file" id='image' name='image'>
            </div>
            <div>
                <label for="description">Post Description (Optional)</label>
                <textarea name="description" id="description" cols="30" rows="10"></textarea>
            </div>
            <div>
                <button type="submit">Submit Post</button>
            </div>
          </form>
        </div>
    </div>
</x-app-layout>
