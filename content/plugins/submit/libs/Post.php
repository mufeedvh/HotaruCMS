<?php
/**
 * The Post class contains some useful methods for using posts
 *
 * PHP version 5
 *
 * LICENSE: Hotaru CMS is free software: you can redistribute it and/or 
 * modify it under the terms of the GNU General Public License as 
 * published by the Free Software Foundation, either version 3 of 
 * the License, or (at your option) any later version. 
 *
 * Hotaru CMS is distributed in the hope that it will be useful, but WITHOUT 
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
 * FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * You should have received a copy of the GNU General Public License along 
 * with Hotaru CMS. If not, see http://www.gnu.org/licenses/.
 * 
 * @category  Content Management System
 * @package   HotaruCMS
 * @author    Nick Ramsay <admin@hotarucms.org>
 * @copyright Copyright (c) 2009, Hotaru CMS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link      http://www.hotarucms.org/
 */
    
class Post
{    
    public $db;                         // database object
    public $cage;                       // Inspekt object
    public $hotaru;                     // Hotaru object
    public $lang            = array();  // stores language file content
    public $plugins;                    // PluginFunctions object
    public $current_user;               // UserBase object
    
    protected $id = 0;
    protected $origUrl = '';
    protected $domain = '';              // the domain of the submitted url
    protected $title = '';
    protected $content = '';
    protected $contentLength = 50;      // default min characters for content
    protected $summary = '';
    protected $summaryLength = 200;     // default max characters for summary
    protected $status = 'unsaved';
    protected $author = 0;
    protected $url = '';
    protected $date = '';
    protected $subscribe = 0;
    protected $postsPerPage = 10;
    protected $allowableTags = '';

    protected $templateName = '';
            
    protected $useLatest = false;       // Split posts into "Top" and "Latest" pages
    protected $useSubmission = true;
    protected $useAuthor = true;
    protected $useDate = true;
    protected $useContent = true;
    protected $useSummary = true;

    public $vars = array();


