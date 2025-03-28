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
                            <h5 class="card-title text-center">Confirmation</h5>
                            @if (Session::has('error'))
                            <div class="text-danger text-center mt-2">
                                <h5>{{ Session::get('error') }}</h5>
                            </div>
                            @endif
                            @if (Session::has('success'))
                            <div class="text-success text-center mt-2">
                                <h5>{{ Session::get('success') }}</h5>
                            </div>
                            @endif

                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('schedule.appointment') }}" class="text-info">Back to Home</a>
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