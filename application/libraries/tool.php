<?php
class Tool {
	
	public static function getHoursDifference($start, $end) {
		$uts['start'] = strtotime($start);
		$uts['end'] = strtotime($end);
		if( $uts['start']!==-1 && $uts['end']!==-1 ) {
			if($uts['end'] >= $uts['start']) {
				$time = Tools::getTimeDifference($start,$end);
				return Tools::numberFormat((($time['hours'] * 60) + $time['minutes']) / 60);
			} else {
				$start = date('H:i:s', strtotime("$start -12 hours"));
				$end = date('H:i:s', strtotime("$end -12 hours"));
				$time = Tools::getTimeDifference($start, $end);
				return Tools::numberFormat((($time['hours'] * 60) + $time['minutes']) / 60);			
			}
		} else {
			return 0;//trigger_error( "Invalid date/time data detected", E_USER_WARNING );
		}
		return 0;
	}
	
	public static function numberFormat($value) {
		return number_format($value, 2, '.', '');
	}	

	public static function objectToArray($object) {
		$array = array();
		$array = (is_object($object)) ? get_object_vars($object): $object;
		return $array;
	}	
	
	function change_to_letters($string) {
	
		$replacements = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u");
		$string = $replacements[$string];
		return $string;
	}
	
	
	public static function getTimeDifference($start, $end) {
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
			if( $uts['end'] >= $uts['start'] )
			{
				$diff    =    $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );            
				return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
			}
			else
			{
				//trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
				return 0;
			}
		}
		else
		{
			//trigger_error( "Invalid date/time data detected", E_USER_WARNING );
			return 0;
		}
		return 0;
	}
	/*
	* Returns number of days. difference of two dates
	*
	* @param string $from 2009-12-25
	* @param string $to 2009-12-28	
	* @return int
	*/		 
	 public static function getDayDifference($from, $to) {
	 	list($from_year, $from_month, $from_day) = explode('-', $from);
	 	list($to_year, $to_month, $to_day) = explode('-', $to);
		$from = mktime(0, 0, 0, $from_month, $from_day, $from_year);
		$to = mktime(0, 0, 0, $to_month, $to_day, $to_year);
		$date_diff = $to - $from;
		return floor($date_diff/(60*60*24));
	 }
	 
	/*
	* Returns the dates based from GMT. it is currently philippine GMT +8
	*
	* @param string $format 'Y-m-d'
	* @param int $timestamp strtotime("now");	
	* @return string
	*/		 		
	public static function getGmtDate($format, $timestamp = NULL){
		$timestamp = (empty($timestamp)) ? strtotime("now") : $timestamp ;
		$offset = (int) +8; //Setting::getValue('gmt');
	   //Offset is in hours from gmt, including a - sign if applicable.
	   //So lets turn offset into seconds
	   $offset = $offset * 60 * 60;
	   $timestamp = $timestamp + $offset;
		//Remember, adding a negative is still subtraction ;)
	   return gmdate($format, $timestamp);
	}
	
	public static function generateEmployeeId($table) {
		if($table=='') {
			exit();	
		}
		$next_id = self::mysql_next_id($table);
		if($next_id<10)
		{
			$return = '1000'.$next_id;
		}elseif($next_id<100 && $next_id>9)
		{
			$return = '100'.$next_id;
		}elseif($next_id<1000 && $next_id>99)
		{
			$return = '10'.$next_id;
		}elseif($next_id<10000 && $next_id>999)
		{
			$return = '1'.$next_id;
		}elseif($next_id<100000 && $next_id>9999)
		{
			$return = $next_id;
		}
		return date("Y") . '-'. $return;
	}
	
	public static function mysql_next_id($table) {
	
    	$result = mysql_query('SHOW TABLE STATUS LIKE "'.$table.'"');
    	$rows = mysql_fetch_assoc($result);
	   	return $rows['Auto_increment'];
	}
	
	public static function convertToValidUrl($string) {
		$string = preg_replace('/[^a-z0-9_ ]/i', '', $string);
		$string = preg_replace('/[ ]/i', '_', $string);
		return strtolower($string);
	}
	
	public static function getCoveredWeekDays($date) {
		list($year, $month, $day) = explode('-', $date);
		$week_number = Tools::getGmtDate('W', strtotime("$year-$month-$day"));
		$zxc = $year; //Get the current year
		$qwe = strtotime("$zxc-1-1"); //First day of the Year		
		//1 year has 52 week
		for( $week = 0; $week <= 52; $week++) {
			$asd = strtotime("+$week week +3 days" ,$qwe);		
			$valid = $asd;
			$weeks[Tools::getGmtDate('W', $valid)] = Tools::getGmtDate('Y-m-d', $valid);
			//echo "Week ".date('W', $valid).", ".Tools::getGmtDate('Y-m-d', $valid)."<br/>";
		}
		$end_date = $weeks[$week_number];
		$end_mktime = strtotime($end_date . "+7 days");
		$start_mktime = strtotime($end_date);
		$start_date = Tools::getGmtDate('Y-m-d', $start_mktime);
		
		while ($start_mktime < $end_mktime) {
			$days_mktime[] = $start_mktime; //date('Y-m-d', $start_mktime);
			$start_mktime = strtotime("+1 day", $start_mktime);
		}
		foreach ($days_mktime as $d) {
			$days[] = Tools::getGmtDate('Y-m-d', $d);
		}
		return $days;
	}
	
	public static function getCoveredWeeks($month, $year) {
		//list($year, $month, $day) = explode('-', $date);
		$week_number = date('W', strtotime("$year-$month-1"));
		$zxc = $year; //Get the current year
		$qwe = strtotime("$zxc-1-1"); //First day of the Year		
		//1 year has 52 week
		for( $week = 0; $week <= 52; $week++) {
			$asd = strtotime("+$week week +3 day" ,$qwe);
			if (date('m', $asd) == $month) {	
				$valid = $asd;
				$weeks[date('W', $valid)] = date('Y-m-d', $valid);
				//echo "Week ".date('W', $valid).", ".date('Y-m-d', $valid)."<br/>";
				$days[] = date('Y-m-d', $valid) . '/' . date('Y-m-d', strtotime("+6 days",$valid));
			}
		}
		return $days;
	}
	
	public static function getCoveredMonths($year) {
		for ($i = 1; $i <= 12; $i++) {
			$last_date = date('t', strtotime("$year-$i-1"));
			$months[] = date("Y-m-d", strtotime("$year-$i-1")) . '/' . date("Y-m-d", strtotime("$year-$i-$last_date"));
		}
		return $months;
	}

	public static function isInteger($value) {
		return (preg_match('/(?<!\S)\d++(?!\S)/', $value)) ? true : false ;
	}
	public static function hasValue($value) {
		return (strlen(trim($value)) > 0) ? true : false ;
	}
	public static function isValidDate($value) {		
		return (preg_match('/(19|20)[0-9]{2}[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])/', $value)) ? true : false ;	
	}
	
	public static function isDate($value) {
		return (strtotime($value)!='') ? 1 : 0 ;
	}
	
	public static function friendlyTitle($draftTitle) {
		if(substr($draftTitle, -3) == '_id'){
			$draftTitle = substr($draftTitle, 0, -3);
		}
		$new_title = ucwords(str_replace('_', ' ', $draftTitle));
		
		return $new_title;	
	}
	
	public static function friendlyFormName($draftTitle) {
	
		if(is_numeric(substr($draftTitle,0,1))) {

				$new_title = 'Required_' .  strtolower(str_replace(' ', '_', $draftTitle));	
			
		}else {
			$new_title = strtolower(str_replace(' ', '_', $draftTitle));
		}

		return $new_title;	
	}
	
	//Usage
	//$current = Tools::getCurrentDateTime('Y-m-d h:i:s a','Asia/Manila');
	public static function getCurrentDateTime($format="Y-m-d h:i:s",$time_zone = "Asia/Manila") {
		date_default_timezone_set($time_zone);
		$current_time = time();
		$date_time    = date($format,$current_time);
		return $date_time;
	}
	
	//Usage
	//$current = Tools::limitCharater("Your Content", 100, " ");
	public static function limitCharater($string, $limit, $break = ".", $pad = "..."){
   	  $string = strip_tags($string);	
	// return with no change if string is shorter than $limit
	  if(strlen($string) <= $limit) return $string;
	
	  // is $break present between $limit and the end of the string?
	  if(false !== ($breakpoint = strpos($string, $break, $limit))) {
		if($breakpoint < strlen($string) - 1) {
		  $string = substr($string, 0, $breakpoint) . $pad;
		}
	  }
		
	  return $string;

	}
	
	//Usage
	//$current = Tools::createRandomPassword();
	public static function createRandomPassword() {
		$chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ023456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '' ;
		while ($i <= 29) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}
	
	/*
		Returns day format of the given $date. 'sun', 'mon', 'tue, 'wed', 'thu', 'fri', 'sat'
	*/
	public static function getDayFormat($date) {
		if (!empty($date)) {
			$days = array('sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat');
			$day_of_the_week = date('w', strtotime($date)); // gets the day of the week. 0=sunday, 1=monday, ....,  6=saturday		
			return $days[$day_of_the_week];
		}
	}
	
	public static function addLeadingZero($number) {
		$number = (int) $number;
		if ($number < 10 && strlen($number) == 1) {
			return "0{$number}";	
		} else {
			return $number;	
		}
	}
	
	public static function timeFormat($time) {
		return date('g:i a', strtotime($time));
	}
	
	public static function currencyFormat($value) {
		return number_format($value, '2', '.', ',');
	}
	
	public static function dateFormat($date) {
		return Tools::getGmtDate('Y-m-d', strtotime($date));
	}
	
	public static function isFileExist($filename) {

		if(file_exists($_SERVER['DOCUMENT_ROOT'].$filename)) {
			$return = true;
		}else {
			$return = false;

		}
		return $return;
	}	
	
	public static function removeFile($file='') {
		
		if($file!='') {
			$myFile = $file;
			$fh = fopen($myFile, 'w') or die("can't open file");
			
			fclose($fh);	
			unlink($myFile);
		}
	}
	
	/*
		Extracts between dates
		
		Usage: 
			$start_date = '2010-11-20';
			$end_date = '2010-11-25';
			$x = Tools::getBetweenDates($start_date, $end_date);
		
		Output:
			Array
			(
				[0] => 2010-11-20
				[1] => 2010-11-21
				[2] => 2010-11-22
				[3] => 2010-11-23
				[4] => 2010-11-24
				[5] => 2010-11-25
			)				
	*/
	public static function getBetweenDates($start_date, $end_date) {
		$mk_start = strtotime($start_date);
		$mk_end= strtotime($end_date);
		while ($mk_start <= $mk_end) {				
			$date = date('Y-m-d', $mk_start);
			$data[] = $date;
			$mk_start = strtotime($date . "+1 day");
		}
		return $data;
	}
	
	/*
		Converts time format to hour format. 8 hour and 30 mins (8:30 becomes 8.50)
	*/	
	public static function convertTimeToHour($time) {
		list($hours, $minutes) = explode(':', $time);
		if (empty($hours) && empty($minutes)) {
			return 0;
		}
		//return Tools::numberFormat((($hours * 60) + $minutes) / 60);
		return (($hours * 60) + $minutes) / 60;
	}
	
	/*
		Converts hour format to time format. 8.50 becomes 08:30
	*/
	public static function convertHourToTime($hour_format) {
		list($hours, $minutes) = explode('.', $hour_format);
		$minutes = ((float) "0.{$minutes}") ;
		return $hours . ':' . round(60 * $minutes);
	}
	
	/*
		Usage:
			$date = '2011-05-4';
			$cutoff[] = '21-5';
			$cutoff[] = '6-20';
			$x = Tools::getCutOffPeriod($date, $cutoff);
		Output:
			$dates['start'] = '2011-02-02';
			$dates['end'] = '2011-02-20';	
	*/
	public static function getCutOffPeriod($date, $patterns) {		
		list($year, $month, $day) = explode('-', $date);
		foreach ($patterns as $cutoff) {
			list($start, $end) = explode('-', $cutoff);
			if ($start > $end) {
				$start_date = date("Y-m-{$start}", strtotime("{$date}"));
				$end_date = date("Y-m-{$end}", strtotime("{$start_date} +1 month"));
				if (Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = $start_date;
					$cutoff_dates['end'] = $end_date;					
					return $cutoff_dates;
				}
				
				$start_date = date("Y-m-{$start}", strtotime("{$date} -1 month"));
				$end_date = date("Y-m-{$end}", strtotime("{$start_date} + 1 month"));
				if (Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = date('Y-m-d', strtotime("$start_date"));
					$cutoff_dates['end'] = date('Y-m-d', strtotime("$end_date"));
					return $cutoff_dates;
				}
			} else {				
				$month_days = date('t', strtotime("{$date}")); //Number of days in the given month - 28 to 31
				if ($month_days < $end) {
					$end = $month_days;	
				}				
				$start_date = date("Y-m-{$start}", strtotime("{$date}"));
				$end_date = date("Y-m-{$end}", strtotime("{$date}"));
				
				if (Tools::isDateWithinDates($date, $start_date, $end_date)) {
					$cutoff_dates['start'] = date('Y-m-d', strtotime("$start_date"));
					$cutoff_dates['end'] = date('Y-m-d', strtotime("$end_date"));
					return $cutoff_dates;
				}
			}
		}		
	}
	
	public static function isDateWithinDates($date, $start_date, $end_date) {
		$dates = Tools::getBetweenDates($start_date, $end_date);
		foreach ($dates as $the_date) {
			if (strtotime($the_date) == strtotime($date)) {
				return true;
			}
		}
		return false;
	}
	
	public static function getNextId($table_name,$currend_id)
	{
		$sql = "
		    SELECT id
			FROM ".$table_name."
			WHERE id > ".Model::safeSql($currentid)."
			ORDER BY id ASC
			LIMIT 1
		";
	}
	
	public static function getPreviousId($table_name,$currend_id)
	{
		$sql = "
		    SELECT id
			FROM ".$table_name."
			WHERE ID > ".Model::safeSql($currentid)."
			ORDER BY id ASC
			LIMIT 1
		";
	}
	
	function toArray($data) {
		if (is_object($data)) $data = get_object_vars($data);
		return is_array($data) ? array_map(__FUNCTION__, $data) : $data;
	}
	
	public static function sendMailSwiftMailer($subject,$email,$msg,$smtp,$port,$username,$password,$from)
	{	
		Loader::appLibrary('swiftmailer/lib/swift_required');						
		$message11   = Swift_Message::newInstance();	
		$transport11 = Swift_SmtpTransport::newInstance($smtp, $port);							
		$transport11->setUsername($username);
		$transport11->setpassword($password);									
		$mailer11 = Swift_Mailer::newInstance($transport11);
		$message11->setSubject($subject);
		$message11->setFrom(array($email => 'Gleent Incorporated'));
		foreach($email as $key=>$value) :
			if (preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/',$value)) { 
				$message11->setTo($value);
				//$message11->setBcc('leoangelo.diaz@gmail.com, amethyst.livlaw@gmail.com');					
				$message11->setBody($msg , 'text/html');
				$numsent += $mailer11->send($message11);	
			}
		endforeach;
	}
	
	public static function createHashTokenUrl() {
		$ft 	 	= Utilities::createFormToken();
		$token	 	= Utilities::encrypt($ft);
		$hash	 	= Utilities::createHash($ft);
		
		$hash_url 	= '?token='.$token.'&hash='.$hash;
		return $hash_url;	
	}
	
	public static function showArray($array) {
		echo $array;
		echo '<pre>';
		print_r($array);
		exit;
	}
	
	public static function getNextRedirectURI() {
		//echo urlencode($_SERVER['REQUEST_URI']);
		$path 	= $_SERVER['REQUEST_URI']."/index";
		$array	= explode('/',$path);
		if($array[count($array)-2] != 'index.php') {
			if($array[count($array)-1]=="index") {
				$method = "";
			} else {$method = $array[count($array)-1];}
			$next_param = $array[count($array)-2]."/{$method}";
		} else {
			$next_param = $array[count($array)-1];
		}
		return $next_param;
	}
	
	public static function getOrdinalSuffix($number) {
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if (($number %100) >= 11 && ($number%100) <= 13)
		   $abbreviation = $number. 'th';
		else { $abbreviation = $number. $ends[$number % 10];	}
		
		return $abbreviation;
		
	}

	function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
	}

	public function generateRandomString($length = 6) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}

	function humanTiming ($time)
	{

	    $time = time() - $time; // to get the time since that moment

	    $tokens = array (
	        31536000 => 'year',
	        2592000 => 'month',
	        604800 => 'week',
	        86400 => 'day',
	        3600 => 'hour',
	        60 => 'minute',
	        1 => 'second'
	    );

	    foreach ($tokens as $unit => $text) {
	        if ($time < $unit) continue;
	        $numberOfUnits = floor($time / $unit);
	        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	    }

	}

	function getHumanTimeDifference($date,$granularity=2) {
	    $date = strtotime($date);
	    $difference = time() - $date;
	    $periods = array('decade' => 315360000,
	        'year' => 31536000,
	        'month' => 2628000,
	        'week' => 604800, 
	        'day' => 86400,
	        'hour' => 3600,
	        'minute' => 60,
	        'second' => 1);

	    foreach ($periods as $key => $value) {
	        if ($difference >= $value) {
	            $time = floor($difference/$value);
	            $difference %= $value;
	            $retval .= ($retval ? ' ' : '').$time.' ';
	            $retval .= (($time > 1) ? $key.'s' : $key);
	            $granularity--;
	        }
	        if ($granularity == '0') { break; }
	    }
	    if($retval) {
	    	return ''.$retval.' ago';
	    } else {
	    	return 'Today';
	    }
	    
	}
}
?>