    /**
     * Build a $plugins object containing $db and $cage
     */
    public function __construct($hotaru)
    {
        $this->hotaru           = $hotaru;
        $this->db               = $hotaru->db;
        $this->cage             = $hotaru->cage;
        $this->lang             = &$hotaru->lang;   // reference to main lang array
        $this->plugins          = $hotaru->plugins;
        $this->current_user     = $hotaru->current_user;
    }
    
    
    /**
     * Access modifier to set protected properties
     */
    public function __set($var, $val)
    {
        $this->$var = $val;  
    }
    
    
    /**
     * Access modifier to get protected properties
     */
    public function __get($var)
    {
        return $this->$var;
    }

    
    /* *************************************************************
     *              REGULAR METHODS
     * ********************************************************** */
     
    
    /**
     * Get all the settings for the current post
     *
     * @param int $post_id - Optional row from the posts table in the database
     * @return bool
     */    
    public function readPost($post_id = 0)
    {
        // Get settings from database if they exist...
        $submit_settings = $this->plugins->getSerializedSettings('submit');
        
        // Assign settings to class member
        $this->contentLength = $submit_settings['post_content_length'];
        $this->summaryLength = $submit_settings['post_summary_length'];
        $this->postsPerPage = $submit_settings['post_posts_per_page'];
        $this->allowableTags = $submit_settings['post_allowable_tags'];

        $use_submission = $submit_settings['post_enabled'];
        $use_author = $submit_settings['post_author'];
        $use_date = $submit_settings['post_date'];
        $use_content = $submit_settings['post_content'];
        $use_summary = $submit_settings['post_summary'];
        
        //enabled
        if ($use_submission == 'checked') { $this->useSubmission = true;  } else { $this->useSubmission = false;  }
        
        //author
        if ($use_author == 'checked') { $this->useAuthor = true; } else { $this->useAuthor = false;  }
        
        //date
        if ($use_date == 'checked') { $this->useDate = true; } else { $this->useDate = false;  }
        
        //content
        if ($use_content == 'checked') { $this->useContent = true; } else { $this->useContent = false;  }
        
        //summary
        if ($use_summary == 'checked') { $this->useSummary = true; } else { $this->useSummary = false; }
                
        $this->plugins->pluginHook('post_read_post_1');
        
        if ($post_id != 0) {
            $post_row = $this->getPost($post_id);
            $this->title = stripslashes(urldecode($post_row->post_title));
            $this->content = stripslashes(urldecode($post_row->post_content));
            $this->id = $post_row->post_id;
            $this->origUrl = urldecode($post_row->post_orig_url);            
            $this->status = $post_row->post_status;
            $this->author = $post_row->post_author;
            $this->url = urldecode($post_row->post_url);
            $this->date = $post_row->post_date;
            $this->subscribe = $post_row->post_subscribe;
            
            $this->vars['post_row'] = $post_row;    // make available to plugins
            
            $this->plugins->pluginHook('post_read_post_2');
                        
            return true;
        } else {
            return false;
        }
        
    }
    
    
    /**
     * Add a post to the database
     *
     * @return true
     */    
    public function addPost()
    {
        $this->domain = get_domain($this->origUrl); // returns domain including http:// 
            
        $sql = "INSERT INTO " . TABLE_POSTS . " SET post_orig_url = %s, post_domain = %s, post_title = %s, post_url = %s, post_content = %s, post_status = %s, post_author = %d, post_date = CURRENT_TIMESTAMP, post_subscribe = %d, post_updateby = %d";
        
        $this->db->query($this->db->prepare($sql, urlencode($this->origUrl), urlencode($this->domain), urlencode(trim($this->title)), urlencode(trim($this->url)), urlencode(trim($this->content)), $this->status, $this->author, $this->subscribe, $this->current_user->id));
        
        $last_insert_id = $this->db->get_var($this->db->prepare("SELECT LAST_INSERT_ID()"));
        
        $this->id = $last_insert_id;
        $this->vars['last_insert_id'] = $last_insert_id;    // make it available outside this class
                
        $this->plugins->pluginHook('post_add_post');
        
        return true;
    }
    
    
    /**
     * Update a post in the database
     *
     * @return true
     */    
    public function updatePost()
    {
        $parsed = parse_url($this->origUrl);
        if (isset($parsed['scheme'])){ $this->domain = $parsed['scheme'] . "://" . $parsed['host']; }
        
        $sql = "UPDATE " . TABLE_POSTS . " SET post_orig_url = %s, post_domain = %s, post_title = %s, post_url = %s, post_content = %s, post_status = %s, post_author = %d, post_subscribe = %d, post_updateby = %d WHERE post_id = %d";
        
        $this->db->query($this->db->prepare($sql, urlencode($this->origUrl), urlencode($this->domain), urlencode(trim($this->title)), urlencode(trim($this->url)), urlencode(trim($this->content)), $this->status, $this->author, $this->subscribe, $this->current_user->id, $this->id));
        
        $this->plugins->pluginHook('post_update_post');
        
        return true;
    }
    
    
    /**
     * Update a post's status
     *
     * @return true
     */    
    public function changeStatus($status = "processing")
    {
        $this->status = $status;
            
        $sql = "UPDATE " . TABLE_POSTS . " SET post_status = %s WHERE post_id = %d";
        $this->db->query($this->db->prepare($sql, $this->status, $this->id));        
        return true;
    }


    /**
     * Gets a single post from the database
     *
     * @return array|false
     */    
    public function getPost($post_id = 0)
    {

        $sql = "SELECT * FROM " . TABLE_POSTS . " WHERE post_id = %d ORDER BY post_date DESC";
        $post = $this->db->get_row($this->db->prepare($sql, $post_id));
        if ($post) { return $post; } else { return false; }
    }
    

