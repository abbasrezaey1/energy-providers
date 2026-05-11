

{include file="header.tpl" title=$title}


    {foreach $last_article as $article}
    
        <!-- Product Details Area Start -->
        <div class="single-product-area section-padding-100 clearfix">
            <div class="container-fluid">
                <div class="column">
                    <div class="col">
                        <div>
                            <div id="product_details_slider"  >
                                <div class="carousel-inner"  >
                                    <div class="carousel-item active">
                                        <a class="gallery_img" href="{$article.image|ep_img}">
                                            <img class="d-block w-100" src="{$article.image|ep_img}" style="margin-top:20px" alt="First slide">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="single_product_desc">
                            <!-- Product Meta Data -->
                            <div class="product-meta-data">
                                <div class="line"></div>
                                <p class="product-price">Score: {if $article.score}{$article.score}%{else}Pending{/if}</p>
                                <a href="product-details.html">
                                    <h6>{$article.title}</h6>
                                </a>
                                <!-- Ratings & Review -->
                                <div class="ratings-review mb-15 d-flex align-items-center justify-content-between">
                                    <div class="ratings">
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                        <i class="fa fa-star" aria-hidden="true"></i>
                                    </div>
                                </div>
        
                            </div>

                            <div class="short_overview my-2">
                                <p>
                                    {$article.content}
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        {/foreach}
        <!-- Product Details Area End -->
    </div>
    <!-- ##### Main Content Wrapper End ##### -->

{include file="footer.tpl"}