<!DOCTYPE html>
<html lang="en">
<head>
  <title>Certificate Authentication - Devi Eye Hospitals</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    body{
        background-color: #fff;
    }
    .container{
        background-color: #fff;
    }
    .mt-10{
        margin-top: 10%;
    }
  </style>
</head>
<body>

<div class="container">
@if($details && $details->created_at)
  <div class="row">
    <div class="col-md-12 text-center">
        <img src="{{ public_path().'/images/assets/Devi-Logo-Transparent.jpg' }}" width="10%" class="img-fluid"/>
        <!--<p>{{ $branch->address }}, {{ $branch->contact_number }}</p>-->
    </div>
    <div class="col-md-4 mt-5"></div>
    <div class="col-md-4 mt-5">
        <h5>Issued to</h5>
        Name: {{ $patient->patient_name }}<br/>
        Address: {{ $patient->address }}<br/><br/>
        
        Issued Certificate(s): {{ $certs }}<br/>
        Issued Date & Time: {{ $details->created_at }}<br/>
        Purpose of Certificate(s): License<br/>
        <h5 class="mt-5">Doctor Details</h5>        
        Name: {{ $doctor->doctor_name }}<br/>
        Designation: {{ $doctor->designation }}<br/>
        Registration No.: {{ $doctor->reg_no }}

    </div>
    <div class="col-md-4 mt-5"></div>
    <div class="col-md-12 mt-10 text-center">        
            <img src="{{ public_path().'/images/assets/verified.png' }}" width="10%" class="img-fluid"/>        
    </div>
  </div>
@else
    <div class="row"><div class="col text-center text-danger mt-10">Certificate yet to be issued.</div></div>
@endif
</div>

</body>
</html>