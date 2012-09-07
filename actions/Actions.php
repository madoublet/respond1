<?php

define('SECOND', 1);
define('MINUTE', 60 * SECOND);
define('HOUR', 60 * MINUTE);
define('DAY', 24 * HOUR);
define('MONTH', 30 * DAY);

class Actions{
	
	public $IsPostBack = false;
	public $Errors = "";
	public $Success = "";
	
	function __construct(){
		
		if (array_key_exists('_submit_check', $_REQUEST))
		{
			$this->IsPostBack = true;
		}
		
	}
	
	// uses curl to execute and retrieve the response from a URL
	public function GetData($url){
		$ch = curl_init();
	    
		curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15');
	    
	    curl_setopt($ch, CURLOPT_NOBODY, false);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result= curl_exec ($ch);
		curl_close($ch);
	    return $result;
	}
	
	public function GetPostData($field){
		
		if(array_key_exists($field, $_POST)){
		
			$value = $_POST[$field];
			$value = trim($value);
	    	$value = stripslashes($value);
	    	$value = htmlspecialchars($value);
			$value = str_replace( "\n", '', $value);
			$value = str_replace( "\t", '', $value);
	    	return $value;
		}
		else{
			return "";
		} 
	}
	
	public function GetPostDataWithSpecialCharacters($field){
		
		if(array_key_exists($field, $_POST)){
		
			$value = $_POST[$field];
			$value = trim($value);
	    	$value = stripslashes($value);
	    	//$value = htmlspecialchars($value);
			$value = str_replace( "\n", '', $value);
			$value = str_replace( "\t", '', $value);
	    	return $value;
		}
		else{
			return "";
		} 
	}
	
	public function GetPostDataFromTextarea($field){
		
		if(array_key_exists($field, $_POST)){
		
			$value = $_POST[$field];
			$value = trim($value);
	    	$value = stripslashes($value);
	    	//$value = htmlentities($value, ENT_QUOTES);
	    	return $value;
		}
		else{
			return "";
		} 
	}
	
	public function Clean($value){
	    $value = htmlspecialchars($value);
		$value = str_replace( "\n", '', $value);
		$value = str_replace( "\t", '', $value);
		
		return $value;
	}
	
	public function GetPostArray($field){
		
		if(array_key_exists($field, $_POST)){
		
			$array = $_POST[$field];
			
	    	return $array;
		}
		else{
			return null;
		} 
		
	}
	
	public function GetQueryString($field){
		
		if(array_key_exists($field, $_GET)){
		
			$value = $_GET[$field];
			$value = trim($value);
	    	$value = stripslashes($value);
	    	$value = htmlspecialchars($value);
			
	    	return $value;
		}
		else{
			return "";
		} 
		
	}
	
	public function SetSelected($val1, $val2){
		
		if($val1==$val2)return 'selected="selected"';
		
	}
	
	// XOR encryption
	public static function XOREncryption($InputString, $KeyPhrase){

	    $KeyPhraseLength = strlen($KeyPhrase);
	 
	    // Loop trough input string
	    for ($i = 0; $i < strlen($InputString); $i++){
	        // Get key phrase character position
	        $rPos = $i % $KeyPhraseLength;
	        // Magic happens here:
	        $r = ord($InputString[$i]) ^ ord($KeyPhrase[$rPos]);
	        // Replace characters
	        $InputString[$i] = chr($r);
	    }
	 
	    return $InputString;
	}
	
	// encrypt simple obfuscation
	public function SimpleEncrypt($InputString, $KeyPhrase){
    	$InputString = Actions::XOREncryption($InputString, $KeyPhrase);
    	$InputString = base64_encode($InputString);

    	return $InputString;
	}
	
	// decrypt for simple obfuscation
	public function SimpleDecrypt($InputString, $KeyPhrase){
		$InputString = base64_decode($InputString);
    	$InputString = Actions::XOREncryption($InputString, $KeyPhrase);

    	return $InputString;
	}
	
	public function GetFriendlyDate($time){

		$delta = strtotime(gmdate("Y-m-d H:i:s", time())) - $time;

		if ($delta < 1 * MINUTE){
			return $delta == 1 ? "one second ago" : $delta . " seconds ago";
		}

		if ($delta < 2 * MINUTE){
			return "1 minute ago";
		}
  
  		if ($delta < 45 * MINUTE){
  			return floor($delta / MINUTE) . " minutes ago";
		}

		if ($delta < 90 * MINUTE){
			return "1 hour ago";
		}

		if ($delta < 24 * HOUR){
			return floor($delta / HOUR) . " hours ago";
		}
  
  		if ($delta < 48 * HOUR){
  			return "yesterday";
		}

  		if ($delta < 30 * DAY){
			return floor($delta / DAY) . " days ago";
		}

 		if ($delta < 12 * MONTH){
 			$months = floor($delta / DAY / 30);
			return $months <= 1 ? "1 month ago" : $months . " months ago";
		}

		else{
			$years = floor($delta / DAY / 365);
			return $years <= 1 ? "1 year ago" : $years . " years ago";
		}
	}
	
	public function GetMessages(){
		
		$messages = "";
		
		if($this->Errors!="")$messages = $messages.$this->Errors;
		if($this->Success!="")$messages = $messages.$this->Success;
		
		return $messages;
		
	}
}

?>