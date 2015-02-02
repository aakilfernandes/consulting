<?PHP

function randomToken(){
	$charLength = rand(12,24);
	return substr(str_shuffle(MD5(microtime())), 0, $charLength);
}

function slugify($str) {
    $search = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
    $replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', 'a', 'a', 'e', 'E');
    $str = str_ireplace($search, $replace, strtolower(trim($str)));
    $str = preg_replace('/[^\w\d\-\ ]/', '', $str);
    $str = str_replace(' ', '-', $str);
    return preg_replace('/\-{2,}/', '-', $str);
}

function removeLineBreaks($output){
	$output = str_replace(array("\r\n", "\r"), "\n", $output);
	$lines = explode("\n", $output);
	$new_lines = array();

	foreach ($lines as $i => $line) {
	    if(!empty($line))
	        $new_lines[] = trim($line);
	}
	return implode($new_lines);
}
