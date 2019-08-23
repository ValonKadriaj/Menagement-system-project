@if($errors->{ $bag ?? 'default'}->any())
    <ul class="mt-6">
        @foreach($errors->{ $bag ?? 'default'}->all() as $error)
            <li class="text-lg text-red-500">
                {{$error}}
            </li>
        @endforeach
    </ul>
@endif