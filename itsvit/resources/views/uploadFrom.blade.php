@extends('layout')

@section('content')
    @if(!empty($errors->all()))
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="/upload" enctype='multipart/form-data'>
        {{ csrf_field() }}
        <div class="form-group">
            <label for="exampleFormControlFile1">Choose file:</label>
            <input type="file" name="file" class="form-control-file" id="exampleFormControlFile1">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
