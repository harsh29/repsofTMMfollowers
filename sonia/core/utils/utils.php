<?php
if (!empty($GLOBALS['_UTILS_PHP_'])) return;
$GLOBALS['_UTILS_PHP_'] = true;

import("core/constants.php");

function getEntrybyID($id)
{
  return WIKI_DIR . "/"  . $id;
}

function println($str)
{
  echo $str . "</br>";
}

function trace($str)
{
  if (DEBUG_MODE)
    println($str);
}

function travDir($dest)
{
  $directory = dir($dest);
  $ret = array();
  while($file = $directory->read()) {
      if ($file != ".." && $file != ".") {
        $ret[] = $file;
        // echo $file;
      }
  }
  return $ret;
}

function isEntryExist($id)
{
  return file_exists(WIKI_DIR . "/" . $id);
}

function getNextID()
{
  for ($id = 1; isEntryExist($id); $id++);
  return $id;
}

function generateGUID() {
  $charid = strtoupper(md5(uniqid(mt_rand(), true)));
  return $charid;
}

function delDir($dir) {
  $dh = opendir($dir);
  while ($file = readdir($dh)) {
    if($file != "." && $file != "..") {
      $fullpath = $dir . "/" . $file;
      if(!is_dir($fullpath)) {
          unlink($fullpath);
      } else {
          delDir($fullpath);
      }
    }
  }

  closedir($dh);
  return rmdir($dir);
}

function formatContent($content) {
  return str_replace("\n", "<br>", $content);
}

function getMD532U($str)
{
  return strtoupper(md5($str));
}

function getBrowser() {
  $agent = $_SERVER["HTTP_USER_AGENT"];

  if(strpos($agent,'MSIE') !== false || strpos($agent,'rv:11.0'))
    return "IE";
  else if(strpos($agent,'Firefox') !== false)
    return "Firefox";
  else if(strpos($agent,'Chrome') !== false)
    return "Chrome";
  else if(strpos($agent,'Opera') !== false)
    return 'Opera';
  else if((strpos($agent,'Chrome') == false) && strpos($agent,'Safari') !== false)
    return 'Safari';
  else
    return 'Other';
}

function getDevice() {
  $agent = $_SERVER["HTTP_USER_AGENT"];

  if(strpos($agent,'iPhone') !== false)
    return "iPhone";
  else if(strpos($agent,'iPad') !== false)
    return "iPad";
  else if(strpos($agent,'Android') !== false)
    return "Android";
  else if(strpos($agent,'Linux') !== false)
    return "Linux";
  else
    return 'PC';
}

function getOS() {
  $OS = "";
  if(!empty($_SERVER['HTTP_USER_AGENT'])) {
    $OS = $_SERVER['HTTP_USER_AGENT'];

    if (preg_match('/win/i',$OS)) {
     $OS = 'Windows';
    } else if (preg_match('/mac/i',$OS)) {
     $OS = 'Mac OS';
    } else if (preg_match('/linux/i',$OS)) {
     $OS = 'Linux';
    } else if (preg_match('/unix/i',$OS)) {
     $OS = 'Unix';
    } else if (preg_match('/bsd/i',$OS)) {
     $OS = 'BSD';
    } else {
     $OS = 'Other';
    }
  }
  return $OS;
}

function getIP(){
  $ip = "";
  $ips = "";

  if (!empty($_SERVER["HTTP_CLIENT_IP"])) {   
    $ip = $_SERVER["HTTP_CLIENT_IP"];
  }

  if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
  }

  if ($ip) {
    $ips = array_unshift($ips, $ip); 
  }

  $count = count($ips);
  for ($i = 0; $i < $count; $i++) {   
   if ($ips && !preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i])) {
      $ip = $ips[$i];
      break;    
    }  
  }  
  $tip = empty($_SERVER['REMOTE_ADDR']) ? $ip : $_SERVER['REMOTE_ADDR']; 

  return $tip; 
}

?>
