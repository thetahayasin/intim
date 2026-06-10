@extends('associate.main')

@section('title', 'Asif Associates | Associate Profile')

@section('content')

<div class="col-md-12 container-fluid">

    <div class="col-12 col-lg-12 col-xl-12">
        <h2 class="h3 mb-4 page-title">Profile</h2>
        <div class="row">
            <div class="col-md-8">
                <div class="">
                    <div class="list-group mb-5">
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <strong class="mb-2">Full Name</strong>
                                </div>
                                <div class="col-auto">
                                    {{ $data->name ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <strong class="mb-2">Email</strong>
                                    <p class="text-muted mb-0">Contact admin to update</p>
                                </div>
                                <div class="col-auto">
                                    {{ $data->email ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <strong class="mb-2">FTS</strong>
                                </div>
                                <div class="col-auto">
                                    {{ $data->fts ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <strong class="mb-2">CRN</strong>
                                </div>
                                <div class="col-auto">
                                    {{ $data->crn ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <strong class="mb-2">Joining Date</strong>
                                </div>
                                <div class="col-auto">
                                    {{ $data->date_joined ? date('M d, Y', strtotime($data->date_joined)) : 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col">
                                    <strong class="mb-2">Ending Date</strong>
                                </div>
                                <div class="col-auto">
                                    {{ $data->end_date ? date('M d, Y', strtotime($data->end_date)) : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-4">
                <div class="card">
                    <div class="card-body att-body">
                        <h5 class="card-title">Instructions</h5>
                        <ul>
                            <li>In case of any errors contact admin to get your details updated.</li>
                            <li>Profile is read-only, for change of password request admin.</li>
                        </ul>
                        <hr>
                        <div class="ayat text-center">
                            <span class="ayat-text"><b>"it is not the eyes that are blind, but it is the hearts in the chests that grow blind"</b></span>
                            <br>
                            <span class="ref-text"><i>Quran (22:46)</i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection