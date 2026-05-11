{include file="header.tpl" title=$title}

<div class="container-fluid pb-5">
        <h2 class="section-title position-relative text-uppercase mx-xl-5 mb-4"><span class="bg-secondary pr-3">{$title}</span></h2>
        <div class="row px-xl-5">
            <div class="col-lg mb-30">
                    <div class="mb-4">

<p>                         {foreach $abouts as $about}
           {$about.about}
        {/foreach}  
  </p>
            </div>
        </div>

</div>
</div>
    
{include file="footer.tpl"}




 