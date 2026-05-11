<?php

// If the server hides PHP errors, create an EMPTY file named DEBUG_ON next to index.php (FTP).
// Then reload — errors show in the browser. Delete DEBUG_ON when finished.
if (is_file(__DIR__ . '/DEBUG_ON')) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

require_once __DIR__ . '/lib/debug_bootstrap.php';
require_once __DIR__ . '/lib/config.php';
require_once __DIR__ . '/lib/functions.php';
require_once __DIR__ . '/lib/require_https.php';
require_once __DIR__ . '/lib/article_helpers.php';
require_once __DIR__ . '/libs/Smarty.class.php';
require_once __DIR__ . '/lib/site.php';
session_start();
$smarty = new Smarty;
$smarty->registerPlugin('modifier', 'ep_img', static function (?string $image): string {
    return ep_article_image_src($image);
});
$site = new Site;// Receive and update POST data if 
// Get website settings from the Site class
$website = $site->getSiteSettings();
$site->receiveAndUpdate($_POST, $website, $smarty);

$url_path = $_SERVER['REQUEST_URI'];

// Router — $base_dir from .env (see lib/config.php); empty = domain root install.
$directory_path = $base_dir;
$first_url_path = strtok($url_path, '/');
$last_url_part = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '');
$last_url_part_path = strtok($last_url_part, '?');
if ($directory_path !== '') {
    $last_url_part_path = str_replace($directory_path, '', $last_url_part_path);
}
$last_url_part_path = trim((string) $last_url_part_path, '/');
// Plain /index.php request should behave like homepage when using rewrite.
if ($last_url_part_path === 'index.php') {
    $last_url_part_path = '';
}
$eqPos = strrpos($last_url_part, '=');
$last_url_part_query = $eqPos !== false ? substr($last_url_part, $eqPos + 1) : '';

