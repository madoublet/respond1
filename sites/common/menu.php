<?php 
	if(!isset($type)){
		$type = 'primary';
	} 

	$p_id = '';

	if(isset($id)){
		$p_id = ' id="'.$id.'"';
	}

	?>

<nav<?php print $p_id; ?> class="<?php print $type; ?>">
	<ul>

<?php

	$json = file_get_contents($dataloc.'data/menu.json');
	$data = json_decode($json, true);
	
	foreach($data as &$item) {
		$c_pageId = $item['PageId'];
		$c_type = $item['Type'];
		$cssClass = '';

		if($c_type==$type){

			if(isset($item['CssClass'])){
				$cssClass = ' '.trim($item['CssClass']);
			}

			if($pageId==$c_pageId){
				print '<li data-pageid="'.$pageId.'" data-cpageid="'.$c_pageId.'" class="selected'.$cssClass.'">';
			}
			else{
				if($pageType=='home' && ($item['Url']=='' || $item['Url']=='/')){ // handle the home condition
					print '<li data-url="'.$item['Url'].'" data-pageid="'.$pageId.'" data-cpageid="'.$c_pageId.'" class="selected'.$cssClass.'">';
				}
				else{
					print '<li data-url="'.$item['Url'].'" data-pageid="'.$pageId.'" data-cpageid="'.$c_pageId.'" class="'.$cssClass.'">';
				}
			}
			
			if(strpos($item['Url'], 'http://')===false && strpos($item['Url'], 'https://')===false){
				$c_url = $rootloc.$item['Url'];
			}
			else{
				$c_url = $item['Url'];
			}
			
			if($c_url=='..//'){
				$c_url = '../';
			}
			
		    print '<a href="'.$c_url.'">'.$item['Name'].'</a></li><!--position: '.strpos($item['Url'], 'http://').'-->';
	    }
	}
?>
	</ul>	
</nav>