@extends('v_super_user.layout.app')

@section('content')


@endsection

@section('js')
<!-- ChartJS -->
<script src="{{ asset('assets/plugins/chart.js/Chart.min.js')}}"></script>
<script>
    let otp = Math.floor(Math.random() * (999 - 100) ) + 100;
    var settings = {
  "url": "https://graph.facebook.com/v13.0/107726235414608/messages",
  "method": "POST",
  "timeout": 0,
  "headers": {
    "Content-Type": "application/json",
    "Authorization": "Bearer EAAG44TMKJSwBAIFuNSjfSrueZBGr75Lio47HtRXWdep3ewb9XZBAZCFi1ZCVoJwz2LP9S2Le10gkka3iQdTxJTwOWKm5iSItowvX3wP2pkI3FIiD53gr9Dx2ZBQFU4ZAFvavZCPHIi9gun7wV3SME9ZBkfEIUdYqnb9pzTrlXXS1SunEqJmoCvi0ZCTo596i9tBxgpEw2kaOPZAQZDZD"
  },
  "data": JSON.stringify({
    "messaging_product": "whatsapp",
    "to": "6281377345777",
    "type": "template",
    "template": {
      "name": "tes_otp",
      "language": {
        "code": "id"
      },
      "components": [
        {
          "type": "body",
          "parameters": [
            {
              "type": "text",
              "text": otp
            }
          ]
        }
      ]
    }
  }),
};

$.ajax(settings).done(function (response) {
  console.log(response);
});
</script>
@endsection
