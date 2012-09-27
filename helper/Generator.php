<?php 

class Generator
{
  
  // generate rss
  public static function GenerateRSS($site, $pageType){
    
    $pages = Page::GetRSS($site->SiteId, $pageType->PageTypeId);
    
    $timeZone = $site->TimeZone;
    $offset = 0;
    
    if($timeZone=='EST'){
      $offset = -5 * (60 * 60);
    }
    else if($timeZone=='CST'){
      $offset = -6 * (60 * 60);
    }
    else if($timeZone=='MST'){
      $offset = -7 * (60 * 60);
    }
    else if($timeZone=='PST'){
      $offset = -8 * (60 * 60);
    }
    
    $rss = '<?xml version="1.0" encoding="ISO-8859-1"?>'.
        '<rss version="2.0">'.
          '<channel>'.
          '<title>'.$site->Name.' - '.$pageType->TypeP.'</title>'.
          '<link>http://'.$site->Domain.'</link>'.
          '<description></description>'.
          '<language>en-us</language>'.
          '<copyright>Copyright (C) '.date('Y').' '.$site->Domain.'</copyright>';
    
    while($row = mysql_fetch_array($pages)){ 
      $u = (strtotime($row['Created'])+$offset);
      
        $rss = $rss.'<item>'.
               '<title>'.$row['Name'].'</title>'.
               '<description><![CDATA['.$row['Description'].']]></description>'.
               '<link>http://'.$site->Domain.'/'.strtolower($pageType->FriendlyId).'/'.strtolower($row['FriendlyId']).'.html</link>'.
               '<pubDate>'.date('D, d M Y H:i:s T', $u).'</pubDate>'.
               '</item>';
    }
    
    $rss = $rss.'</channel>';
    $rss = $rss.'</rss>';
    
    return $rss;
  }
  
  // generate site map
  public static function GenerateSiteMap($site){
    
    $pages = Page::GetPagesForSite($site->SiteId);
    
    $timeZone = $site->TimeZone;
    $offset = 0;
    
    if($timeZone=='EST'){
      $offset = -5 * (60 * 60);
    }
    else if($timeZone=='CST'){
      $offset = -6 * (60 * 60);
    }
    else if($timeZone=='MST'){
      $offset = -7 * (60 * 60);
    }
    else if($timeZone=='PST'){
      $offset = -8 * (60 * 60);
    }
    
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'.
           '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    while($row = mysql_fetch_array($pages)){ 
      $u = (strtotime($row['LastModifiedDate'])+$offset);
      
      $pageType = PageType::GetByPageTypeId($row['PageTypeId']);
      
      if($row['PageTypeId']==-1){
        
        $xml = $xml.'<url>'.
                   '<loc>http://'.$site->Domain.'/</loc>'.
                   '<lastmod>'.date('Y-m-d', $u).'</lastmod>'.
                 '<priority>1.0</priority>'.
                   '</url>';
        
      }
      else{
        $xml = $xml.'<url>'.
                   '<loc>http://'.$site->Domain.'/'.strtolower($pageType->FriendlyId).'/'.strtolower($row['FriendlyId']).'</loc>'.
                   '<lastmod>'.date('Y-m-d', $u).'</lastmod>'.
                 '<priority>0.5</priority>'.
                   '</url>';
      }
    }
    
    $xml = $xml.'</urlset>';
    
