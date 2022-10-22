<!DOCTYPE html>
<html>
<head>
    <title>Devi Eye Clinic & Opticians</title>
    <style>
        .text-blue{
            color: blue;
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
        td,th{
            padding: 5px;
        }
        th{
            text-align: left;
        }
    </style>
</head>
<body>
    <center>
    <img src="./images/assets/Devi-Logo-Transparent.jpg" height='75' width='115'/>
    <p>Camp ID: {{ $campm->camp_id }}</p>
    <p>{{ $campm->venue }}, {{ $campm->address }}</p>
    <p>Branch: {{ $branch->branch_name }}, {{ $branch->address }}, {{ $branch->contact_number }}</p>
    </center>
    <table width="100%" cellpadding='0' cellspacing='0' class="text-large">
        <thead><tr><th>Patient Name</th><th>Age/Gender</th><th>Vision</th></tr></thead><tbody>
            @forelse($camps as $key => $c)
            <tr>
                <td>{{ $c->patient_name }}</td>
                <td>{{ $c->age }} / {{ $c->gender }}</td>
                <td>
                    <table>
                        <tr><td>Re</td><td>{{ $c->re_vb }}</td><td>{{ $c->re_sph }}</td><td>{{ $c->re_cyl }}</td><td>{{ $c->re_axis }}</td><td>{{ $c->re_add }}</td><td>{{ $c->re_va }}</td></tr>
                        <tr><td>Le</td><td>{{ $c->le_vb }}</td><td>{{ $c->le_sph }}</td><td>{{ $c->le_cyl }}</td><td>{{ $c->le_axis }}</td><td>{{ $c->le_add }}</td><td>{{ $c->le_va }}</td></tr>
                    </table>
                </td>
            </tr>
            @empty
            @endforelse
        </tbody>
    </table>
</body>
</html>