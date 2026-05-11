<?php

/**
 * Read a non-empty value from $_ENV / getenv (after lib/config.php loads .env).
 */
function ep_env(string $key, string $default = ''): string
{
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
        return (string) $_ENV[$key];
    }
    $v = getenv($key);

    return ($v !== false && $v !== '') ? (string) $v : $default;
}

/**
 * Scheme for absolute links in templates: always https on public hosts unless FORCE_HTTP / localhost.
 */
function ep_public_base_url_prefix(): string
{
    if (strtolower(ep_env('FORCE_HTTP', '')) === 'true') {
        return 'http://';
    }
    $h = $_SERVER['HTTP_HOST'] ?? '';
    if ($h === '' || strcasecmp($h, 'localhost') === 0 || str_starts_with($h, '127.')
        || str_starts_with($h, '192.168.') || str_starts_with($h, '10.')) {
        return 'http://';
    }

    return 'https://';
}

/**
 * True when Apache/PHP sees a non-TLS request (no forwarded-header guessing).
 * Set HTTPS_TRUST_PROXY=true in `.env` if TLS terminates in front of Apache and you get redirect loops.
 */
function ep_connection_is_plain_http(): bool
{
    if (strtolower(ep_env('HTTPS_TRUST_PROXY', '')) === 'true') {
        $xf = strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
        if ($xf === 'https') {
            return false;
        }
    }

    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return false;
    }
    if ((string) ($_SERVER['SERVER_PORT'] ?? '') === '443') {
        return false;
    }
    if (strtolower((string) ($_SERVER['REQUEST_SCHEME'] ?? '')) === 'https') {
        return false;
    }

    return true;
}

