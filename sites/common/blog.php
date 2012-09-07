<?php

  if($isAjax==false){ // set default pageNo
    $pageNo = 1;
  }

  $length = 20;
  $pageresults = true;
  $list = API::GetBlog($siteUniqId, ($pageNo-1), $rootloc);

  $b_html = '';

  if($isAjax==false){ // do not create a container div for ajax
    $b_html = '<div id="'.$blogid.'" class="blog">';
  }
    
  $count = $totalPages = API::GetTotalPagesForBlog($siteUniqId);

  $index = 0;

  // list items
  foreach($list as &$item){

    if($index >= ($length*($pageNo-1))){

      $curr = $curr+1;
      
      if($curr>($length*$pageNo)){
        break;
      }
      
      // begin post, name and link
      $b_html = $b_html.'<div class="post"><h2><a href="'.$rootloc.$item['Url'].'">'.$item['Name'].'</a></h2>';

      // content
      $b_html = $b_html.html_entity_decode($item['Content']);

      // permalink
      $b_html = $b_html.'<span class="permalink"><a href="'.$rootloc.$item['Url'].'">Permalink</a></span>';

      // end post
      $b_html = $b_html.'</div>';
    }
    
    $index = $index+1;
  
  }
    
  if($isAjax==false){ // end div, show map, etc (non-ajax only)
  
    $b_html = $b_html.'</div>';
    $b_html = $b_html.'<input id="'.$blogid.'-current" type="hidden" value="'.($curr-1).'">';

    // end div
    if((ceil($count/$length)!=1)){
      $b_html = $b_html.'<span id="pager-'.$blogid.'" class="pager">';
      $b_html = $b_html.'<input id="'.$blogid.'-current" type="hidden" value="'.($curr-1).'">';
      $b_html = $b_html.'<input id="'.$blogid.'-pageNo" type="hidden" value="'.($pageNo+1).'">';
      $b_html = $b_html.'<input id="'.$blogid.'-totalpages" type="hidden" value="'.ceil($count/$length).'">';
      $b_html = $b_html.'<input id="'.$blogid.'-totalitems" type="hidden" value="'.$count.'">';
      $b_html = $b_html.'<button type="text" class="pager" id="'.$blogid.'-pager">More...</button>';
      $b_html = $b_html.'</span>';
    }
  
  }

  if($preview==true){
    print $b_html;
  }
  else{
    if($isAjax==false){
      print $b_html;
    }
    else{
      die($b_html);
    }
  }
?>
