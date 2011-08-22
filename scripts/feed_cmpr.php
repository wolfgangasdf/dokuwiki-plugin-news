<?php
require_once('../../../inc/init.php');


$file = $argv[1];

$data = file_get_contents(wikiFN($file));

//$data = preg_replace("#^\s*~~NEWSFEED.*?~~\s+#","",$data);
$segs = 4;
$ar = preg_split("/(={" . $segs  . ",}.*?={" . $segs . ",})\s*\n/",$data, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
if(strpos($ar[0],'~NEWSFEED')) {
  array_shift($ar);
} 
print_r($ar);
exit;





$n = preg_match_all("#<news(.*?)>.*?(?=</news>)#ms", $data, $matches);

echo $n . "\n";
$data = $matches[0];
$titles = $matches[1];
for($i=0; $i<count($data); $i++) {
  list($news,$title) = explode(':',$titles[$i]);
  
  echo "$i-------------\nTitle: ";
  if(isset($title)) {
       echo "trimming title\n";
       $title = trim($title); 
   }	   
  if(!isset($title) && !$title) {
        echo "news\n";
       
   }
   else echo "$title\n"; 
  $data[$i] = preg_replace("#^\s*<news(.*?)>#","",$data[$i]);
  echo $data[$i];
  echo "\n";
}
exit;
if($n == 0) {
   echo "no data\n";
   //exit;
}
else {
   array_unshift($matches,$argv[1]);
}


$gz = gzopen('somefile.gz','wb');

gzwrite($gz, serialize($matches));
gzclose($gz);

$result = join('', gzfile('somefile.gz'));
$result = unserialize($result);
$file = array_shift($result);
echo "File: $file\n";

print_r($result);

exit;

$fsize = filesize('somefile.gz');

$fsize = filesize($file) + (1024*5) ;


$zd = gzopen('somefile.gz', "r");
$contents = gzread($zd,$fsize);
gzclose($zd);
$result = unserialize($contents);
$file = array_shift($result);
echo "File: $file\n";

print_r($result);

exit;


$html = html_secedit(p_render('xhtml',p_get_instructions($data),$info),false);

$data = gzcompress($html);
file_put_contents($file . '.cmpr', $data);

//echo gzuncompress($uncmpr);

$gz = gzopen('somefile.gz','wb');
gzwrite($gz, $html);
gzclose($gz);

readgzfile('somefile.gz'); 
//$cnt = count($n);
/*
if($cnt == 1 && empty($matches)) {
   echo "no data\n";
   exit;
}
else {
   array_unshift($matches,$argv[1]);
}


$tmp = '~~NEWSFEED~~';
$tmp2 = '~~NEWSFEED:3~~';
$tmp=substr($tmp,11,-2);
if($tmp === false) {
  echo "False\n";
}
echo $tmp, "\n";
echo strlen($tmp), "\n";
$tmp2=substr($tmp2,11,-2);
echo strlen($tmp2), "\n";
exit;

//echo wikiFN($file) . "\n";
//echo html_wikilink($file) . "\n";
//global $conf;
//echo $conf['datadir'] . "\n";
//echo DOKU_URL ;

*/

?>
