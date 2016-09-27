<?

function split2($string,$needle,$nth){
  $max = strlen($string);
  $n = 0;
  for($i=0;$i<$max;$i++){
    if($string[$i]==$needle){
      $n++;
      if($n>=$nth){
        break;
      }
    }
  }
  $string_section = substr($string,0,$i);
  return $string_section;
}


function get_site_url(){

  $install_dir = str_replace('/index.php', '', $_SERVER['PHP_SELF']);
  $site_url = 'http://'.$_SERVER['HTTP_HOST'].$install_dir;
  
  return $site_url;
}

function get_requested_page(){
  // page query variable
  if( empty($_GET['page']) || !is_dir('./pages/'.$_GET['page']) ){
    $page = '_home';
  }else{
    $page = $_GET['page'];
  }
  return $page;
}

function get_top_level_pages(){
  return json_encode(array_slice(scandir('./pages'),2));
}

function get_media($path){
  return array_slice(scandir($path),2);
}


function get_latest_posts(){
  
  $posts = Array();

  $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('pages'));
  $last_path = '0000000';

  foreach($iter as $file) {
    if ($file->getFilename() == '.') {
      $path = $file->getPath();

      if( $path == 'pages' ){
        continue;
      }
      array_push($posts, Array(
        "path"      => str_replace('pages/', '', $path),
        "timestamp" => filemtime($path."/.")
      ));
    }
  }

  uasort($posts,function($a, $b){
    return $b['timestamp'] - $a['timestamp'];
  });

  // remove parent folders of updated pages
  // prevents showing a parent folder
  // when just one of its children was updated

  $last_path = '';
  foreach( $posts as $key=>$post ){

    $path = $post['path'];
    if( strpos($last_path, $path) !== false ){
      unset( $posts[$key] );
    }
    $last_path = $path;

  }

  $posts = array_slice($posts, 0, 10);
  return $posts;

}

function get_requested_path(){

  $site_url = get_site_url();

  // get request without leading slash, with trailing slash, without domain name at the start
  $requested_path = str_replace($site_url.'/', '', $_SERVER['SCRIPT_URI'].'/');
  return $requested_path;

}

function utf8ize($d) {
  if (is_array($d)) {
    foreach ($d as $k => $v) {
      $d[$k] = utf8ize($v);
    }
  } else if (is_string ($d)) {
    return utf8_encode($d);
  }
  return $d;
}