function generateRandomString($length = 6) {
    $characters = '123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}


function generateRandomLink($length = 11) {
    $characters = '123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateIOI($length = 11) {
    $characters = '123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}


function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

// Makes the first character of the sentence upper case. It checks if the first word is not abbrivation to keep it unchanged.
function lc_first_word($sentence){
    
    // checking if the first word of the title is abbrivation if yes do not lower case it 
    $first_title_letter = mb_substr($sentence, 0, 1);
    
    if (mb_substr($sentence, 1, 1)!='.' && mb_substr($sentence, 1, 1)!=' '){
        $second_title_letter = mb_substr($sentence, 1, 1);
    } elseif(mb_substr($sentence, 1, 1)=='.') {
        $second_title_letter = mb_substr($sentence, 2, 1);
    } 
    
    if (ctype_upper($first_title_letter) && !ctype_upper($second_title_letter)){
        
        $lc_first_sentence = lcfirst($sentence);
    } else {
        $lc_first_sentence = $sentence;
    }
    
    return $lc_first_sentence;
}


function keyword_extract($text){
   
        $unimportant_words = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'more','the','this', 'i', 'you', 'he', 'she', 'they', 'we', 'it','my', 'your', 'his', 'him', 'her', 'their', 'our', 'that', 'use', 'am', 'is', 'are', 'be', 'do', 'does', 'can', 'have', 'has', 'could', 'should', 'would', 'how', 'so', 'then', 'a', 'an', 'the', 'and', 'or', 'but', 'aboard', 'about', 'above', 'across', 'after', 'against', 'along', 'amid', 'among', 'anti', 'around', 'as', 'at', 'now', 'when', 'then','before', 'behind', 'below', 'beneath', 'beside', 'besides', 'between', 'beyond', 'but', 'by', 'concerning', 'considering', 'despite', 'down', 'during', 'except', 'excepting', 'excluding', 'following', 'for', 'from', 'in', 'inside', 'into', 'like', 'minus', 'near', 'of', 'off', 'on', 'onto', 'opposite', 'outside', 'over', 'past', 'per', 'plus', 'regarding', 'round', 'save', 'since', 'than', 'through', 'to', 'toward', 'towards', 'under', 'underneath', 'unlike', 'until', 'up', 'upon', 'versus', 'via', 'with', 'within', 'without', 'long', 'à', 'à côté de', 'après',  'au sujet de',  'avant', 'avec', 'chez', 'contre', 'dans', 'daprès',  'de', 'depuis', 'derrière' , 'devant', 'durant',  'en', 'en dehors de',  'en face de' , 'entre', 'envers', 'environ' ,'hors de',  'jusque' , 'loin de', 'malgré', 'par'  ,'parmi' , 'pendant' , 'pour', 'près de', 'quant à' , 'sans', 'selon',  'sous',  'suivant', 'sur', 'vers', 'le', 'la', 'les', 'un', 'une', 'des', 'du', 'de', 'la', 'plus', 'le', 'je', 'tu', 'il', 'elle', 'ils', 'nous', 'ça', 'mon', 'votre', 'son', 'lui ', 'elle', 'leur', 'notre', 'cela', 'suis', 'est', 'sont', 'être', 'faire', 'fait', 'peut', 'avoir', 'a', 'pourrait', 'devrait', 'ferait', 'comment', 'ainsi', 'alors', 'un', 'un', 'le', 'et', 'ou', 'mais ', 'à bord', 'environ', 'au-dessus', 'à travers', 'après', 'contre', 'le long', 'au milieu', 'parmi', 'anti', 'autour', 'comme', 'à', 'avant', 'derrière', 'en dessous', 'en dessous', 'à côté', 'à côté', 'entre', 'au-delà', 'mais', 'par', 'concernant', 'considérant ', 'malgré', 'bas', 'pendant', 'sauf', 'excluant', 'excluant', 'suivant', 'pour', 'de', 'dans', 'dedans', 'dans', 'comme', 'moins', 'près', 'de', 'hors', 'sur', 'sur', 'opposé', 'dehors', 'plus', 'passé', 'par', 'plus ', 'concernant', 'rond', 'sauver', 'depuis', 'que', 'à travers', 'à', 'vers', 'vers', 'en dessous', 'en dessous', 'contrairement', 'jusquà', 'en haut', 'sur', 'versus', 'via', 'avec', 'dans', 'sans', 'ce', 'ne', 'se', 'tes','au', 'tre', 'sa','si', 'na', 'ca', 'ma', 'ni', 'ans', 'ces', 'qui', 'vous', 'aux');
        $pattern = '/\b(?:' . join('|', $unimportant_words) . ')\b/i';
        $text = strip_tags($text); 
        //remove numbers from text
        $text = preg_replace('/\d+/u', '', $text);
        //remove nonalephabet
        $text = preg_replace("/[^A-Za-z0-9 ]/", '', $text);
        $important_words = preg_replace($pattern, '', $text); 
        $important_words = str_replace(",", " ", $important_words); 
        $important_words = str_replace(":", " ", $important_words); 
        $important_words = str_replace(".", " ", $important_words); 
        $important_words = str_replace('"', ' ', $important_words); 
        $important_words = trim(preg_replace('!\s+!', ' ', $important_words));
        $important_words = strtolower(str_replace(" ", ",", $important_words));
        return $important_words;
}

function keyword_analysis ($text) {
        
        $important_words = keyword_extract($text);
        $keywords_array= array_unique(explode(',', $important_words));
                  
        $i=0;
        foreach ($keywords_array as $keyword){
                $keywords[$i] = array("frequency"=>number_format((float)((substr_count($text, $keyword)/str_word_count($text))*100), 2, '.', ''), "keyword"=>$keyword);
                $i++;
        }
        arsort($keywords);
        return array_slice($keywords,0,4);
}

function randomPassword($len = 8) {

    //enforce min length 8
    if($len < 8)
        $len = 8;

    //define character libraries - remove ambiguous characters like iIl|1 0oO
    $sets = array();
    $sets[] = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    $sets[] = '23456789';
    $sets[]  = '~!@#$%^&*(){}[],./?';

    $password = '';
    
    //append a character from each set - gets first 4 characters
    foreach ($sets as $set) {
        $password .= $set[array_rand(str_split($set))];
    }

    //use all characters to fill up to $len
    while(strlen($password) < $len) {
        //get a random set
        $randomSet = $sets[array_rand($sets)];
        
        //add a random char from the random set
        $password .= $randomSet[array_rand(str_split($randomSet))]; 
    }
    
    //shuffle the password string before returning!
    return str_shuffle($password);
}


function string_to_url ($string) {
    $url = preg_replace('/\p{P}/', ' ', $string);
    $url = preg_replace('/[^\00-\255]+/u', ' ', $url);
    $url = preg_replace("/[^A-Za-z0-9 ]/", ' ', $url);
    $url = preg_replace('/\s+/', ' ', $url);
    $url = str_replace(' ','-', $url);
                $unwanted_array = array(
                'Š' => 'S',
                'š' => 's',
                'Ž' => 'Z',
                'ž' => 'z',
                'À' => 'A',
                'Á' => 'A',
                'Â' => 'A',
                'Ã' => 'A',
                'Ä' => 'A',
                'Å' => 'A',
                'Æ' => 'A',
                'Ç' => 'C',
                'È' => 'E',
                'É' => 'E',
                'Ê' => 'E',
                'Ë' => 'E',
                'Ì' => 'I',
                'Í' => 'I',
                'Î' => 'I',
                'Ï' => 'I',
                'Ñ' => 'N',
                'Ò' => 'O',
                'Ó' => 'O',
                'Ô' => 'O',
                'Õ' => 'O',
                'Ö' => 'O',
                'Ø' => 'O',
                'Ù' => 'U',
                'Ú' => 'U',
                'Û' => 'U',
                'Ü' => 'U',
                'Ý' => 'Y',
                'Þ' => 'B',
                'ß' => 'Ss',
                'à' => 'a',
                'á' => 'a',
                'â' => 'a',
                'ã' => 'a',
                'ä' => 'a',
                'å' => 'a',
                'æ' => 'a',
                'ç' => 'c',
                'è' => 'e',
                'é' => 'e',
                'ê' => 'e',
                'ë' => 'e',
                'ì' => 'i',
                'í' => 'i',
                'î' => 'i',
                'ï' => 'i',
                'ð' => 'o',
                'ñ' => 'n',
                'ò' => 'o',
                'ó' => 'o',
                'ô' => 'o',
                'õ' => 'o',
                'ö' => 'o',
                'ø' => 'o',
                'ù' => 'u',
                'ú' => 'u',
                'û' => 'u',
                'ý' => 'y',
                'þ' => 'b',
                'ÿ' => 'y'
            );
    $url = strtr($url, $unwanted_array);
    return $url;
}

function count_url ($string) {
    $url_number = substr_count($string,"src=") + substr_count($string,"href=");
    return $url_number; 
}


function check_empty($variable){
    if(isset($variable)){
        $checked_variable = $variable;
    } else {
        $checked_variable = '';
    }  
   return $checked_variable;
} 



function duplicate_check($scan_id){
$headers = array(
    "Authorization: Bearer xZa1zzRds7hjcZmUwAr8PtAcEziwjZDLNfd9iUAI",
    "Accept: application/json",
    "Content-Type: application/json",
);

$ch = curl_init("https://app.killduplicate.com/api/public/scan/".$scan_id);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);
$result= json_decode($result);
$status= $result->status;
$data = $result->data;
$is_dublicated= $data->duplicate;

$duplicate_percentage= $data->dup_percentage;
$used_credits = $data->credits;
//$data->phrases_checked;

$similar_results;
$data = $data->results;
foreach ($data as $key=>$value) {
    $similar_results .= $key.' Similarity: ('.$value.'%)</br>';
}

$check_result = ["status"=>$status, "is_duplicated"=>$is_duplicated, "duplicate_percentage"=>$duplicate_percentage, "used_credits"=>$used_credits, "similar_results"=>$similar_results];

return $check_result;
}