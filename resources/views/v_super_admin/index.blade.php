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
            <div class="col-lg-6 form-group">
                <a href="{{ url('super-admin/oldat/dashboard') }}">
                    <div class="card card-main bg-primary ">
                        <div class="card-body text-black text-center">
                            <h3 class="font-weight-bold">PENGELOLAAN BMN <br> OLAH DATA <br> (OLDAT)</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 form-group">
                <a href="#">
                    <div class="card card-main bg-primary ">
                        <div class="card-body text-black text-center">
                            <h3 class="font-weight-bold">PENGELOLAAN BMN <br> ALAT ANGKUTAN DARAT BERMOTOR <br> (AADT)</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 form-group">
                <a href="#">
                    <div class="card card-main bg-primary ">
                        <div class="card-body text-black text-center">
                            <h3 class="font-weight-bold">PENGELOLAAN <br> ALAT TULIS KANTOR <br> (ATK)</h3>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 form-group">
                <a href="#">
                    <div class="card card-main bg-primary ">
                        <div class="card-body text-black text-center">
                            <h3 class="font-weight-bold" style="margin-top: 40px;">PENGELOLAAN PEMELIHARAAN</h3>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>


@endsection
