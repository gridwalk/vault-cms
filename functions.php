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
  // local or remote server
  $site_url = 'http://'.$_SERVER['HTTP_HOST'];
  if( strpos($site_url, 'localhost') !== false ){
    $site_url = 'http://'.$_SERVER['HTTP_HOST'] . '/vicovault.com';
  }
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