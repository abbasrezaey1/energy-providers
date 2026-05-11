<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{$title}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="{$keywords}" name="keywords">
    <meta content="{$metadescription}" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">  

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
{if isset($head_preloads)}{foreach $head_preloads as $p}<link rel="preload" href="{$p}" as="image">{/foreach}{/if}
</head>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                  <a href="home" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-dark px-2">Rate</span>
                    <span class="h1 text-uppercase text-dark bg-primary px-2 ml-n1">Energy</span>
                </a>
            </div>
            <div class="col-lg-4 col-6 text-left">
                <form action="all">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search for energy provider">
                        
                        <div class="input-group-append">
                            <a href="all" style="text-decoration:none" class="input-group-text bg-transparent text-primary">
                                <i class="fa fa-search"></i>
                            </a>
                        </div>
                    
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-6 text-right">
                <p class="m-0">Public Relations</p>
                <h5 class="m-0">{$email}</h5>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-dark mb-30">
        <div class="row px-xl-5">
            <div class="col-lg-3 d-none d-lg-block">
                <a class="btn d-flex align-items-center justify-content-between bg-primary w-100" data-toggle="collapse" href="#navbar-vertical" style="height: 65px; padding: 0 30px;">
                    <h6 class="text-dark m-0"><i class="fa fa-bars mr-2"></i>Recently Updated</h6>
                    <i class="fa fa-angle-down text-dark"></i>
                </a>
                <nav class="collapse position-absolute navbar navbar-vertical navbar-light align-items-start p-0 bg-light" id="navbar-vertical" style="width: calc(100% - 30px); z-index: 999;">
                    <div class="navbar-nav w-100">
                        {foreach $articles4a as $article}
                            <a href="{$article.url}" class="nav-item nav-link">{$article.title|ucfirst} {if $article.score}|  Score: {$article.score}%{/if}</a>
                        {/foreach}
                    </div>
                </nav>
            </div>
            <div class="col-lg-9">
                <nav class="navbar navbar-expand-lg bg-dark navbar-dark py-3 py-lg-0 px-0">
                    <a href="index.html" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2">Rate</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Energy</span>
                    </a>
                    <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                     <div class="navbar-nav mr-auto py-0">
                            <a href="home" class="nav-item nav-link {$status_1}">Home</a>
                            <a href="all" class="nav-item nav-link {$status_2}">Energy Providers</a>
                            <a href="evaluation-team" class="nav-item nav-link {$status_3}">Evaluation Team</a>
                            <a href="evaluation-checklist" class="nav-item nav-link {$status_4}">Evaluation Criterion</a>
                            <a href="contact" class="nav-item nav-link {$status_5}">Contact</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->
