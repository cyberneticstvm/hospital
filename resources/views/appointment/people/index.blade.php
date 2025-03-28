<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Devi Eye Hospitals - Public Appointment Registration form</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="/images/favicon.ico" rel="icon">
    <link href="/images/favicon.ico" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="/appointment/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/appointment/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="/appointment/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="/appointment/vendor/remixicon/remixicon.css" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="/appointment/css/style.css" rel="stylesheet">

    <!-- =======================================================
  * Template Name: NiceAdmin
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Updated: Apr 20 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>
    <main id="main" class="main">
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Appointment Registration - Devi Eye Hospital</h5>

                            <!-- Horizontal Form -->
                            <form method="POST" action="{{ route('schedule.appointment.update') }}" class="row g-3">
                                @csrf
                                <div class="col-md-12">
                                    <label for="inputName5" class="form-label req">Your Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Your Name" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-6">
                                    <label for="inputName5" class="form-label req">Age</label>
                                    <input type="number" class="form-control" name="age" value="{{ old('age') }}" placeholder="Age" required>
                                </div>
                                <div class="col-6">
                                    <label for="inputName5" class="form-label req">Gender</label>
                                    <select class="form-control" name="gender" required>
                                        <option value="">Select</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="inputName5" class="form-label req">Contact Number</label>
                                    <input type="text" class="form-control" name="contact_number" value="{{ old('contact_number') }}" maxlength="10" placeholder="0123456789" required>
                                    @error('contact_number')
                                    <small class="text-danger">{{ $errors->first('contact_number') }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="inputName5" class="form-label req">Address</label>
                                    <input type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="Address" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="inputName5" class="form-label req">Branch</label>
                                    <select class="form-control" name="branch" required>
                                        <option value="">Select</option>
                                        @forelse($branches as $key => $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->display_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="inputName5" class="form-label req">Appointment Date</label>
                                    <input type="date" class="form-control" name="appointment_date" value="{{ old('appointment_date') }}" min="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="col-6">
                                    <label for="inputName5" class="form-label req">Captcha</label>
                                    <input type="text" class="form-control text-info" name="captcha" value="{{ $rand }}" readonly>
                                </div>
                                <div class="col-6">
                                    <label for="inputName5" class="form-label req">Enter Captcha</label>
                                    <input type="text" class="form-control" name="captcha_val" maxlength="4" value="" required>
                                    @error('captcha_val')
                                    <small class="text-danger">{{ $errors->first('captcha_val') }}</small>
                                    @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </form><!-- End Horizontal Form -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>Devi Eye Hospitals</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
            <a href="https://devieh.com/">Devi Eye Hospitals</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <!-- Template Main JS File -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/appointment/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/appointment/js/main.js"></script>

</body>

</html>