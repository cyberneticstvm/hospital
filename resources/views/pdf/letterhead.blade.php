<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        @font-face {
            font-family: 'malayalam';
            src: url("{{ storage_path('/fonts/TholikaTraditionalUnicode.ttf') }}") format("truetype");
        }
        body{
            background-image:url('./storage/assets/images/letter-head.jpeg');
            width:100%;
            background-size: cover;
        }
        .text-blue{
            color: blue;
        }
        table{
            border-bottom: 1px solid #000;
        }
        .text-medium{
            font-size: 12px;
        }
        .text-small{
            font-size: 10px;
        }
        .text-large{
            font-size: 15px;
        }
        .from{
            margin-top: 20%;
            margin-left: 6%;
        }
        .to{
            margin-top: 1%;
            margin-left: 6%;
        }
        .subject{
            margin-top: 2%; 
            margin-bottom: 2%;
            text-decoration: underline;
        }
        .matter{
            /*text-align:justify;*/
            margin-left: 6%;
            margin-right:2%;
            font-family: 'malayalam';
        }
        .text-end{
            margin-left: 75%;
        }
    </style>
</head>
<body>
    <div class="from">From: <span class="text-end">{{ date('d/M/Y', strtotime($matter->date)) }}</span><br/>{!! nl2br($matter->from) !!} </div>
    <div class="to">To: <br/>{!! nl2br($matter->to) !!}</div>
    <center class="subject">{{ $matter->subject }}</center>
    <div class="matter">{!! nl2br($matter->matter) !!}</div>
</body>
</html>