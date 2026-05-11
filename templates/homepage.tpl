{include file="header.tpl" title=$title}
    <!-- Carousel Start -->
    <div class="container-fluid mb-3">
        <div class="row px-xl-5">
            <div class="col-lg-8">
                <div id="header-carousel" class="carousel slide carousel-fade mb-30 mb-lg-0" data-ride="carousel">
                 
                    <div class="carousel-inner">
                        <div class="carousel-item position-relative active" style="height: 430px;">
                            <img class="position-absolute w-100 h-100" src="img/carousel-2.jpg" style="object-fit: cover;">
                            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                <div class="p-3" style="max-width: 700px;">
                                    <h1 class="display-4 text-white mb-3 animate__animated animate__fadeInDown">We Rate Energy Providers</h1>
                                    <p class="mx-md-5 px-5 animate__animated animate__bounceIn">We evaluate & score energy providers for you to easily judge them</p>
                                    <a class="btn btn-outline-light py-2 px-4 mt-3 animate__animated animate__fadeInUp" href="all">See Energy Providers</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                
                {foreach $articles1a as $article}
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="{$article.image|ep_img}" alt="">
                    <div class="offer-text">
                        <h3 class="text-white mb-3">Recently Applied</h3>
                        <h6 class="text-white">Energy Provider: {$article.url}</h6>
                        <h6 class="text-white">SCORE: {if $article.score}{$article.score}%{else}Pending{/if}</h6>
                        
                        <a href="{$article.url}" class="btn btn-primary">View Profile</a>
                    </div>
                </div>
                {/foreach}
                
                {foreach $articles1b as $article}
                <div class="product-offer mb-30" style="height: 200px;">
                    <img class="img-fluid" src="{$article.image|ep_img}" alt="">
                    <div class="offer-text">
                        <h3 class="text-white mb-3">Score Improved</h3>
                        <h6 class="text-white">Energy Provider: {$article.url}</h6>
                        <h6 class="text-white">SCORE: {if $article.score}{$article.score}%{else}Pending{/if}</h6>
                        
                        <a href="{$article.url}" class="btn btn-primary">View Profile</a>
                    </div>
                </div>
                {/foreach}
                
            </div>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Featured Start -->
    <div class="container-fluid">
        <div class="row px-xl-5">
            <div class="container-fluid">
                        <div class="bg-light" style="padding: 30px;">
                            <h5 style="color:#ffcb0d">Energy providers evaluation</h5>
                            <h5>We research and publish indicative profiles of electricity suppliers.<br>
                            Providers may apply for inclusion in the next review round:</h5>
                            <p style="font-size:17px; font-weight:bold">
                                Application Deadline: <span>{$result_deadline} 12th</span><br>
                                Indexing Date: <span >{$deadline_date} 23th</span>
                            </p>
                            <a href="contact" class="btn btn-primary">Apply</a>
                        </div>
            </div>
        </div>
    </div>
    <!-- Featured End -->


    <!-- Categories Start -->
    <div class="container-fluid pt-5">
            <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Under Evaluation</span></h2>
            <div class="row px-xl-5 pb-3">
                {foreach $articles12 as $article}
                    <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                        <a class="text-decoration-none" href="{$article.url}">
                            <div class="cat-item d-flex align-items-center mb-4">
                                <div class="overflow-hidden" style="width: 100px; height: 100px;">
                                    <img class="img-fluid" src="{$article.image|ep_img}" alt="">
                                </div>
                                <div class="flex-fill pl-3">
                                    <h6>{$article.title}</h6>
                                    <small class="text-body">Score: {if $article.score}{$article.score}%{else}Pending{/if}</small>
                                </div>
                            </div>
                        </a>
                    </div>
                {/foreach}
            </div>
    </div>
    
    <!-- Products Start -->
    <div class="container-fluid pt-5 pb-3">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Top Energy Providers</span></h2>
        <div class="row px-xl-5">
            {foreach $articles8 as $article}
                <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                    <div class="product-item bg-light mb-4">
                        <div class="position-relative overflow-hidden">
                            <img class="img-fluid w-100" src="{$article.image|ep_img}" alt="">
                        </div>
                        <div class="text-center py-4">
                            <a class="h6 text-decoration-none text-truncate" href="{$article.url}">{$article.title}</a>
                            <div class="d-flex align-items-center justify-content-center mt-2">
                                <h5>Score: {if $article.score}{$article.score}%{else}Pending{/if}</h5>
                            </div>
                            
                            <a href="{$article.url}" class="btn btn-primary">View Profile</a>
                        </div>
                    </div>
                </div>
            {/foreach}
           
                                   
            
   <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
    </div>
    <!-- Products End -->


    <!-- Vendor End -->
{include file="footer.tpl"}
