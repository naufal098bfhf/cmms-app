<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Favicon (Logo di Title) -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">

    <title>Halaman Login</title>
</head>


<body>
    <div class="container" id="container">
       <div class="form-container sign-up">
            <div class="cmms-info">
                <h1 class="cmms-title">About CMMS</h1>
                <p class="cmms-desc">
                    CMMS (Computerized Maintenance Management System) adalah sistem berbasis software
                    yang dirancang untuk membantu organisasi dalam merencanakan, menjadwalkan, dan
                    memantau seluruh kegiatan pemeliharaan mesin, peralatan, dan aset perusahaan.
                    Sistem ini memungkinkan pencatatan aktivitas secara terstruktur, meminimalisir downtime,
                    serta meningkatkan efisiensi dan produktivitas operasional secara keseluruhan. Dengan CMMS, perusahaan dapat mengoptimalkan sumber daya, memperpanjang umur peralatan,
                    serta memastikan setiap aktivitas pemeliharaan terdokumentasi dengan baik.
                </p>
            </div>
        </div>


        <div class="form-container sign-in">
            <form>
                <h1>Sign In</h1>
                <div class="social-icons">
                    <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                    <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
                <span>or use your email password</span>
                <input type="email" placeholder="Email">
                <input type="password" placeholder="Password">
                <button>Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <button class="hidden" id="login">Sign In</button>
                </div>
               <div class="toggle-panel toggle-right">
                    <img src="{{ asset('images/logo2.png') }}" alt="Logo" id="register" class="logo-instansi">
                    <h3>Politeknik Industri Petrokimia Banten</h3>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