    /**
     * Physically delete a post from the database 
     *
     * There's a plugin hook in here to delete their parts, e.g. votes, coments, tags, etc.
     */    
    public function deletePost()
    {
        $sql = "DELETE FROM " . TABLE_POSTS . " WHERE post_id = %d";
        $this->db->query($this->db->prepare($sql, $this->id));
        
        $this->plugins->pluginHook('post_delete_post');
        
    }
    
    
    /**
     * Gets all the posts from the database
     *
     * @param array $vars - search parameters
     * @param int $limit - no. of rows to retrieve
     * @param bool $all - true to retrieve ALL rows, else default 20
     * @param string $select - the select clause
     * @param string $orderby - the order by clause
     * @return array|false $prepare_array is the prepared SQL statement
     *
     * Example usage: $post->filter(array('post_tags LIKE %s' => '%tokyo%'), 10);
     */    
    public function filter($vars = array(), $limit = 0, $all = false, $select = '*', $orderby = 'post_date DESC')
    {
        if(!isset($filter)) { $filter = ''; }
        $prepare_array = array();
        $prepare_array[0] = "temp";    // placeholder to be later filled with the SQL query.
        
        if (!empty($vars)) {
            $filter = " WHERE ";
            foreach ($vars as $key => $value) {
                $filter .= $key . " AND ";    // e.g. " post_tags LIKE %s "
                array_push($prepare_array, $value);
            }
            $filter = rstrtrim($filter, "AND ");
        }
        
        if ($all == true) {
            $limit = '';
        } elseif ($limit == 0) { 
            $limit = "LIMIT 20"; 
        } else { 
            $limit = "LIMIT " . $limit; 
        }
        
        if ($orderby) { $orderby = "ORDER BY " . $orderby; }
        
        $sql = "SELECT " . $select . " FROM " . TABLE_POSTS . $filter . " " . $orderby . " " . $limit;
        
        $prepare_array[0] = $sql;
        
        // $prepare_array needs to be passed to $this->db->prepare, i.e. $this->db->get_results($this->db->prepare($prepare_array));
                
        if ($prepare_array) { return $prepare_array; } else { return false; }
    }
    
    
    /**
     * Gets all the posts from the database
     *
     * @param array $prepared array - prepared SQL statement from filter()
     * @return array|false - array of posts
     */    
    public function getPosts($prepared_array = array())
    {
        if (!empty($prepared_array)) {
            if (empty($prepared_array[1])) {
                $posts = $this->db->get_results($prepared_array[0]); // ignoring the prepare function.
            } else {
                $posts = $this->db->get_results($this->db->prepare($prepared_array)); 
            }
            if ($posts) { return $posts; }
        }
        
        return false;
    }
    
    
    /**
     * Publish content as an RSS feed
     * Uses the 3rd party RSS Writer class.
     */    
    public function rssFeed()
    {
        require_once(EXTENSIONS . 'RSSWriterClass/rsswriter.php');
        
        $select = '*';
        
        $status = $this->cage->get->testAlpha('status');
        $limit = $this->cage->get->getInt('limit');
        $user = $this->cage->get->testUsername('user');
        $tag = $this->cage->get->noTags('tag');
        $search = $this->cage->get->getMixedString2('search');
        $category = $this->cage->get->noTags('category');
        if ($category) { 
            // so we can use a couple of functions from the Category class
            require_once(PLUGINS . 'categories/libs/Category.php');
            $cat = new Category($this->db); 
        } 
                
        //if (!$status) { $status = "top"; }
        if (!$limit) { $limit = 10; }
                    
        if ($status) { $filter['post_status = %s'] = $status; }
        if ($user) { $filter['post_author = %d'] = $this->current_user->getUserIdFromName($this->cage->get->testUsername('user'));  }
        if ($tag) { $filter['post_tags LIKE %s'] = '%' . $tag . '%'; }
        if ($category && (FRIENDLY_URLS == "true")) { $filter['post_category = %d'] = $cat->getCatId($category); }
        if ($category && (FRIENDLY_URLS == "false")) { $filter['post_category = %d'] = $category; }
        if ($search && $this->plugins->isActive('search')) { 
            $search_plugin = new Search('', $this->hotaru);
            $prepared_search = $search_plugin->prepareSearchFilter($search); 
            extract($prepared_search);
            $orderby = "post_date DESC";    // override "relevance DESC" so the RSS feed updates with the latest related terms. 
        }
        
        $this->plugins->pluginHook('post_rss_feed');
        
        $feed           = new RSS();
        $feed->title    = SITE_NAME;
        $feed->link     = BASEURL;
        
        if ($status == 'new') { $feed->description = $this->lang["submit_rss_latest_from"] . " " . SITE_NAME; }
        elseif ($status == 'top') { $feed->description = $this->lang["submit_rss_top_stories_from"] . " " . SITE_NAME; }
        elseif ($user) { $feed->description = $this->lang["submit_rss_stories_from_user"] . " " . $user; }
        elseif ($tag) { $feed->description = $this->lang["submit_rss_stories_tagged"] . " " . $tag; }
        elseif ($category && (FRIENDLY_URLS == "true")) { $feed->description = $this->lang["submit_rss_stories_in_category"] . " " . $category; }
        elseif ($category && (FRIENDLY_URLS == "false")) { $feed->description = $this->lang["submit_rss_stories_in_category"] . " " . $cat->getCatName($category); }
        elseif ($search) { $feed->description = $this->lang["submit_rss_stories_search"] . " " . stripslashes($search); }
                
        if (!isset($filter))  $filter = array();
        $prepared_array = $this->filter($filter, $limit, false, $select);
        
        $results = $this->getPosts($prepared_array);

        // determine if categories is installed, active and then create a Categories object:
        if (($this->plugins->getSetting('submit_categories', 'submit') == 'checked') 
            && ($this->plugins->isActive('categories'))) {
            $this->vars['useCategories'] = true; 
            require_once(PLUGINS . 'categories/libs/Category.php');
            $cat = new Category($this->db);
        } else { 
            $this->vars['useCategories'] = false; 
        }
            
        if ($results) {
            foreach ($results as $result) 
            {
                $this->url = $result->post_url; // used in Hotaru's url function
                
                //reset defaults:
                $this->vars['category'] = 1;
                $this->vars['catSafeName'] = '';
                
                if ($this->vars['useCategories'] && ($result->post_category != 1)) {
                    $this->vars['category'] = $result->post_category;
                    $this->vars['catSafeName'] =  $cat->getCatSafeName($result->post_category);
                }
                
                $item = new RSSItem();
                $title = html_entity_decode(urldecode($result->post_title), ENT_QUOTES,'UTF-8');
                $item->title = stripslashes($title);
                $item->link  = $this->hotaru->url(array('page'=>$result->post_id));
                $item->setPubDate($result->post_date); 
                $item->description = "<![CDATA[ " . stripslashes(urldecode($result->post_content)) . " ]]>";
                $feed->addItem($item);
            }
        }
        echo $feed->serve();
    }
    
    
    /**
     * Checks for existence of a url
     *
     * @return array|false - array of posts
     */    
    public function urlExists($url = '')
    {
        $sql = "SELECT count(post_id) FROM " . TABLE_POSTS . " WHERE post_orig_url = %s";
        $posts = $this->db->get_var($this->db->prepare($sql, urlencode($url)));
        if ($posts > 0) { return $posts; } else { return false; }
    }
    
    
    /**
     * Checks for existence of a title
     *
     * @param str $title
     * @return int - id of post with matching title
     */
    public function titleExists($title = '')
    {
        $title = trim($title);
        $sql = "SELECT post_id FROM " . TABLE_POSTS . " WHERE post_title = %s";
        $post_id = $this->db->get_var($this->db->prepare($sql, urlencode($title)));
        if ($post_id) { return $post_id; } else { return false; }
    }
    
    
    /**
     * Checks for existence of a post with given post_url
     *
     * @param str $post_url
     * @return int - id of post with matching url
     */
    public function isPostUrl($post_url = '')
    {
        $sql = "SELECT post_id FROM " . TABLE_POSTS . " WHERE post_url = %s";
        $post_id = $this->db->get_var($this->db->prepare($sql, urlencode($post_url)));
        if ($post_id) { return $post_id; } else { return false; }
    }
    
