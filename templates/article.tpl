{include file="header.tpl" title=$title}
    {foreach $articly as $article}
    <!-- Shop Detail Start -->
    <div class="container-fluid pb-5">
        <div class="row px-xl-5">
            <div class="col-lg-5 mb-30">
                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                    <div class="suppliers">
                      <div class="logo">
                        <div class="sp_logo">
                            <img src="{$article.image|ep_img}" alt="{$article.title}" title="{$article.title}" width="100%" border="0">
                            <button class="btn btn-warning border-warning rounded btn-sm py-0 btn-mini font-italic text-muted">{$article.category|ucfirst}</button>
                        </div>
                    </div>
            <div class="aler">
                        <div class="sp_url" style="margin-top:-12px">
                            <a style="color:gray; tex-decoration:none" >International Energy Provider Number (IEPN):</a>
                        </div>
                                                <div class="sp_phone">
                            <a style="color:gray"  class="tel">IEPN-{$article.iepn}</a></div>
                             
            
                    <div class="emissions">
                                <div class="sp_emissions">
                    <table class="emissions">
                        <tbody>
                            <tr>
                                <th><h1 style="color:#FFD333" ><i class="fa fa-cloud"></i></h1></th>
                                <td  style="padding-left:7px"><b>CO<span class="sub">2</span></b><br><span>{$article.co2} g/kWh</span></td>
                            </tr>
                            <tr>
                                <th><h1 style="color:#FFD333" ><i class="fa fa-flag-checkered" aria-hidden="true"></i></h1></th>
                                <td style="padding-left:7px"><b>Nuclear Waste</b><br><span>{$article.nuclear_waste} g/kWh</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                </div>
                
          
                
                <div class="addr" style="margin-top:20px; color:#FFD333">
                    <div class="sp_addr">
                    <p>
                    {if $article.address}
                       <i class="fa fa-map-marker"> </i> {$article.address}<br>
                    {/if}
                    {if $article.tel}
                       <i class="fa fa-phone" aria-hidden="true"></i> {$article.tel}
                    {/if}
                    </p>
                    <a href="http://{$article.website}" target="_blank" class="btn btn-primary">Website</a>
                    </div>
                </div>
                </div>
            </div>
</div></div>

            <div class="col-lg-7 h-auto mb-30">
                <div class="h-100 bg-light p-30">
                    <h3><button class="btn btn-warning text-white rounded py-1"><i class="fa fa-check-circle"></i> Verified</button> {$article.title}</h3>
                    
                    <div class="d-flex mb-3">
                    {if $article.score}
                        <div class="text-primary mr-2">
                            <small class="{if $article.score<10}far fa-star{elseif $article.score>10 and $article.score<20}fas fa-star-half-alt{elseif $article.score>20}fas fa-star{/if}"></small>
                            <small class="{if $article.score<30}far fa-star{elseif $article.score>30 and $article.score<40}fas fa-star-half-alt{elseif $article.score>40}fas fa-star{/if}"></small>
                            <small class="{if $article.score<50}far fa-star{elseif $article.score>50 and $article.score<60}fas fa-star-half-alt{elseif $article.score>60}fas fa-star{/if}"></small>
                            <small class="{if $article.score<70}far fa-star{elseif $article.score>70 and $article.score<80}fas fa-star-half-alt{elseif $article.score>80}fas fa-star{/if}"></small>
                            <small class="{if $article.score<90}far fa-star{elseif $article.score>90 and $article.score<100}fas fa-star-half-alt{elseif $article.score==100}fas fa-star{else}far fa-star{/if}" ></small>
                        </div>
                        
                        
                        <small class="pt-1">({$article.score-4} Reviews)</small>
                    {/if}
                    </div>
                    {if $article.business_activeness}
                        <div class="alert alert-warning" role="alert">
                           <i class="fa fa-info-circle" aria-hidden="true"></i> This provider has gone out of business. <a href="contact">Contact us</a> to guide you.
                        </div>
                    {/if}
                    
                        <div class="d-flex align-items-center mb-4 pt-2">
                            <button class="btn btn-primary px-3"><i class="fa fa-exclamation-circle mr-2" aria-hidden="true"></i>E-SCORE: {if $article.score}{$article.score}%{else}<small>Under Evaluation</small>{/if}</button>
                        </div>
                    
                    <p class="mb-4">
                    {$article.content}
                    {if $article.coal or $article.gas or $article.nuclear or $article.renewable} <br><br>
                        <i class="fa fa-info-circle" aria-hidden="true"></i> 
                        <small>{$article.title} provides{if $article.coal} {$article.coal}%  coal energy (national average is 2.7%), and {/if}{if $article.gas}{$article.gas}% gas energy (national average is 38.2%),{/if} {if $article.nuclear}{$article.nuclear}% nuclear energy (national average is 16.1%), and {/if}{if $article.renewable}{$article.renewable}% renewable energy (national everage is 40.3%){/if}.</small>
                    {/if}
                    </p>
                

                    <a href="http://iwantbetter.com" class="btn btn-primary px-3" style="font-size: 18px;">Switch Energy <i class="fa fa-arrow-right mr-1"></i></a>
                    <br><br>
                    <div class="d-flex pt-2">
                        <strong class="text-dark mr-2">Share on:</strong>
                        <div class="d-inline-flex">
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a class="text-dark px-2" href="">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->
{/foreach}

{include file="footer.tpl"}