    return $xml;
  }
  
  // generates a page
  public static function GeneratePage($site, $page, $siteurl, $imageurl, $preview){
    
    $pageTypeId = $page->PageTypeId;
    $pageType = PageType::GetByPageTypeId($pageTypeId);
    $path = $_SERVER['REQUEST_URI'];
    
    $rootloc = '';
    $scriptloc = 'scripts/';
    $commonloc = '../common/';
    $dataloc = '';
    $default_url = '';
    $mobile_url = 'm/';
    $curr_url = 'http://'.$site->Domain;
    $abs_url = 'http://'.$site->Domain;
  
    if($preview==true){ // sets paths for preview
      $path = 'preview.html';
      $rootloc = 'sites/'.$site->FriendlyId.'/';
      $scriptloc = 'sites/'.$site->FriendlyId.'/js/';
      $commonloc = 'sites/common/';
      $dataloc = 'sites/'.$site->FriendlyId.'/';
    }
    else{
      if($page->PageTypeId==-1){ // home
        $path = '/';
        $scriptloc = 'js/';
      }
      else{
        $rootloc = '../';
        $scriptloc = '../js/';
        $dataloc = '../';
        $commonloc = '../../common/';
        $path = '/'.strtolower($pageType->FriendlyId).'/'.strtolower($page->FriendlyId).'.php';
        $default_url = $path;
        $mobile_url = 'm'.$path;
      }
      
  
      $curr_url = $curr_url.$default_url; 
      $abs_url = $abs_url.$default_url; 
    }
    
    $siteId = $site->SiteId;
    $timezone = $site->TimeZone;
    $siteUniqId = $site->SiteUniqId;
    $pageurl = 'http://'.$site->Domain.$path;
     
    $siteName = $site->Name;
    $template = $site->Template;
    $analyticsId = $site->AnalyticsId;
    
    $device = 'default';

    $htmlDir = 'sites/'.$site->FriendlyId.'/templates/'.$site->Template.'/html/';
    $htmlFile = $htmlDir.$page->Layout.'.html';
    $content = '{content}';

    if(file_exists($htmlFile)){
      $content = file_get_contents($htmlFile);
    }
    
    // global constants
    $content = str_replace('{site}', $site->Name, $content);
    
    // replace with constants
    if($page->PageTypeId!=-1){ // content
      $content = str_replace('{id}', $page->FriendlyId, $content);
      $content = str_replace('{type}', $pageType->FriendlyId, $content);
      $content = str_replace('{name}', $page->Name, $content);
      $content = str_replace('{description}', $page->Description, $content);
      $content = str_replace('{keywords}', $page->Keywords, $content);

      $p_content = ''; 

      if($preview==true){
        $fragment = 'sites/'.$site->FriendlyId.'/fragments/draft/'.$page->PageUniqId.'.html';

        if(file_exists($fragment)){
          $p_content = file_get_contents($fragment);
        }

        $p_content = str_replace('sites/'.$site->FriendlyId.'/', $rootloc, $p_content);
        $content = str_replace('{content}', $p_content, $content);
      }
      else{
        $fragment = 'sites/'.$site->FriendlyId.'/fragments/publish/'.$page->PageUniqId.'.html';

        if(file_exists($fragment)){
          $p_content = file_get_contents($fragment);
        }

        $p_content = str_replace('sites/'.$site->FriendlyId.'/', $rootloc, $p_content);
        $content = str_replace('{content}', $p_content, $content);
      }
      $content = str_replace('{synopsis}', substr(strip_tags(html_entity_decode($page->Description)), 0, 200), $content);
    }
    else if($page->PageTypeId==-1){ // home
      $pageId = Page::GetHome($siteId);
    
      if($pageId!=-1){
        $page = Page::GetByPageId($pageId);
        $content = str_replace('{id}', 'home', $content);
        $content = str_replace('{type}', 'home', $content);
        $content = str_replace('{name}', 'Home', $content);
        $content = str_replace('{description}', $page->Description, $content);
        $content = str_replace('{keywords}', $page->Keywords, $content);
        
        if($preview==true){
          
          $fragment = 'sites/'.$site->FriendlyId.'/fragments/draft/'.$page->PageUniqId.'.html';

          if(file_exists($fragment)){
            $p_content = file_get_contents($fragment);
          }

          $p_content = str_replace('sites/'.$site->FriendlyId.'/', $rootloc, $p_content);
          $content = str_replace('{content}', $p_content, $content);
        }
        else{
            
          $fragment = 'sites/'.$site->FriendlyId.'/fragments/publish/'.$page->PageUniqId.'.html';

          if(file_exists($fragment)){
            $p_content = file_get_contents($fragment);
          }

          $p_content = str_replace('sites/'.$site->FriendlyId.'/', $rootloc, $p_content);
          $content = str_replace('{content}', $p_content, $content);
        }
      }
    }
    $css = $rootloc.'css/'.$page->Stylesheet.'.css';
    
    $html = Generator::ParseHTML($site->SiteUniqId, $page->PageUniqId, $css, $content, $rootloc, $scriptloc, $dataloc, $commonloc, $device, $preview, $curr_url, $abs_url, $pageurl);
    
    // setup php header
    if($preview==true){
      $header = '';
    }
    else{
      $pageType = 'content';

      if($page->PageTypeId==-1){
        $pageType = 'home';
      }

      $header = '<?php '.PHP_EOL.
        '$rootloc="'.$rootloc.'";'.PHP_EOL.
        '$siterootloc="'.$dataloc.'";'.PHP_EOL.
        '$dataloc="'.$dataloc.'";'.PHP_EOL.
        '$commonloc="'.$commonloc.'";'.PHP_EOL.
        '$domain="'.$site->Domain.'";'.PHP_EOL.
        '$siteId='.$site->SiteId.';'.PHP_EOL.
        '$siteUniqId="'.$site->SiteUniqId.'";'.PHP_EOL.
        '$pageId='.$page->PageId.';'.PHP_EOL.
        '$pageType="'.$pageType.'";'.PHP_EOL.
        '$default_url="'.$default_url.'";'.PHP_EOL.
        'include "'.$commonloc.'Utilities.php";'.PHP_EOL.
        'include "'.$commonloc.'API.php";'.PHP_EOL.
        '?>';
    }
    
    $html = str_replace('{root}', $rootloc, $html);
    
    return $header.$html;
    
  }
  
  private static function ParseHTML($siteUniqId, $pageUniqId, $css, $content, $rootloc, $scriptloc, $dataloc, $commonloc, $device, $preview, $curr_url, $abs_url, $pageurl){
    
    //$html = str_get_html($content); // get in the parser
    $html = str_get_html($content, true, true, DEFAULT_TARGET_CHARSET, false, DEFAULT_BR_TEXT);

    $page = Page::GetByPageUniqId($pageUniqId);
    $site = Site::GetBySiteUniqId($siteUniqId);
    $mapcount = 0;
    $pageId = $page->PageId;

    if($preview==true){
      $siterootloc=$dataloc;

      ob_start();
      include $commonloc.'API.php';
      $isAjax=false;
      $content = ob_get_contents(); 
      ob_end_clean();
    }

    foreach($html->find('module') as $el){
      
      if(isset($el->name)){
        $name = $el->name;
        
        if($name=='styles'){
          $el->outertext = '<link href="'.$css.'" type="text/css" rel="stylesheet" media="screen">'.
                   '<link href="'.$rootloc.'css/bootstrap.min.css" type="text/css" rel="stylesheet" media="screen">'.
                   '<link href="'.$rootloc.'css/prettify.css" type="text/css" rel="stylesheet" media="screen">'.
                   '<link href="'.$rootloc.'css/bootstrap-responsive.min.css" type="text/css" rel="stylesheet" media="screen">';
        }
        else if($name=='header'){
          ob_start();
          include 'modules/publish/header.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='scripts'){
          ob_start();
          include 'modules/publish/scripts.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='analytics'){
          ob_start();
          
          //$webpropertyid= $el->webpropertyid;
          $webpropertyid = $site->AnalyticsId;
          
          include 'modules/publish/analytics.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='rss'){
          ob_start();
          
          include 'modules/publish/rss.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='featured'){
          ob_start();
          
          $type = $el->type;
          
          include 'modules/publish/featured.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='list'){
          
          $type = $el->type;
          $label = $el->label;
          $isAjax = false;
          $pageNo = 1;
          $curr = 0;
          $listid = $el->id;
          $display = $el->display;
          $desclength = $el->desclength;
          $length = $el->length;
          $orderby = $el->orderby;
          $groupby = $el->groupby;
          $pageresults = $el->pageresults;
          
          if($preview==true){
            
            ob_start();
          
            $typeid = $type;
            include $commonloc.'list.php';
            
            $content = ob_get_contents(); // holds the content
            ob_end_clean();
            
            $el->outertext = $content;
          }
          else{
            $list = '<?php '.
              '$isAjax=false;'.
              '$display="'.$el->display.'";'.
              '$label="'.$el->label.'";'.
              '$typeid="'.$el->type.'";'.
              '$listid="'.$listid.'";'.
              '$desclength="'.$desclength.'";'.
              '$length="'.$length.'";'.
              '$orderby="'.$orderby.'";'.
              '$groupby="'.$groupby.'";'.
              '$preview=false;'.
              '$pageresults='.$pageresults.';'.
              'include "'.$commonloc.'list.php"; ?>';
            
            $el->outertext = $list;
          }
        }
        else if($name=='menu'){

          if($preview==true){
            
            ob_start();
          
            if(isset($el->type)){
              $type = $el->type;
            }
            else{
              $type = 'primary';
            }

            if(isset($el->id)){
              $id = $el->id;
            }

            include $commonloc.'menu.php';
            
            $content = ob_get_contents(); // holds the content
            ob_end_clean();
            
            $el->outertext = $content;
          }
          else{
            if(isset($el->type)){
              $type = $el->type;
            }
            else{
              $type = 'primary';
            }
            $el->outertext = '<?php $type="'.$type.'"; include "'.$commonloc.'menu.php"; ?>';
          }
        }
        else if($name=='tracker'){
          $el->outertext = '<?php include "'.$commonloc.'tracker.php"; ?>';
        }
        else if($name=='footer'){
          ob_start();
          
          $copy = $el->innertext;
          
          include 'modules/publish/footer.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='crumbs'){
          ob_start();
          include 'modules/publish/crumbs.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='slideshow'){
          $id = $el->id;
          $width = $el->width;
          $height = $el->height;
          $imgList = $el->innertext;
          ob_start();
          include 'modules/publish/slideshow.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='gallery'){
          $id = $el->id;
          $imgList = $el->innertext;
          ob_start();
          include 'modules/publish/gallery.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='html' || $name=='youtube' || $name=='vimeo'){
          $el->outertext= $el->innertext;
        }
        else if($name=='file'){
          $file = $el->file;
          $description = $el->description;
          ob_start();
          include 'modules/publish/file.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
          else if($name=='form'){
          $form = $el->innertext;
          $file = $el->file;
          $description = $el->description;
          ob_start();
          include 'modules/publish/form.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='map'){
          $address = $el->address;
          ob_start();
          include 'modules/publish/map.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='twitter'){
          $username = $el->username;
          ob_start();
          include 'modules/publish/twitter.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='like'){
          $username = $el->username;
          ob_start();
          include 'modules/publish/like.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='comments'){
          ob_start();
          include 'modules/publish/comments.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='byline'){
          ob_start();
          include 'modules/publish/byline.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else{ 
          // do nothing
        }
        
      }

      foreach($html->find('plugin') as $el){

        $attrs = $el->attr;

        $p_vars = '';
        
        foreach($attrs as $key => &$val){
        
            ${$key} = $val; // set variable

            $p_vars .= '$'.$key.'="'.$val.'";';
        }

        $id = $el->id;
        $name = $el->name;
      
        if($render=='publish'){
          ob_start();
          include 'plugins/'.$type.'/render.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();

          $el->outertext= $content;
        }
        else if($render=='runtime'){
          $list = '<?php '.
              $p_vars.
              'include "'.$rootloc.'plugins/'.$type.'/render.php"; ?>';
            
          $el->outertext = $list;
        }

      }
    }
    
    return $html;
  }

  public static function ParseBlogHTML($siteUniqId, $pageUniqId, $content, $rootloc){
    $html = str_get_html(html_entity_decode($content)); // get in the parser
    
    $page = Page::GetByPageUniqId($pageUniqId);
    $site = Site::GetBySiteUniqId($siteUniqId);
    $mapcount = 0;

    foreach($html->find('module') as $el){
      
      if(isset($el->name)){
        $name = $el->name;
        
        if($name=='cart'){
          ob_start();
          
          $type = $el->type;
          
          include 'modules/publish/cart.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='event'){
          ob_start();
          
          $type = $el->type;
          
          include 'modules/publish/event.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='crumbs'){
          ob_start();
          include 'modules/publish/crumbs.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='slideshow'){
          $slideshowid = $el->id;
          $width = $el->width;
          $height = $el->height;
          $imgList = $el->innertext;
          ob_start();
          include 'modules/publish/slideshow.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='html' || $name=='youtube' || $name=='vimeo'){
          $el->outertext= $el->innertext;
        }
        else if($name=='file'){
          $file = $el->file;
          $description = $el->description;
          ob_start();
          include 'modules/publish/file.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='form'){
          $form = $el->innertext;
          $file = $el->file;
          $description = $el->description;
          ob_start();
          include 'modules/publish/form.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='map'){
          $address = $el->address;
          ob_start();
          include 'modules/publish/map.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='byline'){
          ob_start();
          include 'modules/publish/byline.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else if($name=='twitter'){
          $username = $el->username;
          ob_start();
          include 'modules/publish/twitter.php'; // loads the module
          $content = ob_get_contents(); // holds the content
          ob_end_clean();
          
          $el->outertext= $content;
        }
        else{  // do not show non-supported modules
          $el->outertext = '';
        }
        
      }
    }
  
    $html = str_replace('sites/'.$site->FriendlyId.'/', $rootloc, $html);

    return $html;
  }
  
}


?>