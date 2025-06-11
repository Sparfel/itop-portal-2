<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iTop Portal 2</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <style>
        body {
            background: #28b4e6;
            color: #4A4A4A;
            font-family: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            margin: 0;
            overflow-x: hidden;
            --font-family-sans-serif: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            --fa-style-family-brands: "Font Awesome 6 Brands";
            --fa-font-brands: normal 400 1em/1 "Font Awesome 6 Brands";
            --fa-font-regular: normal 400 1em/1 "Font Awesome 6 Free";
            --fa-style-family-classic: "Font Awesome 6 Free";
            --fa-font-solid: normal 900 1em/1 "Font Awesome 6 Free";
        }
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }
        .content-wrapper {
            position: absolute;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
        .header {
            background: #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 100%;
            padding: 5px;
        }
        .header .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 0.5rem;
        }
        @media (min-width: 992px) {
            .header .grid {
                grid-template-columns: 1fr auto 1fr;
            }
            .header .logo {
                grid-column: 2;
                justify-self: center;
            }
            .header .nav {
                grid-column: 3;
                justify-self: end;
            }
        }
        .main-content {
            min-height: calc(100vh - 150px);
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        .left-block {
            background: #ffffff;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }
        .right-block {
            background: #ffffff;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            transition: box-shadow 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .right-block:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .right-block a {
            color: #28b4e6;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }
        .right-block a:hover {
            color: #1a8cc2;
        }
        .right-block i {
            color: #4A4A4A;
            font-size: 2rem;
            transition: color 0.3s ease;
        }
        .right-block:hover i {
            color: #28b4e6;
        }
        .nav-link {
            color: #4A4A4A !important;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: color 0.3s ease, background-color 0.3s ease;
        }
        .nav-link:hover {
            color: #28b4e6 !important;
            background-color: rgba(40, 180, 230, 0.1);
        }
        .nav-link:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(40, 180, 230, 0.5);
        }
        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.1rem;
            font-weight: 300;
            text-align: center;
        }
        .logo img {
            height: 50px;
            margin-right: 0.5rem;
        }
        .btn-custom {
            background-color: #28b4e6;
            color: #fff;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #1a8cc2;
            color: #fff;
        }
        h1, h3, p, li {
            color: #4A4A4A;
        }
        .text-primary {
            color: #28b4e6 !important;
        }
        .list-unstyled li i {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<!-- Particles.js Background -->
<div id="particles-js"></div>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="grid">
                <div class="logo">
                    <img src="{{asset("/img/portal-itop-logo.png")}}" alt="iTop Portal Logo" height="50"/>
                    <span>iTop <b>Portal</b></span>
                </div>
                @if (Route::has('login'))
                    <nav class="nav -mx-3 flex flex-1 justify-end">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="nav-link">Log in</a>
                        @endauth
                    </nav>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container main-content">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-6 mb-4">
                <div class="left-block">
                    <h1 class="display-4 fw-bold">Welcome to iTop Portal</h1>
                    <p class="lead">Unleash the power of a cutting-edge, branded web portal that transforms IT service delivery for your team and clients.</p>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Seamless Simplicity:</strong> Wow users with an intuitive interface for effortless ticket creation and instant tracking.</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Empower Everyone:</strong> Boost productivity with a custom portal that puts self-service IT support at their fingertips.</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-primary me-2"></i> <strong>Rock-Solid Security:</strong> Trust a scalable platform that keeps your data safe and performance unmatched.</li>

                    </ul>
                    <a href="/dashboard" class="btn btn-custom btn-lg mt-4">Get Started</a>
                </div>
            </div>
            <!-- Right Column -->
            <div class="col-lg-6">
                <div class="right-block">
                    <i class="fas fa-book"></i>
                    <div>
                        <h3>Documentation</h3>
                        <p>Explore our comprehensive documentation to master all of iTop Portal features.</p>
                        <a href="https://portal-doc.hennebont-kerroch.fr" target="_blank">Read the Documentation</a>
                    </div>
                </div>
                <div class="right-block">
                    <i class="fab fa-github"></i>
                    <div>
                        <h3>Source Code</h3>
                        <p>View and contribute to iTop Portal source code on your GitHub repository.</p>
                        <a href="https://github.com/sparfel/itop-portal-2" target="_blank">View on GitHub</a>
                    </div>
                </div>
                <div class="right-block">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Contact Us</h3>
                        <p>Have questions or need assistance?</p>
                        <a href="mailto:emmanuel.lozachmeur@gmail.com" target="_blank">Contact Us</a>
                    </div>
                </div>
            </div>
            <footer class=" text-white text-center py-4">
                <div class="container">
                    <p class="mb-0 text-sm text-white">Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
                </div>
            </footer>
        </div>
    </main>
</div>

<!-- Bootstrap JS and Particles.js initialization -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    particlesJS('particles-js', {
        "particles": {
            "number": {
                "value": 80,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": ["#ffffff", "#FF6F00"]
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                }
            },
            "opacity": {
                "value": 0.5,
                "random": false
            },
            "size": {
                "value": 3,
                "random": true
            },
            "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#ffffff",
                "opacity": 0.25,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "bounce": false
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "repulse"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                },
                "resize": true
            }
        },
        "retina_detect": true
    });
</script>
</body>
</html>
