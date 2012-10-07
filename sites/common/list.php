<?php

  // get data
  if(!isset($display)){ // list or blog
    $display = 'list';
  }

  if($length=='All'){ // set # of results ($length) per page
    $length = 200; 
  }

  if(!isset($desclength)){
    $desclength = 5000;
  }
  else{
    $desclength = (int)$desclength; 
  }

  if($isAjax==false){ // set default pageNo
    $pageNo = 1;
    $totalPages = API::GetTotalPages($siteUniqId, $typeid);
  }

  // get data
  $list = API::GetList($siteUniqId, $typeid, $length, $orderby, ($pageNo-1), $rootloc, $display);

  $l_html = '';

  if($isAjax==false){ // do not create a div for ajax
    $l_html = '<div id="'.$listid.'" class="list">';
  }
    
  $lastcat = '';
  
  $count = count($list);
  
  $index = 0;

  // list items
  foreach($list as &$item){


    if($display=='list'){

      $oe_class = 'odd';
      $order_class = '';
      
      if(($index+1)%2==0){
        $oe_class='even';
      }

      if($item['Thumb']!=''){
        
        if($isAjax==false){
          $l_html = $l_html.'<div class="listItem hasImage '.$oe_class.' index-'.($index+1).'" data-thumb="'.$siterootloc.$item['Thumb'].'">';
          $l_html = $l_html.'<span class="image"><a href="'.$rootloc.$item['Url'].'"><img src="'.$siterootloc.$item['Thumb'].'"></a></span>';
        }
        else{
          $l_html = $l_html.'<div class="listItem hasImage '.$oe_class.' index-'.($index+1).'" data-thumb="'.$siterootloc.$item['Thumb'].'">';
          $l_html = $l_html.'<span class="image"><a href="'.$rootloc.$item['Url'].'"><img src="'.$siterootloc.$item['Thumb'].'"></a></span>';
        }
        
      }
      else{
        $l_html = $l_html.'<div class="listItem '.$oe_class.' index-'.($index+1).'">';
      }
      
      // show links
      $l_html = $l_html.'<h4><a href="'.$rootloc.$item['Url'].'">'.$item['Name'].'</a></h4>';
      
      // callout
      if(isset($item['Callout'])){
        if($item['Callout']!=''){
          $l_html = $l_html.'<em class="callout">'.$item['Callout'].'</em>';
        }
      }

      // description
      if(isset($item['Description'])){
        if($item['Description']!=''){
          $desc = strip_tags(html_entity_decode($item['Description']));
          $s_length = strlen($desc);
          $desc = substr($desc, 0, $desclength);
          $desc = htmlentities($desc, ENT_QUOTES, "UTF-8");
          if($s_length>$desclength){
            $desc = $desc.'...';
          }
          
          $l_html = $l_html.'<p class="description">'.$desc.'</p>';
        }
      }
      
      $l_html = $l_html.'</div>';

    }
    else{

      // begin post, name and link
      $l_html = $l_html.'<div class="post"><h1><a href="'.$rootloc.$item['Url'].'">'.$item['Name'].'</a></h1>';

      // content
      $l_html = $l_html.html_entity_decode($item['Content']);

      // permalink
      $l_html = $l_html.'<span class="permalink"><a href="'.$rootloc.$item['Url'].'">Permalink</a></span>';

      // end post
      $l_html = $l_html.'</div>';

    }
      
    $index = $index+1;
  
  }
    
  if($isAjax==false){ // end div, show map, etc (non-ajax only)
  
    // end div
    $l_html = $l_html.'</div>';
    
    if($pageresults==true && (($pageNo*$count)<$totalPages)){
      $l_html = $l_html.'<span id="pager-'.$listid.'" class="pager">';
      $l_html = $l_html.'<input id="'.$listid.'-display" type="hidden" value="'.$display.'">';
      $l_html = $l_html.'<input id="'.$listid.'-siteuniqid" type="hidden" value="'.$siteUniqId.'">';
      $l_html = $l_html.'<input id="'.$listid.'-typeid" type="hidden" value="'.$typeid.'">';
      $l_html = $l_html.'<input id="'.$listid.'-pageno" type="hidden" value="'.($pageNo+1).'">';
      $l_html = $l_html.'<input id="'.$listid.'-totalpages" type="hidden" value="'.$totalPages.'">';
      $l_html = $l_html.'<input id="'.$listid.'-desclength" type="hidden" value="'.$desclength.'">';
      $l_html = $l_html.'<input id="'.$listid.'-length" type="hidden" value="'.$length.'">';
      $l_html = $l_html.'<input id="'.$listid.'-orderby" type="hidden" value="'.$orderby.'">';
      $l_html = $l_html.'<button type="text" class="pager" id="'.$listid.'-pager">More...</button>';
      $l_html = $l_html.'</span>';
    }
  
  }

  if($preview==true){
    print $l_html;
  }
  else{
    if($isAjax==false){
      print $l_html;
    }
    else{
      die($l_html);
    }
  }
?>
