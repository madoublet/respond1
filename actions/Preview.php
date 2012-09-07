<?php

// Preview controller
class Preview extends Actions
{
	public $Ajax = '';
	public $Html = '';
	
	function __construct($authUser){
		
		$site = Site::GetBySiteId($authUser->SiteId);
	
		if(array_key_exists('t', $_GET)){ // handle preview for a template
			$template = $this->GetQueryString('t');
			
			$filename = 'templates/'.$template.'/html/home.html';
			
			if(file_exists($filename)){
				$content = file_get_contents($filename);
				
				$html = str_get_html(html_entity_decode($content)); // get in the parser
		
				foreach($html->find('module') as $el){
					
				}
				
				/*
				$header = '<header><h1><a id="logo" href="#">';
				
				if($site->LogoUrl==''){
					$header = $header.'$site->Name';	
				}
				else{
					$header = $header.'<img src="'.$site->LogoUrl.'">';
				}
				
				$header = $header.'</h1></header>';
				*/
			}
		}
		
	}

}