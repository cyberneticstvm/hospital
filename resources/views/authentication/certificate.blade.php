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
  <div class="row">
    <div class="col-md-12 text-center">
        <img src="{{ public_path().'/images/assets/Devi-Logo-Transparent.jpg }}" width="10%" class="img-fluid"/>
        <p>{{ $branch->address }}, {{ $branch->contact_number }}</p>
    </div>
    <div class="col-md-4"></div>
    <div class="col-md-4">
        <h5>Patient Details</h5>
        Patient Name: {{ $patient->patient_name }}<br/>
        Patient Address: {{ $patient->address }}<br/>
        <h5 class="mt-5">Doctor Details</h5>        
        Doctor Name: {{ $doctor->doctor_name }}<br/>
        Designation: {{ $doctor->designation }}<br/>
        Registration No.: {{ $doctor->reg_no }}<br/>
        <h5 class="mt-5">Other Details</h5>
        Issued Date & time: {{ $details->created_at }}<br/>
    </div>
    <div class="col-md-4"></div>
    <div class="col-md-12 mt-10 text-center">
        @if($details->created_at)
            <img src="{{ public_path().'/images/assets/verified.png' }}" width="10%" class="img-fluid"/>
        @endif
    </div>
    <div class="col mt-10 text-center">
        <p class='text-medium'>VARKALA | PARIPPALLY | POTHENCODE | PARAVOOR | CHIRAYINKEEZHU | KADAKKAL | ATTINGAL | OONNINMOODU | EDAVA | NADAYARA</p>
        <p class="text-small">Ph: +91 9388611622</p>
    </div>
  </div>
</div>

</body>
</html>