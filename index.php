

<?



require('functions.php');

$site_url = get_site_url();
$page = get_requested_page();

// get content from server
$pages = get_top_level_pages();
$media = get_media('./pages/'.$page);

// start an HTML string to output to the page
$html = '';

// generate a nice array of data about the media
foreach ($media as $key => $filename) {

  // if the filename starts with - skip it
  if( $filename[0] == '-' ){
    unset($media[$key]);
    continue;
  }

  // if the content is a folder skip it
  if( is_dir('./pages/'.$page.'/'.$media[$key]) ){
    unset($media[$key]);
    continue;
  }

  $rel_path = './pages/'. $page .'/'. $filename;
  $abs_path = $site_url .'/pages/'. $page .'/'. $filename;

  $link = '';
  $extension = strtolower(pathinfo( $rel_path )['extension']);
  if( $extension == 'webloc' ){
    $link_xml = simplexml_load_file($rel_path);
    $link = $link_xml->dict->string;
  }

  $content = '';
  if( $extension == 'txt' || $extension == 'html'){
    $content = file_get_contents($rel_path);
  }

  // if the content is HTML, add it to the output string, then skip it
  if( $extension == 'html' ){
    $html .= $content;
    unset($media[$key]);
    continue;
  }

  $media[$key] = array(
    'rel_path'  => $rel_path,
    'abs_path'  => $abs_path,
    'hash'      => md5_file( $rel_path ),
    'filesize'  => filesize( $rel_path ),
    'extension' => $extension,
    'link'      => $link,
    'content'   => $content
  );
}

// get arrays of pages inside each subpage
$subpages = array();

$request = get_requested_path();

// step through each level of the URL path
$n = 1;
while( $n <= mb_substr_count($request, '/') && $request !== '/' ){

  $level = split2( $request, '/', $n );
  $level_rel_path = './pages/'.$level;
  $subpages[$level] = array();

  // find everything in this level
  $level_contents = scandir( $level_rel_path );

  // filter to just directories
  foreach ($level_contents as $key => $value) {

    if( $value == '.'  ){ continue; }
    if( $value == '..' ){ continue; }

    $content_path = './pages/'.$level.'/'.$level_contents[$key];

    // push directory into the subpages array
    if( is_dir($content_path) ){
      array_push($subpages[$level], $content_path);
    }
  }
  $n++;
}

// output to JS
echo '<script>';
echo   'var siteURL  = "'.$site_url.'";';
echo   'var pages    = '.$pages.';';
echo   'var page     = "'.$page.'";';
echo   'var media    = '.json_encode($media).';';
echo   'var subpages = '.json_encode($subpages).';';
echo '</script>';


?>
<link href="https://fonts.googleapis.com/css?family=Inconsolata:400,700&subset=latin-ext" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= $site_url ?>/style.css">

<body>

<div id="container">

<script>
  var _body      = document.querySelectorAll('body')[0]
  var _container = document.getElementById('container')
</script>

<script src='<?= $site_url ?>/helpers.js'></script>
<script src='<?= $site_url ?>/nav.js'></script>
<script src='<?= $site_url ?>/type_image.js'></script>
<script src='<?= $site_url ?>/type_link.js'></script>
<script src='<?= $site_url ?>/type_text.js'></script>
<script src='<?= $site_url ?>/type_video.js'></script>

<?

// output any HTML gathered earlier from this folder
echo $html;

?>

<script>

for( var i in media){

    content = media[i]
    if( content == undefined ){ continue }
    // image

    if( content.extension == 'gif' || content.extension == 'png' || content.extension == 'jpg' || content.extension == 'jpeg'){
    // if( content.type.indexOf('image') !== -1 ){
      outputImage(content)
      continue
    }  

    // link (webloc)
    if( content.extension == 'webloc' ){
      outputLink(content)
      continue
    }

    // text
    if( content.extension == 'txt' ){
    // if( content.type.indexOf('text') !== -1 ){
      outputText(content)
      continue
    }

    //video
    if( content.extension == 'mp4' ){
      outputVideo(content)
      continue;
    }
  }

</script>

</div>

<div class="last-updated">
  <?
  echo "Updated on ";
  echo date('F j, Y', filemtime('pages/'.$page."/."));
  ?>
</div>

<div class='latest-posts'>

  <?

  // if( $page == 'home' ){
    echo "<span>Pages Updated Recently:</span>";

    $latest = get_latest_posts();
  
    foreach ($latest as $key => $value) {
      
      $path = $latest[$key][path];

      echo "<a href='/".$path."'>".$path."</a>";
      echo " - ";
      echo date('F j, Y', $latest[$key][timestamp]);
      echo '<br>';

    }
  // }

  ?>
</div>

</body>