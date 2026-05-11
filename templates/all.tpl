{include file="header.tpl" title=$title}


        <!-- Products Start -->
    <div class="container-fluid pt-1 pb-3">
        
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">Energy Providers</span></h2>
        <div class="row px-xl-5">
            
            {foreach $articles as $article}
            <div class="col-lg-3 col-md-4 col-sm-6 pb-1">
                <div class="product-item bg-light mb-4">
                    <div class="position-relative overflow-hidden">
                        <img class="img-fluid w-100" src="{$article.image|ep_img}" alt="">
                    </div>
                    <div class="text-center py-4">
                        <a class="h6 text-decoration-none text-truncate" href="">{$article.title}</a>
                        <div class="d-flex align-items-center justify-content-center mt-2">
                            <h5>Score: {if $article.score}{$article.score}%{else}Pending{/if}</h5>
                        </div>
                        <div class="d-flex align-items-center justify-content-center mb-1">
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small class="fa fa-star text-primary mr-1"></small>
                            <small>(54)</small>
                        </div><br>
                        
                        <a href="{$article.url}" class="btn btn-primary">View Profile</a>
                    </div>
                </div>
            </div>
            {/foreach}
        </div>
            
                                   
            
{include file="footer.tpl"}