@extends('layout')

@section('content')
    <div class="card-deck mb-3 text-center">
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Counts</h4>
            </div>
            <div class="card-body">
                <h1 class="card-title pricing-card-title">{{number_format($usersCount)}} <small class="text-muted">/ total</small></h1>
                <ul class="list-unstyled mt-3 mb-4">
                    @foreach ($usersCountByGender as $gender)
                        <li>{{$gender->name}}: {{ number_format($gender->cnt) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
                <h4 class="my-0 font-weight-normal">Average ages</h4>
            </div>
            <div class="card-body">
                <h1 class="card-title pricing-card-title">{{round($avgAge)}} <small class="text-muted">/ all
                        users</small></h1>
                <ul class="list-unstyled mt-3 mb-4">
                    @foreach ($avgAgeByGender as $gender)
                        <li>{{$gender->name}}: {{ round($gender->avgAge) }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Count</th>
                <th scope="col">Country</th>
            </tr>
            </thead>
            <tbody>
            @foreach($usersCountByCountry as $k => $country)
                <tr>
                    <th scope="row">{{$k+1}}</th>
                    <td>{{number_format($country->cnt)}}</td>
                    <td>{{$country->name}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
