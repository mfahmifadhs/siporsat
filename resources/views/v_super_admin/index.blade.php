@extends('v_super_admin.layout.app')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-3 form-group">
                <a href="{{ url('super-admin/oldat/dashboard') }}">
                    <div class="card bg-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-laptop fa-3x"></i>
                            <h6 class="font-weight-bold mt-4">PEMELIHARAAN <br> OLAH DATA & MEUBELAIR</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 form-group">
                <a href="#">
                    <div class="card bg-primary ">
                        <div class="card-body text-black text-center">
                            <i class="fas fa-car fa-3x"></i>
                            <h6 class="font-weight-bold mt-4">PEMELIHARAAN <br> ALAT ANGKUTAN DARAT BERMOTOR </h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 form-group">
                <a href="#">
                    <div class="card bg-primary ">
                        <div class="card-body text-black text-center">
                            <i class="fas fa-pencil-ruler fa-3x"></i>
                            <h6 class="font-weight-bold mt-4">PENGELOLAAN <br> ALAT TULIS KANTOR</h6>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 form-group">
                <a href="#">
                    <div class="card bg-primary ">
                        <div class="card-body text-black text-center">
                            <i class="fas fa-building fa-3x"></i>
                            <h6 class="font-weight-bold mt-4">PEMELIHARAAN <br> GEDUNG DAN BANGUNAN</h6>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>


@endsection
