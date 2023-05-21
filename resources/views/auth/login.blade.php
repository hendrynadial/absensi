<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login | Admin YPSIM</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin YPSIM" name="description" />
    <meta content="Themesdesign" name="author" />
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <link href="{!! asset('/assets/css/bootstrap.min.css') !!}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="{!! asset('/assets/css/icons.min.css') !!}" rel="stylesheet" type="text/css" />
    <link href="{!! asset('/assets/css/app.min.css') !!}" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body class="authentication-bg bg-success">
    <div class="home-center">
        <div class="home-desc-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="px-2 py-3">
                                    <div class="text-center">
                                        <a href="#">
                                            <img src="{!! asset('assets/images/logo-ypsim.jpg')!!}" height="170" alt="logo">
                                        </a>
                                        <h5 class="mb-2 mt-4"><small> Yayasan Perguruan Sultan Iskandar Muda </small></h5>
                                    </div>

                                    <form class="form-horizontal mt-4 pt-2" id="login-form">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="Email">Email</label>
                                            <input type="email" required name="email" class="form-control" id="email" placeholder="Masukan Email">
                                        </div>

                                        <div class="mb-3">
                                            <label for="password">Kata Sandi</label>
                                            <input type="password" required name="password" class="form-control" id="password" placeholder="Masukan Kata Sandi">
                                        </div>

                                        <div>
                                            <button class="btn btn-success w-100 waves-effect waves-light" type="submit">Log In</button>
                                        </div>

                                        <div class="mt-4 text-center">
                                            <a href="#" class="text-muted">
                                                <i class="mdi mdi-lock me-1"></i> Lupa Kata Sandi?
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Log In page -->
    </div>
    <!-- JAVASCRIPT -->
    <script src="{!! asset('assets/libs/jquery/jquery.min.js')!!}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>

<script>
$("#login-form").submit(function(event) {
    event.preventDefault();
    event.stopImmediatePropagation();
    var formData = new FormData($(this)[0]);
    $.ajax({
        url: '{!! url("/login") !!}',
        type: "POST",
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(e) {
            if (e.status == 1) {
                Swal.fire({
                    icon: 'success', 
                    title: 'Berhasil', 
                    text: e.message, 
                    timer: 1000, 
                })
                location.href = "/";
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Opss...',
                    text: e.message, 
                    timer: 1500, 
                })
            }
        }
    });
});
</script>

