@extends("templates.base")
@section("userregistration")
<form method="post">
    @csrf
    <input type="text" name="name" placeholder="Full Name" /><br/>
    <input type="text" name="username" placeholder="Username" /><br/>
    <input type="email" name="email" placeholder="Email" /><br/>
    <input type="password" name="password" placeholder="Password" /><br/>
    <button type="submit">Register</button>
</form>
@endsection