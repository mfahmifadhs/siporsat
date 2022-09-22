@extends('v_super_user.layout.app')

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
        <div class="row">
            <div class="col-lg-3 col-12 form-group">
                <div class="card bg-primary" style="height: 100%;">
                    <div class="card-body text-center">
                        <p class="font-weight-bold mt-4">
                           <span class="fa-lg"> PENGELOLAAN <br> OLAH DATA BMN <br> (OLDAT)</span>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ url('super-user/oldat/dashboard') }}" class="small-box-footer text-white">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12 form-group">
                <div class="card bg-primary" style="height: 100%;">
                    <div class="card-body text-center">
                        <p class="font-weight-bold mt-4">
                           <span class="fa-lg"> PENGELOLAAN <br> ALAT ANGKUTAN DARAT BERMOTOR (AADB)</span>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ url('super-user/aadb/dashboard') }}" class="small-box-footer text-white">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12 form-group">
                <div class="card bg-primary" style="height: 100%;">
                    <div class="card-body text-center">
                        <p class="font-weight-bold mt-4">
                           <span class="fa-lg"> PENGELOLAAN <br> ALAT TULIS KANTOR <br> (ATK)</span>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="small-box-footer text-white">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-12 form-group">
                <div class="card bg-primary" style="height: 100%;">
                    <div class="card-body text-center">
                        <p class="font-weight-bold mt-4">
                           <span class="fa-lg"> PENGELOLAAN <br> PEMELIHARAAN GEDUNG (GDG)</span>
                        </p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="small-box-footer text-white">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@endsection
