<html>
    <head>
        <title>
            SMS API Test
        </title>
    </head>
    <body>
        @if(session()->has('success'))
            <div class="alert alert-success">
                {{ session()->get('success') }}
            </div>
        @endif
        <form method="post" action="{{ route('testsmsapisend') }}">
            @csrf
            <input type="text" maxlength="10" placeholder="Mobile Number" name="mobile"/>
            <input type="text" maxlength="5" placeholder="OTP" name="otp"/>
            <button type="submit">Send SMS</button>
        </form>
    </body>
</html>