    /**
     * Get Unique Post Statuses
     *
     * @return array|false
     */
    public function getUniqueStatuses() 
    {
        /* This function pulls all the different statuses from current links, 
        or adds some defaults if not present.*/

        $unique_statuses = array();
        $sql = "SELECT DISTINCT post_status FROM " . TABLE_POSTS;
        $statuses = $this->db->get_results($this->db->prepare($sql));
        if ($statuses) {
            foreach ($statuses as $status) {
                if ($status->post_status) {
                    array_push($unique_statuses, $status->post_status);
                }
            }
        }
        // Some essentials if not already included:
        if (!in_array('new', $unique_statuses)) { array_push($unique_statuses, 'new'); }
        if (!in_array('top', $unique_statuses)) { array_push($unique_statuses, 'top'); }
        if (!in_array('buried', $unique_statuses)) { array_push($unique_statuses, 'buried'); }
        if (!in_array('pending', $unique_statuses)) { array_push($unique_statuses, 'pending'); }
        
        if ($unique_statuses) { return $unique_statuses; } else { return false; }
    }
    
    
    /**
     * Scrapes the title from the page being submitted
     */
    public function fetchTitle($url)
    {
        require_once(EXTENSIONS . 'SWCMS/class.httprequest.php');
        
        if ($url != 'http://' && $url != ''){
            $r = new HTTPRequest($url);
            $string = $r->DownloadToString();
        } else {
            $string = '';
        }
        
        if (preg_match('/charset=([a-zA-Z0-9-_]+)/i', $string , $matches)) {
            $encoding=trim($matches[1]);
            //you need iconv to encode to utf-8
            if (function_exists("iconv"))
            {
                if (strcasecmp($encoding, 'utf-8') != 0) {
                    //convert the html code into utf-8 whatever encoding it is using
                    $string=iconv($encoding, 'UTF-8//IGNORE', $string);
                }
            }
        }
        
        if (preg_match("'<title>([^<]*?)</title>'", $string, $matches)) {
            $title = trim($matches[1]);
        } else {
            $title = $this->lang["submit_form_not_found"];
        }
        
        return sanitize($title, 1);
    }
    

