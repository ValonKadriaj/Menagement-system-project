@extends('layouts.app')

@section('content')
<div class="lg:w-1/2 lg:mx-auto bg-white p-6 md:py-12 md:px-16 rounded-lg shadow">
    <h1 class="text-3xl font-normal mb-10 text-center">
            Login
    </h1>
    <form   
        action="{{ route('login') }}"
        method="POST"
        >
            
        @csrf

        <div class="field mb-6">
            <label class="label text-sm mb-2 block">Email</label>
            <div class="control">
                <input 
                    type="text"
                    class="input bg-transparent outline-none border border-gray-500 rounded p-2 text-xs w-full"
                    name="email"
                    placeholder="Email"
                    value="{{old('email')}}"
                    required 
                >
            </div>
        </div>
        <div class="field mb-6">
            <label class="label text-sm mb-2 block">Password</label>
            <div class="form-control">
                <input 
                    type="password"
                    class="input bg-transparent outline-none border border-gray-500 rounded p-2 text-xs w-full"
                    name="password"
                    placeholder="Password" 
                    required ></input
                >
            </div>
        </div>

        <div>
            <div class="form-control">
                <button type="submit" class="button  mr-4">Login</button>
                <a href="/register" class="button-2">Register</a>
            </div>
        </div>
    </form>
    @include('projects.errors')
</div>

@endsection