// DB/web identifier: folder name, or hostname when installed at root (matches submissions.web_id style).
$web_id = $directory_path !== ''
    ? $directory_path
    : preg_replace('/[^a-zA-Z0-9.-]/', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
$website = $web_id;
$base_url = trim($_SERVER['HTTP_HOST']);

$date = date("Y-m-d");

$year = date("Y");

$smarty->assign('base_url', ep_public_base_url_prefix() . $base_url);
$smarty->assign('date', $date);
$smarty->assign('year', $year);

$row = null;
try {
    $stWs = $conn->prepare(
        'SELECT * FROM `web_settings` WHERE `web_id` COLLATE utf8mb4_0900_ai_ci = ? COLLATE utf8mb4_0900_ai_ci LIMIT 1'
    );
    $stWs->execute([$web_id]);
    $row = $stWs->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $row = null;
}
if (!$row) {
    $row = $conn->query('SELECT * FROM `web_settings` ORDER BY `web_no` ASC LIMIT 1')->fetch(PDO::FETCH_ASSOC);
}
$row = $row ?: [];

$tracker = $row['tracker'] ?? '';
$web_no = $row['web_no'] ?? '';
$logo = $row['logo'] ?? '';
$title = $row['name'] ?? '';
$address = trim((string) ($row['address'] ?? ''));
$email = trim((string) ($row['email'] ?? ''));
$tel = trim((string) ($row['tel'] ?? ''));
$website_domain = $row['website_domain'] ?? '';
$logo_small = $row['logo_small'] ?? '';

$contactHtml = (string) ($row['contact'] ?? '');
if ($email === '' && $contactHtml !== '' && preg_match('/mailto:([^"\'>\s&]+)/i', $contactHtml, $m)) {
    $email = rawurldecode(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
}

// Footer / topbar: optional columns + .env (see .env.example).
if ($address === '') {
    $address = ep_env('SITE_ADDRESS', 'Fuhrmannsweg 2, 07607 Eisenberg, Germany');
}
if ($email === '') {
    $email = ep_env('SITE_EMAIL', 'info@asindex.org');
}
if ($tel === '') {
    $tel = ep_env('SITE_TEL', '');
}

$smarty->assign('site_host', preg_replace('/^www\./i', '', (string) ($_SERVER['HTTP_HOST'] ?? '')));

$smarty->assign('tracker', $tracker);

$articles = [];

$sql = "SELECT * FROM submissions ORDER BY score DESC, business_activeness DESC";

$i = 0;
foreach ($conn->query($sql) as $article) {
    $articles[$i] = $article;
    $i++;
}

$n = count($articles);
$articles_newest = ep_sort_submissions_newest($articles);

// Navbar "Recently Updated" dropdown — top providers by score (up to 4).
$smarty->assign('articles4a', array_slice($articles, 0, min(4, $n)));

// Homepage side tiles: two most recently added submissions.
$smarty->assign('articles1a', array_slice($articles_newest, 0, 1));
$smarty->assign('articles1b', array_slice($articles_newest, 1, 1));

// Footer columns: top by score vs newest by id.
$smarty->assign('articles6a', array_slice($articles, 0, min(6, $n)));
$smarty->assign('articles6b', array_slice($articles_newest, 0, min(6, $n)));

$smarty->assign('articles2b', array_slice($articles, 0, min(2, $n)));
      
$board = $board ?? 'Board';
$collection = $collection ?? 'Collection';
$type = $type ?? 'provider';
$object = $object ?? 'provider';
$object_description = $object_description ?? '';
$web_settings = $web_settings ?? $website;

$navbar = ['home'=>'Home', 'about'=>'About', 'board'=>$board, 'collection'=>$collection, 'contact'=>'Contact'];
$site = ['type'=>$type, 'object'=>$object, 'object_description'=>$object_description];
$smarty->assign('navbar', $navbar);
$smarty->assign('site', $site);
$smarty->assign('website_domain', $website_domain);
$smarty->assign('logo', $logo);
$smarty->assign('logo_small', $logo_small);
$smarty->assign('web_settings', $web_settings);
$smarty->assign('web_no', $web_no);
$smarty->assign('website', $website);

$smarty->assign('address', $address);
$smarty->assign('email', $email);
$smarty->assign('tel', $tel);

switch ($last_url_part_path)
{

    case 'all': 
               $sql = "SELECT * FROM submissions ORDER BY score DESC, business_activeness DESC";
                
                $i=0;
                foreach($conn->query($sql) as $article) {
                        $articles[$i] = $article;     
                        $i++;
                }
                
                $smarty->assign('articles', $articles);
                
                $smarty->assign('date', date("Y/m/d"));
                $smarty->assign('title', 'Energy Providers');
                $smarty->assign('status_2', 'active');
                $smarty->display('all.tpl');
    break;      

    
    case 'newest':
                
                $sql = "SELECT * FROM submissions ORDER BY submission_id DESC LIMIT 1";
                $last_article = $conn->query($sql);
                
                $sql = "SELECT * FROM submissions ORDER BY submission_id DESC LIMIT 1";
                foreach($conn->query($sql) as $article) {
                    $idd = $article['idd'];
                }
                
                //Get comments                
                $sql = "SELECT * FROM `comments` WHERE `submission_id` = '$idd'";
                
                $comments = $conn->query($sql);
                
                if($comments->rowCount() !== 0){
                    $smarty->assign('comments', $comments);
                }
                
                $sql = "SELECT * FROM `submissions`";
            
                $i=0;
                foreach($conn->query($sql) as $article) {
                    $articles[$i] = $article;
                    $i++;
                }

                $articles_newest_page = ep_sort_submissions_newest($articles);
                $smarty->assign('articles5a', array_slice($articles_newest_page, 0, min(5, count($articles))));
                
                $smarty->assign("last_article", $last_article);
                $smarty->assign('title', 'Newest News');
                $smarty->assign('status_3', 'active');
                $smarty->display('newest.tpl');
                
    break;
    
    case 'evaluation-team':

                $smarty->assign('date', date("Y/m/d"));
                $smarty->assign('title', 'Evaluation Team');
                $smarty->assign('status_3', 'active');
                $smarty->assign('head_preloads', ['img/1.png', 'img/2.png', 'img/3.png', 'img/4.png']);
                $smarty->display('evaluation-team.tpl');
                
    break;
    
    case 'evaluation-checklist':

                $smarty->assign('date', date("Y/m/d"));
                $smarty->assign('title', 'Evaluation Checklist');
                $smarty->assign('status_4', 'active');
                $smarty->display('evaluation-checklist.tpl');
                
    break;
    
    
    case 'receiver':


            if (isset($_POST['setting'])) {

                $homepage_post_number = $_POST['homepage_post_number'];
                $general_robot_array = $_POST['general_robot_array'];
                
                $name = $_POST['name'];
                $website_domain = $_POST['website_domain'];
                $address = $_POST['address'];
                $email = $_POST['email'];
                $tel = $_POST['tel'];
                
                $logo_url = $_POST['logo_url'];
                $logo_small_url = $_POST['logo_small_url'];

                $about = $_POST['about'];
                $terms_conditions = $_POST['terms_conditions'];
                $privacy_policy = $_POST['privacy_policy'];
                
                $website_type = $_POST['website_type'];
                $website_content = $_POST['website_content'];
                
                $footer_link_1 = $_POST['footer_link_1'];
                $footer_link_text_1 = $_POST['footer_link_text_1'];

                $footer_link_2 = $_POST['footer_link_2'];
                $footer_link_text_2 = $_POST['footer_link_text_2'];

                $footer_link_3 = $_POST['footer_link_3'];
                $footer_link_text_3 = $_POST['footer_link_text_3'];

                $footer_link_4 = $_POST['footer_link_4'];
                $footer_link_text_4 = $_POST['footer_link_text_4'];

                $footer_link_5 = $_POST['footer_link_5'];
                $footer_link_text_5 = $_POST['footer_link_text_5'];

                $footer_link_6 = $_POST['footer_link_6'];
                $footer_link_text_6 = $_POST['footer_link_text_6'];

                $footer_link_7 = $_POST['footer_link_7'];
                $footer_link_text_7 = $_POST['footer_link_text_7'];

                $footer_link_8 = $_POST['footer_link_8'];
                $footer_link_text_8 = $_POST['footer_link_text_8'];
                
                $logo_title = string_to_url($name).'.png';
                $logo_small_title = string_to_url($name).'_mobile.png';
                
                file_put_contents('logo/'. $logo_title, file_get_contents($logo_url));
                file_put_contents('logo/'. $logo_small_title, file_get_contents($logo_small_url));
                
                $sth = $conn->prepare('UPDATE `web_settings` SET `footer_link_1`=?, `footer_link_text_1`=?, `footer_link_2`=?, `footer_link_text_2`=?, `footer_link_3`=?, `footer_link_text_3`=?, `footer_link_4`=?, `footer_link_text_4`=?, `footer_link_5`=?, `footer_link_text_5`=?, `footer_link_6`=?, `footer_link_text_6`=?, `footer_link_7`=?, `footer_link_text_7`=?, `footer_link_8`=?, `footer_link_text_8`=?, `homepage_post_number`=?, `logo`=?, `logo_small`=?, `general_robot_array`=?, `name`=?, `website_type`=?, `website_content`=? , `address`=? , `tel`=? , `email`=?, `about`=?, `terms_conditions`=?, `privacy_policy`=?, `logo_title`=?, `logo_small_title`=?, `website_domain`=?');
                
                $sth->bindParam(1, $footer_link_1);
                $sth->bindParam(2, $footer_link_text_1);

                $sth->bindParam(3, $footer_link_2);
                $sth->bindParam(4, $footer_link_text_2);

                $sth->bindParam(5, $footer_link_3);
                $sth->bindParam(6, $footer_link_text_3);

                $sth->bindParam(7, $footer_link_4);
                $sth->bindParam(8, $footer_link_text_4);

                $sth->bindParam(9, $footer_link_5);
                $sth->bindParam(10, $footer_link_text_5);

                $sth->bindParam(11, $footer_link_6);
                $sth->bindParam(12, $footer_link_text_6);

                $sth->bindParam(13, $footer_link_7);
                $sth->bindParam(14, $footer_link_text_7);

                $sth->bindParam(15, $footer_link_8);
                $sth->bindParam(16, $footer_link_text_8);

                $sth->bindParam(17, $homepage_post_number);
                $sth->bindParam(18, $logo_url);
                $sth->bindParam(19, $logo_small_url);
                $sth->bindParam(20, $general_robot_array);
                $sth->bindParam(21, $name);
                $sth->bindParam(22, $website_type);
                $sth->bindParam(23, $website_content);
                $sth->bindParam(24, $address);
                $sth->bindParam(25, $tel);
                $sth->bindParam(26, $email);
                $sth->bindParam(27, $about);
                $sth->bindParam(28, $terms_conditions);
                $sth->bindParam(29, $privacy_policy);
                $sth->bindParam(30, $logo_title);
                $sth->bindParam(31, $logo_small_title);
                $sth->bindParam(32, $website_domain);
                $sth->execute();

} elseif (isset($_POST['is_comment'])) {
   
            $comment = $_POST['comment'];
            $name = $_POST['name'];
            $id= $_POST['id'];
            $submission_id = $_POST['submission_id'];
            $date = $_POST['date'];
            
            $sth = $conn->prepare('INSERT INTO `comments`(`submission_id`, `name`, `comment`, `date`, `id`) VALUES (?,?,?,?,?)');
            $sth->bindParam(1, $submission_id);
            $sth->bindParam(2, $name);
            $sth->bindParam(3, $comment);
            $sth->bindParam(4, $date);
            $sth->bindParam(5, $id);
            $sth->execute();
                
} elseif (isset($_POST['is_delete'])){

        $idd= $_POST['id'];
        $sql = "DELETE FROM `submissions` WHERE `idd` = '$idd'";
        $conn->query($sql);

} else {
    
    
            echo '<br>'. $title = strip_tags(trim($_POST['title']));//
            echo '<br>'. $url = string_to_url($title);//
            echo '<br>'. $image_title = $url.'.png';//
            
            echo '<br>'. $idd = $_POST['id'];//
            echo '<br>'. $content = $_POST['content'];//

            echo '<br>'. $tel = $_POST['tel'];//
            echo '<br>'. $address = $_POST['address'];//
            echo '<br>'. $business_activeness = $_POST['business_activeness'];//
            echo '<br>'. $co2 = $_POST['co2'];//
            echo '<br>'. $nuclear_waste = $_POST['nuclear_waste'];//
            echo '<br>'. $coal = $_POST['coal'];//
            echo '<br>'. $gas = $_POST['gas'];//
            echo '<br>'. $nuclear = $_POST['nuclear'];//
            echo '<br>'. $renewable = $_POST['renewable'];//
            echo '<br>'. $score = $_POST['score'];//

            echo '<br>'. $iepn = rand(1115233,9897980);//
        
           echo '<br>'.  $image_url = $_POST['image_url'];//
            echo '<br>'. $category = $_POST['category'];//
           echo '<br>'.  $date = $_POST['date'];//
            
           echo '<br>'.  $keywords = $_POST['keywords'];//
           echo '<br>'.  $metadescription = $_POST['metadescription'];//
         
           echo '<br>'.  $related_links_1 = check_empty($_POST['related_links_1']);//
           echo '<br>'.  $related_links_text_1 = check_empty($_POST['related_links_text_1']);//

         echo '<br>'.    $related_links_2 = check_empty($_POST['related_links_2']);//
          echo '<br>'.   $related_links_text_2 = check_empty($_POST['related_links_text_2']);//

       echo '<br>'.      $related_links_3 = check_empty($_POST['related_links_3']);//
        echo '<br>'.     $related_links_text_3 = check_empty($_POST['related_links_text_3']);//
       echo '<br>'.      $website = $_POST['website'];//
            
if(isset($_POST['update'])){

$sth = $conn->prepare('UPDATE `submissions` SET `url`=?, `title`=?, `content`=? , `keywords`=?, `related_links_1`=?, `related_links_text_1`=?, `related_links_2`=?, `related_links_text_2`=?, `related_links_3`=?, `related_links_text_3`=?, `metadescription`=?, `date`=?, `category`=?, `image_url`=?, `image`=?, `website`=?, `tel`=?, `address`=?, `business_activeness`=?, `co2`=?,  `nuclear_waste`=?, `coal`=?, `gas`=?, `nuclear`=?, `renewable`=?, `iepn`=?, `score`=?  WHERE `idd`=?');
$sth->bindParam(1, $url);//
$sth->bindParam(2, $title);//
$sth->bindParam(3, $content);//
$sth->bindParam(4, $keywords);//
$sth->bindParam(5, $related_links_1);//
$sth->bindParam(6, $related_links_text_1);//
$sth->bindParam(7, $related_links_2);//
$sth->bindParam(8, $related_links_text_2);//
$sth->bindParam(9, $related_links_3);//
$sth->bindParam(10, $related_links_text_3);//
$sth->bindParam(11, $metadescription);//
$sth->bindParam(12, $date);//
$sth->bindParam(13, $category);//
$sth->bindParam(14, $image_url);//
$sth->bindParam(15, $image_title);//
$sth->bindParam(16, $website);//
$sth->bindParam(17, $tel);//
$sth->bindParam(18, $address);//
$sth->bindParam(19, $business_activeness);//
$sth->bindParam(20, $co2);//
$sth->bindParam(21, $nuclear_waste);//
$sth->bindParam(22, $coal);//
$sth->bindParam(23, $gas);//
$sth->bindParam(24, $nuclear);//
$sth->bindParam(25, $renewable);//
$sth->bindParam(26, $iepn);//
$sth->bindParam(27, $score);//
$sth->bindParam(28, $idd);//
$sth->execute();
echo 'done';

            } else {
                

       $sth = $conn->prepare('INSERT INTO `submissions`(`url`, `title`, `content`, `keywords`, `related_links_1`, `related_links_text_1`, `related_links_2`, `related_links_text_2`, `related_links_3`, `related_links_text_3`, `metadescription`, `image`, `date`, `category`, `image_url`, `idd`, `web_id`, `tel`, `address`, `business_activeness`, `co2`, `nuclear_waste`, `coal`, `gas`, `nuclear`, `renewable`, `iepn`, `website`, `score`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');

$sth->bindParam(1, $url);
$sth->bindParam(2, $title);
$sth->bindParam(3, $content);
$sth->bindParam(4, $keywords);
$sth->bindParam(5, $related_links_1);
$sth->bindParam(6, $related_links_text_1);
$sth->bindParam(7, $related_links_2);
$sth->bindParam(8, $related_links_text_2);
$sth->bindParam(9, $related_links_3);
$sth->bindParam(10, $related_links_text_3);
$sth->bindParam(11, $metadescription);
$sth->bindParam(12, $image_title);
$sth->bindParam(13, $date);
$sth->bindParam(14, $category);
$sth->bindParam(15, $image_url);
$sth->bindParam(16, $idd);
$sth->bindParam(17, $web_id);
$sth->bindParam(18, $tel);
$sth->bindParam(19, $address);
$sth->bindParam(20, $business_activeness);
$sth->bindParam(21, $co2);
$sth->bindParam(22, $nuclear_waste);
$sth->bindParam(23, $coal);
$sth->bindParam(24, $gas);
$sth->bindParam(25, $nuclear);
$sth->bindParam(26, $renewable);
$sth->bindParam(27, $iepn);
$sth->bindParam(28, $website);
$sth->bindParam(29, $score);
$sth->execute();


            }

            file_put_contents('img/'.$image_title, file_get_contents($image_url));
    }
           // $smarty->display('homepage.tpl');

    break;

    case 'submit':
    
    $smarty->display('submit.tpl');
      
    break;




    case 'delete':

        echo $submission_id = $_POST['submission_id'];

        $sql = "DELETE FROM `submissions` WHERE `submission_id` = '$submission_id'";
        $conn->query($sql);

        header('location: waiting-articles');

    break;
    



    case 'contact':
            

        $sql = "SELECT * FROM `submissions`";
        $i=0;
        foreach($conn->query($sql) as $article) {
                    $articles[$i] = $article;     
                    $i++;
        }

        $smarty->assign('website', $website);
        $smarty->assign('title', 'Contact');
        $smarty->assign('status_5', 'active');
        $smarty->display('contact.tpl');
        
    break;

    case 'privacy-policy':

        $sql = "SELECT * FROM `web_settings`";
        
        $privacies = $conn->query($sql);
        
        $smarty->assign('privacies', $privacies);
        $smarty->assign('title', 'Privacy Policy');
        $smarty->display('privacy.tpl');
      
    break;

    case 'terms-conditions':

      $sql = "SELECT * FROM `web_settings`";
        
        $terms = $conn->query($sql);
        
        $smarty->assign('terms', $terms);
        $smarty->assign('title', 'Terms and Conditions');
        $smarty->display('terms-condition.tpl');
 
    break;

    case 'about':
        
        $sql = "SELECT * FROM `web_settings`";
        
        $abouts = $conn->query($sql);
        
        $smarty->assign('abouts', $abouts);
        $smarty->assign('title', 'About');
        $smarty->display('about.tpl');

    break;


    case 'logout':
    
            session_start();
            session_destroy();
            header("Location: login");
    
    break;
    
    case '404':
        $smarty->display('404.tpl');
    break;
    
    
    case 'submit-now':
        
    $smarty->assign('title', 'Submit '. ucfirst($object));
    $smarty->display('submit.tpl');
    
    break;
    
    case 'editorial-team':
        
    $smarty->assign('title', 'Evaluation Team');
    $smarty->display('editorial-team.tpl');
    
    break;
    
    default:
           
     if ($last_url_part_path == '' || $last_url_part_path == 'home')
     {
        
        $sql = "SELECT * FROM submissions ORDER BY score DESC, business_activeness DESC";
            
        $i=0;
        foreach($conn->query($sql) as $article) {
            $articles[$i] = $article;
            $i++;
        }
        
        $nh = count($articles);
        $smarty->assign('articles12', array_slice($articles, 0, min(12, $nh)));
        $smarty->assign('articles8', array_slice($articles, 0, min(8, $nh)));
        
        $smarty->assign('result_deadline', date("F"));
        $smarty->assign('deadline_date', date("F", strtotime('first day of +1 month')));
        
        $smarty->assign("metadesciption", $metadescription);
        $smarty->assign("keywords", $keywords);
        
        $smarty->assign('title', $title);
        
        $smarty->assign('status_1', 'active');
        $smarty->display('homepage.tpl');
        
      } else {
          
        $sql = "SELECT * FROM `submissions` WHERE `url` = '$last_url_part_path'";

        $articly= $conn->query($sql);       
        
        $sql = "SELECT * FROM `submissions`";
        $i=0;
        foreach($conn->query($sql) as $article) {
            $articles[$i] = $article;  
            $i++;
        }
        
        $sql = "SELECT * FROM `submissions` WHERE `url` = '$last_url_part_path'";
        foreach($conn->query($sql)as $item) {
          $idd = $item['idd'];
        }
        
        $sql = "SELECT * FROM `comments` WHERE `submission_id` = '$idd'";
        $comments = $conn->query($sql);
        $smarty->assign('comments', $comments);
   
        $smarty->assign("articly", $articly);
         $smarty->assign('status_2', 'active');
        $smarty->assign("title", $title);
        $smarty->assign("metadesciption", $metadescription);
        $smarty->assign("keywords", $keywords);

        $smarty->display('article.tpl');
        
      }

}