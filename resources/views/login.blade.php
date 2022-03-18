<!doctype html>
<html class="no-js " lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Bootstrap 5 admin template and web Application ui kit.">
    <meta name="keyword" content="ALUI, Bootstrap 5, ReactJs, Angular, Laravel, VueJs, ASP .Net, Admin Dashboard, Admin Theme">
    <title>:: ALUI ::</title>
    <link rel="icon" href="/images/favicon.ico" type="image/x-icon"> <!-- Favicon-->

    <!-- project css file  -->
    <link rel="stylesheet" href="/css/al.style.min.css">
    <!-- project layout css file -->
    <link rel="stylesheet" href="/css/layout.a.min.css">
</head>

<body>

<div id="layout-a" class="theme-cyan">

    <!-- main body area -->
    <div class="main auth-div7 p-2 py-3 p-xl-5">
        
        <!-- Body: Body -->
        <div class="body d-flex p-0 p-xl-5">
            <div class="container-fluid">

                <div class="row g-0 justify-content-center">
                    <div class="col-xl-5 col-lg-7 col-md-8 col-sm-8">
                       <!-- Form -->
                       <form class="row g-0" method="post">
                           @csrf
                            <div class="col-12 text-center mb-5 text-light">
                                <h1 class="fw-bold">Sign in</h1>
                                <span class="fs-5">Devi Eye Hospitals.</span>
                            </div>
                            <div class="col-12">
                                <div class="input-group justify-content-center">
                                    <div class="form-floating">
                                        <input type="text" class="form-control rounded-start rounded-0" name="username" placeholder="Username">
                                        <label>Username</label>
                                    </div>
                                    <div class="form-floating">
                                        <input type="password" class="form-control rounded-0" name="password" placeholder="Password">
                                        <label>Password</label>
                                    </div>
                                    <button type="submit" class="btn btn-lg btn-primary text-uppercase rounded-end rounded-0">SIGN IN</button>
                                </div>
                            </div>
                            <div class="col-12 text-center mt-4">
                                <span class="text-light">Don't have an account yet? <a class="text-primary" href="/dash">Sign up here</a></span>
                            </div>
                        </form>
                        <!-- End Form -->
                    </div>
                </div> <!-- End Row -->
                
            </div>
        </div>

        <!-- partical animation -->
        <div class="animation-wrapper">
            <div class="particle particle-1"></div>
            <div class="particle particle-2"></div>
            <div class="particle particle-3"></div>
            <div class="particle particle-4"></div>
        </div>

    </div>

    <!-- Modal: Setting -->
    <div class="modal fade" id="SettingsModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title">AL-UI Setting</h5>
                    </div>
                    <div class="modal-body custom_scroll">
                    <!-- Settings: Font -->
                    <div class="setting-font">
                        <small class="card-title text-muted">Google font Settings</small>
                        <ul class="list-group font_setting mb-3 mt-1">
                            <li class="list-group-item py-1 px-2">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="radio" name="font" id="font-opensans" value="font-opensans" checked="">
                                    <label class="form-check-label" for="font-opensans">
                                        Open Sans Google Font
                                    </label>
                                </div>
                            </li>
                            <li class="list-group-item py-1 px-2">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="radio" name="font" id="font-quicksand" value="font-quicksand">
                                    <label class="form-check-label" for="font-quicksand">
                                        Quicksand Google Font
                                    </label>
                                </div>
                            </li>
                            <li class="list-group-item py-1 px-2">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="radio" name="font" id="font-nunito" value="font-nunito">
                                    <label class="form-check-label" for="font-nunito">
                                        Nunito Google Font
                                    </label>
                                </div>
                            </li>
                            <li class="list-group-item py-1 px-2">
                                <div class="form-check mb-0">
                                    <input class="form-check-input" type="radio" name="font" id="font-Raleway" value="font-raleway">
                                    <label class="form-check-label" for="font-Raleway">
                                        Raleway Google Font
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <!-- Settings: Color -->
                    <div class="setting-theme">
                        <small class="card-title text-muted">Theme Color Settings</small>
                        <ul class="list-unstyled d-flex justify-content-between choose-skin mb-2 mt-1">
                            <li data-theme="indigo"><div class="indigo"></div></li>
                            <li data-theme="blue"><div class="blue"></div></li>
                            <li data-theme="cyan" class="active"><div class="cyan"></div></li>
                            <li data-theme="green"><div class="green"></div></li>
                            <li data-theme="orange"><div class="orange"></div></li>
                            <li data-theme="blush"><div class="blush"></div></li>
                            <li data-theme="red"><div class="red"></div></li>
                            <li data-theme="dynamic"><div class="dynamic"><i class="fa fa-paint-brush"></i></div></li>
                        </ul>
                    </div>
                    <!-- Settings: Theme dynamics -->
                    <div class="dt-setting">
                        <small class="card-title text-muted">Dynamic Color Settings</small>
                        <ul class="list-group list-unstyled mb-3 mt-1">
                            <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2">
                                <label>Primary Color</label>
                                <button id="primaryColorPicker" class="btn bg-primary avatar xs border-0 rounded-0"></button>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-1 px-2">
                                <label>Secondary Color</label>
                                <button id="secondaryColorPicker" class="btn bg-secondary avatar xs border-0 rounded-0"></button>
                            </li>
                        </ul>
                    </div>
                    <!-- Settings: Light/dark -->
                    <div class="setting-mode">
                        <small class="card-title text-muted">Light/Dark & Contrast Layout</small>
                        <ul class="list-group list-unstyled mb-0 mt-1">
                            <li class="list-group-item d-flex align-items-center py-1 px-2">
                                <div class="form-check form-switch theme-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="theme-switch">
                                    <label class="form-check-label" for="theme-switch">Enable Dark Mode!</label>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center py-1 px-2">
                                <div class="form-check form-switch theme-high-contrast mb-0">
                                    <input class="form-check-input" type="checkbox" id="theme-high-contrast">
                                    <label class="form-check-label" for="theme-high-contrast">Enable High Contrast</label>
                                </div>
                            </li>
                            <li class="list-group-item d-flex align-items-center py-1 px-2">
                                <div class="form-check form-switch theme-rtl mb-0">
                                    <input class="form-check-input" type="checkbox" id="theme-rtl">
                                    <label class="form-check-label" for="theme-rtl">Enable RTL Mode!</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-start text-center">
                    <button type="button" class="btn flex-fill btn-primary lift">Save Changes</button>
                    <button type="button" class="btn flex-fill btn-white border lift" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <a class="page-setting" href="#" title="Settings" data-bs-toggle="modal" data-bs-target="#SettingsModal"><i class="fa fa-gear"></i></a>

</div>

<!-- Jquery Core Js -->
<script src="/bundles/libscripts.bundle.js"></script>

<!-- Jquery Page Js -->
<script src="/js/template.js"></script>

</body>
</html>