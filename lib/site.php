<?php

require_once __DIR__ . '/config.php';

class Site {
    private $conn; // Database connection
    private $primaryKeyCache = array();

    /**
     * Initializes a new instance of the Site class.
     */
    public function __construct() {
        global $servername, $database, $username, $password;

        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$database;", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Handle the exception here, e.g., log or display an error message
            echo "Database connection failed: " . $e->getMessage();
        }
    }

    /**
     * Retrieves the primary ID column name for a given table.
     *
     * @param string $table The table name
     * @return string|null The primary ID column name, or null if not found
     */
    public function getPrimaryId($table) {
        if (isset($this->primaryKeyCache[$table])) {
            return $this->primaryKeyCache[$table];
        }

        $sql = "SHOW KEYS FROM `$table` WHERE `Key_name` = 'PRIMARY'";
        foreach($this->conn->query($sql) as $row) {
            $primaryKey = $row['Column_name'];
            $this->primaryKeyCache[$table] = $primaryKey;
            return $primaryKey;
        }
        return null;
    }

    /**
     * Inserts data into a specified table based on the provided post data.
     *
     * @param array $post The post data
     * @param string $table The table name
     * @return void
     */
public function insertData($post, $table) {
        
    $id_name = $this->getPrimaryId($table);
    $last_id = null; // Initialize last_id variable
    $params = array();
    $columns = array();
    $values = array();

    // Extract valid columns and values from the post data
    foreach($post as $key => $value) {
        if ($this->fieldExists($table, $key)) {
            $columns[] = $key;
            $values[] = '?';
            $params[] = $value;
        }
    }

    if (!empty($columns)) {
        // Prepare and execute the SQL statement
        $sql = "INSERT INTO `$table` (`" . implode("`, `", $columns) . "`) VALUES (" . implode(", ", $values) . ")";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        
        $last_id = $this->getLastId($table,  $id_name);

        // Return the last inserted ID
        return $last_id;
    }

    return null;
}

    


    /**
     * Updates data in a specified table based on the provided post data and ID.
     *
     * @param array $post The post data
     * @param string $table The table name
     * @param string|null $id_name The ID column name (null for all rows)
     * @param mixed|null $id_value The ID value (null for all rows)
     * @return void
     */
    public function updateDataByID($post, $table, $id_name = null, $id_value = null) {
        if ($id_name === null && $id_value === null) {
            // Update all rows with the provided data
            foreach($post as $key => $value) {
                if ($this->fieldExists($table, $key)) {
                    $sql = "UPDATE `$table` SET `$key` = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute(array($value));
                }
            }
        } else {
            // Update rows with the provided ID and data
            foreach($post as $key => $value) {
                if ($this->fieldExists($table, $key)) {
                    $sql = "UPDATE `$table` SET `$key` = ? WHERE `$id_name` = ?";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->execute(array($value, $id_value));
                }
            }
        }
    }

    /**
     * Delete data in a specified table based on the provided post data and ID.
     *
     * @param array $post The post data
     * @param string $tableName The table name
     * @param string $columnName The column name
     * @param mixed $id_value The ID value
     */
    public function deleteDataByID($post, $table, $id_name, $id_value) {
        $sql = "DELETE FROM `$table` WHERE `$id_name` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id_value]);
    }

    /**
     * Checks if a given column exists in the specified table.
     *
     * @param string $tableName The table name
     * @param string $columnName The column name
     * @return bool True if the column exists, false otherwise
     */
    public function fieldExists($tableName, $columnName) {
        $sql = "DESCRIBE `$tableName`";
        foreach($this->conn->query($sql) as $row) {
            if ($row['Field'] === $columnName) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Checks if a given column exists in the specified table.
     *
     * @param string $tableName The table name
     * @param string $columnName The column name
     * @return bool True if the column exists, false otherwise
     */
    public function redirect($page) {
            header("Location: $page");
            exit(); 
    }
    /**
     * Retrieves an array of all articles from the "submissions" table.
     *
     * @return array The array of articles
     */
    public function getArticlesArray() {
        $sql = "SELECT * FROM `submissions`";
        $stmt = $this->conn->query($sql);
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $articles;
    }



    /**
     * Retrieves an array of all articles from the "submissions" table.
     *
     * @return array The array of articles
     */
    public function getArticlesByPage($website, $number) {
        $width = $website['pages']['all']['articles_per_page'];
        $articles = $this->getArticlesArray();
        $total_articles = count($articles);
        $chunk_size = ceil($total_articles / $width);
        $pages_number = ceil($total_articles / $chunk_size);
        $start_index = ($number - 1) * $chunk_size;

        if ($start_index >= $total_articles) {
            return [];
        }

        return ['pages_number'=> $pages_number, 'articles' => array_slice($articles, $start_index, $width)];
    }

    public function setPager($website, $smarty, $pages_number, $current_page) {
        
        global $span;

        if (!$current_page){
            $current_page = 1;
        }

        if ($current_page - $span > 1) {
            $first = 1;
            $smarty->assign('first', $first);
        }

        $start = max(1, $current_page - $span);
        $smarty->assign('start', $start);

        $smarty->assign('current_page', $current_page);

        $end = min($pages_number, $current_page + $span);
        $smarty->assign('end', $end);

        if ($current_page + $span < $pages_number) {
            $last = $pages_number;
            $smarty->assign('last', $last);
        }
    }

    /**
     * Sets the base URL in the Smarty template.
     *
     * @param Smarty $smarty The Smarty template engine
     * @return void
     */
    public function setBaseUrl($smarty) {
        
        $host = trim((string) ($_SERVER['HTTP_HOST'] ?? ''));
        $prefix = function_exists('ep_public_base_url_prefix') ? ep_public_base_url_prefix() : 'https://';
        $smarty->assign('base_url', $prefix . $host);
        
    }

    /**
     * Retrieves the first URL path segment from the current request.
     *
     * @return string The first URL path segment
     */
    public function getFirstUrlPath() {
        $url_path = $_SERVER['REQUEST_URI'];
        $first_url_path = strtok($url_path, '/');
        return $first_url_path;
    }

    /**
     * Retrieves the last URL path segment from the current request, excluding the base directory.
     *
     * @param string $base_dir The base directory name
     * @return string|null The last URL path segment, or null if not found
     */
    public function getLastUrlPath($base_dir) {
        $url = basename($_SERVER['REQUEST_URI']);
        $sPath = parse_url($url, PHP_URL_PATH);   // parse URL and return only path component
        $aPath = explode('/', trim($sPath, '/'));  // remove surrounding "/" and return parts into array
        end($aPath);                               // last element of array
        if (current($aPath) !== $base_dir) {
            return current($aPath);
        }
        return ' ';
    }

    /**
     * Retrieves the query parameter value from the last URL path segment.
     *
     * @return string|null The query parameter value, or null if not found
     */
    public function getUrlQuery() {
        $last_url_part = basename($_SERVER['REQUEST_URI']);
        $last_url_part_query = substr($last_url_part, strrpos($last_url_part, '=') + 1);
        return $last_url_part_query;
    }

    /**
     * Retrieves an article from the "submissions" table based on the provided URL.
     *
     * @param string $url The article URL
     * @return array|null The article data, or null if not found
     */
    public function getArticleByURL($url) {
        $sql = "SELECT * FROM `submissions` WHERE `url` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$url]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        return $article;
    }

    /**
     * Retrieves the last inserted ID from the specified table and column.
     *
     * @param string $table The table name
     * @param string $id The ID column name
     * @return mixed|null The last inserted ID, or null if not found
     */
    public function getLastId($table, $id) {
        $sql = "SELECT * FROM $table ORDER BY $id DESC LIMIT 1";
        foreach($this->conn->query($sql) as $row) {
            $last_id = $row[$id];
        }
        return $last_id;
    }

    /**
     * Retrieves the last submitted article from the "submissions" table.
     *
     * @return array|null The last submitted article data, or null if not found
     */
    public function getLastArticle() {
        $sql = "SELECT * FROM submissions ORDER BY submission_id DESC LIMIT 1";
        $stmt = $this->conn->query($sql);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        return $article;
    }

    /**
     * Retrieves the comments for the last submitted article.
     *
     * @return array|null The array of comments, or null if not found
     */
    public function getLastArticleComments() {
        $sql = "SELECT * FROM submissions ORDER BY submission_id DESC LIMIT 1";
        $stmt = $this->conn->query($sql);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($article) {
            $submission_id = $article['idd'];
            $sql = "SELECT * FROM `comments` WHERE `submission_id` = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$submission_id]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $comments;
        }
        return null;
    }

    /**
     * Sets the current date and year in the Smarty template.
     *
     * @param Smarty $smarty The Smarty template engine
     * @return void
     */
    public function setDates($smarty) {
        $smarty->assign('date', date("Y-m-d"));
        $smarty->assign('year', date("Y"));
    }

    /**
     * Retrieves the comments for a specific article based on the provided URL.
     *
     * @param string $url The article URL
     * @return array|null The array of comments, or null if not found
     */
    public function getArticleCommentsByURL($url) {
        $article = $this->getArticleByURL($url);
        if ($article) {
            $submission_id = $article['submission_id'];
            $sql = "SELECT * FROM `comments` WHERE `submission_id` = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$submission_id]);
            $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $comments;
        }
        return null;
    }

    /**
     * Splits an array into multiple slices of specified lengths.
     *
     * @param array $array The input array
     * @param array $slices The slice lengths
     * @return array The array of sliced arrays
     */
    public function getSlices($array, $slices) {
        
        $size = sizeof($array);
        $lengthSum = 0;
        $correctedSlices = [];
        
        foreach ($slices as $label => $length) {
            $lengthSum += $length;
            if ($lengthSum > $size) {
                $length = $size - ($lengthSum - $length);
                $length = max(0, $length);
            }
            
            $correctedSlices[$label] = $length;
        }
        
        $arrays = [];
        $startIndex = $size - array_sum($correctedSlices);
        foreach ($correctedSlices as $label => $length) {
            $arrays[$label] = array_slice($array, $startIndex, $length);
            $startIndex += $length;
        }
        
        return $arrays;
        
    }

    function distributeArticles($max_home_articles, $blocks) {
        
        $last_block_label = null;
        $last_block_articles = 0;
        $main_blocks = [];
    
        // Separate the 'last' block and main blocks
        foreach ($blocks as $block_label) {
            $blocks_with_numbers[$block_label] = (int) filter_var($block_label, FILTER_SANITIZE_NUMBER_INT);
    
            if (strpos($block_label, 'last') !== false) {
                $last_block_label = $block_label;
                $last_block_articles = $blocks_with_numbers[$block_label];
            } elseif (strpos($block_label, 'main') !== false) {
                $main_blocks[] = $block_label;
            }
        }
    
        // Ensure 'last' block has the exact number of articles mentioned in its label
        if ($last_block_label !== null) {
            $blocks_with_numbers[$last_block_label] = max($last_block_articles, $blocks_with_numbers[$last_block_label]);
        }
    
        $total_main_blocks = count($main_blocks);
        $total_main_articles = array_sum(array_intersect_key($blocks_with_numbers, array_flip($main_blocks)));
        $remaining_articles = $max_home_articles - array_sum($blocks_with_numbers);
    
        if ($remaining_articles > 0 && $total_main_blocks > 0) {
            $articles_per_main_block = floor($remaining_articles / $total_main_blocks);
            $articles_remainder = $remaining_articles % $total_main_blocks;
    
            foreach ($main_blocks as $main_block) {
                $blocks_with_numbers[$main_block] += $articles_per_main_block;
            }
    
            // Distribute the remaining articles among the main blocks one by one
            for ($i = 0; $i < $articles_remainder; $i++) {
                $blocks_with_numbers[$main_blocks[$i]]++;
            }
        }
    
        // Ensure the article numbers are non-negative
        foreach ($blocks_with_numbers as $block_label => $article_number) {
            $corrected_blocks[$block_label] = max(0, $article_number);
        }
    
        return $corrected_blocks;
    }


    /**
     * Sets the article blocks in the Smarty template.
     *
     * @param array $website The website data
     * @param array $blocks The number of blocks for each section
     * @param Smarty $smarty The Smarty template engine
     * @return void
     */
    public function setBlocks($website, $blocks, $smarty) {
        
        $pages = $website['pages'];
        $articles = $this->getArticlesArray();
        $max_home_articles = $website['pages']['home']['max_articles_number'];
        
        //disributing the $max_home_articles number on the blocks
        $blocks = $this->distributeArticles($max_home_articles, $blocks);
        $slices = $this->getSlices($articles, $blocks);
        foreach ($slices as $label => $array) {
            if(sizeof($array)==1){
                $smarty->assign($label, $array[0]);
            } else {
                $smarty->assign($label, $array);
            }
        }
        
    }
    
    public function setHomePageArticles($website, $smarty) {
        
        $articles = $this->getArticlesArray();
        $max_articles_number = $website['pages']['home']['max_articles_number'];
        
        $slices = $this->getSlices($articles, ['homePageArticles'=>$max_articles_number]);
        foreach ($slices as $label => $array) {
            $smarty->assign($label, $array);
        }
        
    }
    /**
     * Retrieves the pages from the website data.
     *
     * @param array $website The website data
     * @return array The array of pages
     */
    public function getPages($website) {
        
        $pages = $website['pages'];
        return $pages;
    
    }


    /**
     * Assign images folder to pages from the website data.
     *
     * @param string $images_folder image folder name
     * @param Smarty $smarty The Smarty template engine
     * @return void
     */
    public function setImagesFolder($images_folder, $smarty) {
        $smarty->assign('images_folder', $images_folder);
    }

    /**
     * Assign URL and titles to pages from the website data.
     *
     * @param array $pages Pages title and URL array
     * @param Smarty $smarty The Smarty template engine
     * @return void
     */
    public function setPages($pages, $smarty) {
        $smarty->assign('pages', $pages);
    }

    /**
     * Sets the footer sections in the Smarty template.
     *
     * @param array $website The website data
     * @param Smarty $smarty The Smarty template engine
     * @return void
     */
    public function setFooter($website, $smarty) {
        $pages = $website['pages'];
        unset($pages['404']);
        $articles = $this->getArticlesArray();
        $smarty->assign('footer_left', $this->getFooter($articles, $pages, $website['footer'], 'left'));
        $smarty->assign('footer_right', $this->getFooter($articles, $pages, $website['footer'], 'right'));
    }

    /**
     * Saves a file from the provided URL.
     *
     * @param string $url The file URL
     * @param string $name The file name to save
     * @param string|null $folder The folder name (optional)
     * @return void
     */
    public function saveFile($url, $name, $folder) {
        if ($folder) {
            $folder = $folder . '/';
        }
        file_put_contents($folder . $name, file_get_contents($url));
    }

    /**
     * Retrieves the footer sections based on the articles, pages, and custom footers.
     *
     * @param array $articles The array of articles
     * @param array $pages The array of pages
     * @param string $custom_footers The custom footer JSON string
     * @param string $side The footer side ("left" or "right")
     * @return array The array of footer items
     */
    public function getFooter($articles, $pages, $custom_footers, $side) {
        
        global $footer_height;
        $footer_left = [];
        $footer_right = [];

        $slices = $this->getSlices($articles, ['footer_left' => $footer_height, 'footer_right' => $footer_height]);
        foreach ($slices['footer_left'] as $item) {
            $footer_left[] = ['title' => $item['title'], 'url' => $item['url']];
        }

        foreach ($pages as $item) {
            if ($item['url']!=''){
                $footer_right[] = ['title' => $item['title'], 'url' => $item['url']];
            }
        }

        $slices = $this->getSlices($custom_footers, ['custom_footer_left' => 4, 'custom_footer_right' => 4]);
        foreach ($slices['custom_footer_left'] as $item) {
            if ($item['url']) {
                $footer_left[] = ['title' => $item['title'], 'url' => $item['url']];
            }
        }

        foreach ($slices['custom_footer_right'] as $item) {
            if ($item['url']) {
                $footer_right[] = ['title' => $item['title'], 'url' => $item['url']];
            }
        }

        $slices_left = $this->getSlices($footer_left, ['footer_left' => $footer_height]);
        $slices_right = $this->getSlices($footer_right, ['footer_right' => $footer_height]);

        if ($side === 'left') {
            return $slices_left['footer_left'];
        } elseif ($side === 'right') {
            return $slices_right['footer_right'];
        }
    }

    /**
     * Retrieves the site settings from the "web_settings" table.
     *
     * @return array|null The site settings data, or null if not found
     */
    public function getSiteSettings() {
        $sql = "SELECT * FROM `web_settings`";
        $stmt = $this->conn->query($sql);
        $setting = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$setting) {
            return [];
        }

        // Older dumps only have plain text `contact`; optional JSON columns may be absent.
        $jsonKeys = ['pages', 'social', 'footer', 'article_page', 'subscription', 'files', 'meta', 'indexing'];
        foreach ($jsonKeys as $key) {
            if (!isset($setting[$key]) || $setting[$key] === '' || $setting[$key] === null) {
                $setting[$key] = null;
                continue;
            }
            $raw = $setting[$key];
            if (!is_string($raw)) {
                continue;
            }
            $trim = ltrim($raw);
            if ($trim !== '' && ($trim[0] === '{' || $trim[0] === '[')) {
                $decoded = json_decode($raw, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $setting[$key] = $decoded;
                }
            }
        }

        return $setting;
    }
    
    
    /**
     * Save Files from the post data.
     *
     * @return array|null The site settings data, or null if not found
     */
    public function prepareFiles($files, $images_folder) {
       $files = [ 
                     [
                        'url'=> $files['robots']['file_url'], 
                        'file_name'=> $files['robots']['file_name'], 
                        'folder'=> null 
                     ],
                     
                     [
                         'url'=> $files['htaccess']['file_url'], 
                         'file_name'=> $files['htaccess']['file_name'], 
                         'folder'=> null 
                     ],
                     
                     [
                         'url'=> $files['contact']['image']['image_url'],
                         'file_name'=> $files['contact']['image']['image_name'], 
                         'folder'=> $images_folder 
                     ],
                     
                     [
                         'url'=> $files['logo']['large']['image_url'],
                         'file_name'=> $files['logo']['large']['image_name'],
                         'folder'=> $images_folder
                      ],
                      
                     [
                         'url'=> $files['logo']['small']['image_url'], 
                         'file_name'=> $files['logo']['small']['image_name'], 
                         'folder'=> $images_folder
                     ]
            ];
        return $files;
        }      

    public function saveFiles($post, $images_folder) {
        
            $files = json_decode($post['files'], true);
            $files = $this->prepareFiles ($files, $images_folder);
            foreach ($files as $file)
            {   
                $this->saveFile($file['url'], $file['file_name'], $file['folder']);
            }
    
        }      
    

    public function getUniqueIPsAndURLs() {
        // Retrieve unique IPs and their associated URLs
        $sql = "SELECT ip, GROUP_CONCAT(DISTINCT url SEPARATOR ', ') AS unique_urls FROM visitors GROUP BY ip";
        $stmt = $this->conn->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function recordVisitor($url) {
        $visitorIP = $_SERVER['REMOTE_ADDR'];
        $currentTime = new DateTime();
        $formattedCurrentTime = $currentTime->format("Y-m-d H:i:s");

        // Fetch the last recorded time for this IP and URL combination
        $lastRecordedTime = $this->getLastRecordedTime('visitors', 'ip', 'url', $visitorIP, $url);

        if (!$lastRecordedTime || $this->isTimeDifferenceGreaterThan1Hour($lastRecordedTime, $formattedCurrentTime)) {
            $visitor = ['ip' => $visitorIP, 'url' => $url, 'time' => $formattedCurrentTime];
            
            $pageId = $this->insertData($visitor, 'visitors');
            $smarty->assign('pageId', $pageId);
        }
    }

    // Function to fetch the last recorded time for the IP and URL combination
    private function getLastRecordedTime($table, $ipField, $urlField, $ip, $url) {
        $sql = "SELECT time FROM $table WHERE $ipField = ? AND $urlField = ? ORDER BY time DESC LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$ip, $url]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['time'])) {
            return $result['time'];
        }

        return null;
    }

    private function isTimeDifferenceGreaterThan1Hour($startTime, $endTime) {
        $startTimestamp = strtotime($startTime);
        $endTimestamp = strtotime($endTime);
        $timeDifference = $endTimestamp - $startTimestamp;
        $oneHourInSeconds = 3600; // 1 hour in seconds
        return $timeDifference > $oneHourInSeconds;
    }

       
    /**
     * Process and update data based on the provided post data.
     *
     * @param array $post The post data
     * @return void
     */
    public function receiveAndUpdate($post, $website, $smarty) {
        global $images_folder;
        $website = is_array($website) ? $website : [];
        $pages = $website['pages'] ?? [];
        $subscription = $website['subscription'] ?? [];
        $articlePage = $website['article_page'] ?? [];
        $contactBtn = is_array($pages) && isset($pages['contact']['button_name']) ? $pages['contact']['button_name'] : null;
        $subBtn = is_array($subscription) && isset($subscription['button_name']) ? $subscription['button_name'] : null;
        $commentBtn = is_array($articlePage) && isset($articlePage['comment']['button_name'])
            ? $articlePage['comment']['button_name']
            : null;

        switch (true) {

            case isset($post['is_setting']):
                // Update website settings
                $this->updateDataByID($post, 'web_settings', null, null);
                $this->saveFiles($post, $images_folder);
                break;

            case isset($post['is_comment']):
                // Insert comments
                $this->insertData($post, 'comments');
                break;

            case isset($post['is_delete']):
                // Delete submission
                $this->deleteDataByID($post, 'submissions', 'idd', $post['id']);
                break;

            case isset($post['is_article_update']):
                // Update submission
                $this->updateDataByID($post, 'submissions', 'idd', $post['idd']);
                $this->saveFile($post['image_url'], $post['image'], $images_folder);
                break;

            case isset($post['is_new_article']):
                // Insert submission
                $this->insertData($post, 'submissions');
                $this->saveFile($post['image_url'], $post['image'], $images_folder);
                break;

            case $contactBtn !== null && isset($post[$contactBtn]):
                // Insert Messages
                $this->insertData($post, 'messages');
                $smarty->assign('received', true);
                break;

            case $subBtn !== null && isset($post[$subBtn]):
                // Insert Subscription
                $this->insertData($post, 'emails');
                $smarty->assign('subscribed', true);
                break;

            case $commentBtn !== null && isset($post[$commentBtn]):
                // Insert Comments
                $this->insertData($post, 'comments');
                break;

            case isset($post['is_leaving']):
                $_POST['pageId'] = $_SERVER['REMOTE_ADDR'];
                $this->updateDataByID($post, 'visitors', 'pageId', $post['pageId']);
                break;

        }
    }
}
