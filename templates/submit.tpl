{include file="header.tpl" title=$title}

    {if $message}
        <div class="alert alert-success">
           <strong>{$message}</strong>
        </div>
    {/if}

<div class="mb-3"></div>

    <form class="m-4" method="POST" action="receiver">
        
            <div class="form-group">
               <label for="title">Title:</label>
               <input type="text" class="form-control" {if $article.title}value="{$article.title}"{/if} name="title" id="title" required>
            </div>
            
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea class="form-control" rows="8" name="content" required>{if $article.content}{$article.content}{/if}</textarea>
                  {if $content_hint_message}
                      <div class="alert alert-{$message_type}" role="alert">
                        {$content_hint_message}
                      </div>
                   {/if}
            </div>
            
            <div class="form-group">
               <label for="keywords">Date:</label>
               <input type="file" class="form-control" {if $article.keywords}value="{$article.keywords}"{/if} name="image" required>
            </div>

            <div class="form-group">
               <label for="keywords">Keywords:</label>
               <input type="text" class="form-control" {if $article.keywords}value="{$article.keywords}"{/if} placeholder="Enter keywords" name="keywords" id="keywords" required>
               <small id="keywords" class="form-text text-muted">Speperate the keywords with "," e.g. keyword1, keyword2</small>            
            </div>
            
            <div class="form-group">
               <label for="metadescription">Meta-Desciption:</label>
               <input type="text" class="form-control" {if $article.metadescription}value="{$article.metadescription}"{/if} placeholder="Enter a short description" name="metadescription" id="metadescription" required>
               <small id="metadescription" class="form-text text-muted">Enter a description of the article maximum two lines.</small>            
            </div>
      
            <input type="hidden"  name="related_link_number" value="{$related_link_number}"> 
            
            
          
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Related Link</span>
              </div>
              <input type="text" name="related_links_text_1" {if $article.related_links_text_1}value="{$article.related_links_text_1}"{/if} placeholder="Label" class="form-control">
              <input type="url" name="related_links_1" {if $article.related_links_1}value="{$article.related_links_1}"{/if} placeholder="URL: e.g. http://website.com/article-1" class="form-control">
            </div>
            
            
             
            
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Related Link</span>
              </div>
              <input type="text" name="related_links_text_2" {if $article.related_links_text_2}value="{$article.related_links_text_2}"{/if} placeholder="Label" class="form-control">
              <input type="url" name="related_links_2" {if $article.related_links_2}value="{$article.related_links_2}"{/if} placeholder="URL: e.g. http://website.com/article-2" class="form-control">
            </div>
            
            
        
            <div class="input-group">
              <div class="input-group-prepend">
                <span class="input-group-text">Related Link</span>
              </div>
              <input type="text" name="related_links_text_3" {if $article.related_links_text_3}value="{$article.related_links_text_3}"{/if} placeholder="Label" class="form-control">
              <input type="url" name="related_links_3" {if $article.related_links_3}value="{$article.related_links_3}"{/if} placeholder="URL: e.g. http://website.com/article-3" class="form-control">
            </div>
            
            <br>
            
            <div class="form-group">
                <label for="web">Select Website:</label>
                <select name="web" id="web" required>
                  <option value="abc-cbd.fr">abc-cbd.fr</option>
                  <option value="cbd-mania.fr">cbd-mania.fr</option>
                  <option value="cbdix.fr">cbdix.fr</option>
                </select>
            </div>
            
            <div class="form-group">
               <label for="keywords">Date:</label>
               <input type="date" class="form-control" {if $article.keywords}value="{$article.keywords}"{/if} name="date" required>
                       
            </div>
            <input type="text" value="217" name="id">
            <input type="text" value="1" name="update">
            <input type="text" value="http://xafh7070.odns.fr/images/They-cruely-has-been-increasing-bills-now.png" name="image_url">
         
            
            <div class="form-group">
            <label for="keywords">Category:</label>
                   <select class="form-control" name="category" id="pet-select">
                     <option value="">--Select--</option>
                     <option value="business">Business</option>
                     <option value="technology">Technology</option>
                     <option value="science">Science</option>
                         <option value="humanity">Humanity</option>
                    </select>
            </div>
            
            <div>
                    <button class="btn btn-primary font-weight-semi-bold px-4" style="height: 50px;" type="submit">Post Article</button>
            </div>
    </form>
    
</div>