    /**
     * Prepare filter and breadcrumbs for Posts List
     *
     * @return array
     */
    public function prepareList()
    {
        $this->templateName = "list";
                
        if (!$this->hotaru->vars['filter']) { $this->hotaru->vars['filter'] = array(); }
        
        if ($this->cage->get->testPage('page') == 'latest') 
        {
            // Filters page to "new" stories only
            $this->hotaru->vars['filter']['post_status = %s'] = 'new'; 
            $rss = "<a href='" . $this->hotaru->url(array('page'=>'rss', 'status'=>'new')) . "'>";
            $rss .= " <img src='" . BASEURL . "content/themes/" . THEME . "images/rss_10.png'></a>";
            $this->hotaru->vars['page_title'] = $this->lang["post_breadcrumbs_latest"] . $rss;
        } 
        elseif ($this->useLatest)
        {
            // Filters page to "top" stories only
            $this->hotaru->vars['filter']['post_status = %s'] = 'top';
            $rss = "<a href='" . $this->hotaru->url(array('page'=>'rss')) . "'>";
            $rss .= " <img src='" . BASEURL . "content/themes/" . THEME . "images/rss_10.png'></a>";
            $this->hotaru->vars['page_title'] = $this->lang["post_breadcrumbs_top"] . $rss;
        }
        else
        {
            // Filters page to "all" stories
            $rss = "<a href='" . $this->hotaru->url(array('page'=>'rss')) . "'>";
            $rss .= " <img src='" . BASEURL . "content/themes/" . THEME . "images/rss_10.png'></a>";
            $this->hotaru->vars['page_title'] = $this->lang["post_breadcrumbs_all"] . $rss;
        }
        
        $this->plugins->pluginHook('post_list_filter');
        
        // defaults
        if (!isset($this->hotaru->vars['select'])) { $this->hotaru->vars['select'] = '*'; }
        if (!isset($this->hotaru->vars['orderby'])) { $this->hotaru->vars['orderby'] = 'post_date DESC'; }
        
        $prepared_filter = $this->filter($this->hotaru->vars['filter'], 0, true, $this->hotaru->vars['select'], $this->hotaru->vars['orderby']);
        
        $stories = $this->getPosts($prepared_filter);
        
        return $stories;
    }
    
    
    /**
     * Prepares and calls functions to send a trackback
     */
    public function sendTrackback()
    {
        // Scan content for trackback urls
        $tb_array = array();
        
        $trackback = $this->detectTrackback();
        
        if (!$trackback) { return false; } // No trackback url found
        
        // Clean up the title and description...
        $title = htmlspecialchars(strip_tags($post->getTitle()));
        $title = (strlen($this->title) > 150) ? substr($this->title, 0, 150) . '...' : $this->title;
        $excerpt = strip_tags($this->content);
        $excerpt = (strlen($excerpt) > 200) ? substr($excerpt, 0, 200) . '...' : $excerpt;

        // we don't want friendly urls in case the title or category is edited after submission, thus
        // changing and therefore breaking the trackback link posted on other sites. So...
        $url = BASEURL . 'index.php?page=' . $this->id; 
        
        if ($this->ping($trackback, $url, $title, $excerpt)) {
            echo "Trackback sent successfully...";
        } else {
            echo "Error sending trackback....";
        }
    }
    
    
    
