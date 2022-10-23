@extends('v_super_user.layout.app')

@section('content')

<div class="container">
    <div class="row justify-content-center align-items-center h-70">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading font-weight-bold">Set up Google Authenticator</div>
                <hr>

                <div class="panel-body" style="text-align: center;">
                    <p>Set up your two factor authentication by scanning the barcode below with you Google Authenticator app. Alternatively, you can use the code <strong> {{ $secretkey }}</strong> </p>
                    @if (Auth::user()->secret_key == null)
                    <div>
                        {!! $QR_Image !!}
                    </div>
                    @endif

                    <p>Take note of the <strong>OTP</strong> generated on your Authenticator App.You will be unable to login otherwise </p>
                    <div>
                        <a href="/complete-registration"><button class="btn-primary">Finish Registration</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
