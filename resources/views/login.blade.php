@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="limWidth">
            @if($errors->any())
              <div class="alert alert-danger">
                {{ $errors->first() }}
              </div>
            @endif

            <form class="form-horizontal" role="form" method="POST" action="{{ url('/'.$site->slug.'/login') }}">
                {{ csrf_field() }}

              <div class="login">
                <h1>Login</h1>
                <div class="form-group">
                  <input placeholder="Durham Username" id="email" type="text" class="form-control" name="user" value="{{ session('user') }}" required autofocus>
                </div>

                <div class="form-group">
                  <input placeholder="Password" id="password" type="password" class="form-control" name="password" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Login
                </button>

              </div>
            </form>
    </div>
@endsection