    /**
     * Scan content of source url for a trackback url
     *
     * @return str - trackback url
     *
     * Adapted from Pligg.com and SocialWebCMS.com
     */
    public function detectTrackback()
    {
        include_once(EXTENSIONS . 'SWCMS/class.httprequest.php');
        
        // Fetch the content of the original url...
        $url = $this->origUrl;
        
        if ($url != 'http://' && $url != ''){
        $r = new HTTPRequest($url);
        $content = $r->DownloadToString();
        } else {
            $content = '';
        }
        
        $trackback = '';
        
        if (preg_match('/trackback:ping="([^"]+)"/i', $content, $matches) ||
            preg_match('/trackback:ping +rdf:resource="([^>]+)"/i', $content, $matches) ||
            preg_match('/<trackback:ping>([^<>]+)/i', $content, $matches)) {
                $trackback = trim($matches[1]);
                
        } elseif (preg_match('/<a[^>]+rel="trackback"[^>]*>/i', $content, $matches)) {
            if (preg_match('/href="([^"]+)"/i', $matches[0], $matches2)) {
                $trackback = trim($matches2[1]);
            }
            
        } elseif (preg_match('/<a[^>]+href=[^>]+>trackback<\/a>/i', $content, $matches)) {
            if (preg_match('/href="([^"]+)"/i', $matches[0], $matches2)) {
                $trackback = trim($matches2[1]);
            }
        } elseif (preg_match('/http:([^ ]+)trackback.php([^<|^ ]+)/i', $content, $matches)) {
                $trackback = trim($matches[1]);
                
        } elseif (preg_match('/trackback:ping="([^"]+)"/', $content, $matches)) {
                $trackback = trim($matches[1]);
        }
                
        return $trackback;
    }
    
    

    /**
     * Send a trackback to the source url
     *
     * @param str $trackback - url of source
     * @param str $url - of Hotaru post
     * @param str $title
     * @param str $excerpt
     * @link http://phptrackback.sourceforge.net/docs/
     */
    public function ping($trackback, $url, $title = "", $excerpt = "")
    {
        $response = "";
        $reason = ""; 
        
        // Set default values
        if (empty($title)) {
            $title = SITE_NAME;
        } 

        if (empty($excerpt)) {
            // If no excerpt show "This article has been featured on Site Name".
            $excerpt = $this->lang['submit_trackback_excerpt'] . " " . SITE_NAME;
        } 
        
        // Parse the target
        $target = parse_url($trackback);
        
        if ((isset($target["query"])) && ($target["query"] != "")) {
            $target["query"] = "?" . $target["query"];
        } else {
            $target["query"] = "";
        } 
    
        if ((isset($target["port"]) && !is_numeric($target["port"])) || (!isset($target["port"]))) {
            $target["port"] = 80;
        } 
        // Open the socket
        $tb_sock = fsockopen($target["host"], $target["port"]); 
        // Something didn't work out, return
        if (!is_resource($tb_sock)) {
            return '$post->ping: Tring to send a trackback but can\'t connect to: ' . $tb_sock . '.';
            exit;
        } 
        
        // Put together the things we want to send
        $tb_send = "url=" . rawurlencode($url) . "&title=" . rawurlencode($title) . "&blog_name=" . rawurlencode(SITE_NAME) . "&excerpt=" . rawurlencode($excerpt); 
         
        // Send the trackback
        fputs($tb_sock, "POST " . $target["path"] . $target["query"] . " HTTP/1.1\r\n");
        fputs($tb_sock, "Host: " . $target["host"] . "\r\n");
        fputs($tb_sock, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($tb_sock, "Content-length: " . strlen($tb_send) . "\r\n");
        fputs($tb_sock, "Connection: close\r\n\r\n");
        fputs($tb_sock, $tb_send); 
        // Gather result
        while (!feof($tb_sock)) {
            $response .= fgets($tb_sock, 128);
        } 

        // Close socket
        fclose($tb_sock); 
        // Did the trackback ping work
        strpos($response, '<error>0</error>') ? $return = true : $return = false;
        // send result
        
        return $return;
    } 
}

?>
