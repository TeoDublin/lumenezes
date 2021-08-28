<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link href="https://unpkg.com/@coreui/coreui@2.1.16/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        .primeira {
            background-color: #0d6efd;!important;font-style: normal;color:white;
        }
        .segunda {
            background-color: #0d6efd;!important;font-style: normal;color:white;
        }
        .texto {font-family: Cursive, Georgia, Times, 'Times New Roman', serif; font-style: normal;font-size: 15px}

        @media only screen and (max-width: 500px) {
            .texto {font-family: Cursive, Georgia, Times, 'Times New Roman', serif; font-style: normal;font-size: 4vw}
        }        

        .texto-primario{
            color: rgb(12, 12, 12);font-style: normal;
        }

        .texto-secondario{
            color: rgb(12, 12, 12);font-style: normal;
        }      
        
        .texto-terciario{
            color: rgb(12, 12, 12);font-style: italic;
        }        
        

    </style>
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed pace-done sidebar-lg-show texto">
    
    <header class="app-header navbar fixed-top">
         
        <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
            <span class="navbar-toggler-icon"></span>
        </button>

        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
            <span class="navbar-toggler-icon"></span>
        </button>

        <span class="navbar-brand texto-primario">@stack('title')</span>
        <span class="navbar-text"></span>
    </header>

    <div class="app-body">
        
        <div class="sidebar">
            <nav class="sidebar-nav">

                <ul class="nav">
                    <li class="nav-item">
                        <a href="/" class="nav-link">
                            <i class="nav-icon fas fa-fw fa-calculator"></i>
                            Simulador
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/receitas" class="nav-link">
                            <i class="nav-icon fas fa-fw fa-cookie-bite"></i>
                            Receitas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/produtos" class="nav-link">
                            <i class="nav-icon fas fa-fw fa-shopping-cart"></i>
                            Produtos
                        </a>
                    </li>                    
                </ul>

            </nav>
            <button class="sidebar-minimizer brand-minimizer" type="button"></button>
        </div>


        <main class="main">
            <div style="padding-top: 20px" class="container-fluid p-1">
               
                @yield('content')

            </div>

        </main>

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://unpkg.com/@coreui/coreui@2.1.16/dist/js/coreui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    @stack('script')
</body>

</html>