<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

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

        @media only screen and (max-width: 380px) {
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
        
        /* Hide scrollbar for Chrome, Safari and Opera */
        .corpo::-webkit-scrollbar {
        display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .corpo {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
        }
    </style>    

</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed pace-done sidebar-lg-show texto corpo">
    <div id="app">

        <header class="app-header navbar fixed-top">
         
            <div class="p-2">
                <a href="@stack('back_to')">
                    <i class="fa fa-arrow-left fa-2x"></i>
                </a>
            </div>

            
            <span class="navbar-brand texto-primario">@stack('title')</span>
            <span class="navbar-text"></span>

        
        </header>

        <div class="app-body">
            <div style="padding-top: 20px" class="container-fluid p-1 m-0">
                @yield('content')
            </div>
        </div>        


        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://unpkg.com/@coreui/coreui@2.1.16/dist/js/coreui.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        
        @stack('script_simulador')        
    </div>
</body>
</html>
