@extends('layout')
@section('content')
    @if (session('success'))
        <div class="col-sm-12">
            <div class="alert  alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
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
            <div class="input-group">
                <div class="custom-file">
                    <input name="file" type="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="headerRow" name="is_conatains_header_row" value="1">
            <label class="form-check-label" for="exampleCheck1">File contains header row?</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
