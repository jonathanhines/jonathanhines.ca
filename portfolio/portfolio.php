<?php
echo "<h1>Portfolio";
if(!isset($_GET['portfolio'])){
  $_GET['portfolio'] = "people";
}
echo " - ".ucwords($_GET['portfolio']);
echo "</h1>\r\n";
$workDir = "/portfolio/";
$workDirHandle = opendir($workDir);
$categories = array();
if($workDirHandle){
  while (false !== ($file = readdir($workDirHandle))) {
    //echo "$file<br/>";
    if(is_dir($workDir.$file)&&$file!=".."&&$file!="."&&$file!="thumbnails"){
      $categories[preg_replace("/^._/","",$file)] = $file;
    }
  }
}
closedir($workDirHandle);
asort($categories);
$links = array();
foreach($categories as $name => $category){
  if($name==$_GET['portfolio']){
    $links[]="<span class='active'>".ucwords($name)."</span>\r\n";
  }else{
    $links[]="<a href='/photography/".
      $name.
      "'>".ucwords($name)."</a>";
  }
}
echo "<div id='galleryLinks' style='text-align:right;height:50px;padding-top:25px;'>"
  .implode(" | \r\n",$links)."</div><!--galleryLinks-->\r\n";
if(isset($categories[$_GET['portfolio']])){
  echo "<div id='gallery' style='text-align: right; position:relative; width:730px; float:right;'>\r\n";
  $workDir = "/portfolio/".$categories[$_GET['portfolio']]."/";
    $workDirHandle = opendir($workDir);
    $images = array();
    if($workDirHandle){
      while (false !== ($file = readdir($workDirHandle))) {
        //echo "$file<br/>";
        if(!is_dir($workDir.$file)&&preg_match("/\.jpg$/i",$file)){
          $images[] = $file;
        }
      }
    }
    closedir($workDirHandle);
  if(count($images)>0){
    sort($images);
    if(file_exists($workDir."captions.txt")){
      $textFile = fopen($workDir."captions.txt","r");
      if($textFile){
        while (!feof($textFile)){
          $fileLine = fgets($textFile,4096);
          if(preg_match("/^#/",$fileLine)){continue;}
          $fileLine = trim(htmlspecialchars($fileLine));
          $fileLine = preg_replace("/\'/","&#39;",$fileLine);
          $captions[] = $fileLine;
        }
        fclose($textFile);
      }
    }
    $links=array();
    foreach ($images as $key=>$image){
      $link ="<div id='".$gallery."' class='galleryLinkWraperSection'>"
                ."<div class='imageBuffer'>"
                ."<div class='imageBoundry'>"
                ."<a class='galleryImage' href='".$workDir.$image."' rel='lightbox[".$_GET['gallery']."]'";
      if (isset($captions[$key])&&$captions[$key]!=""){
        $link.= " title='".$captions[$key]."'";
      }else{
        //$link.= " title=' '";
      }
      $link.=">"
                  //."<div class='imageBuffer'>"
                  ."<img src='/portfolio/thumbnails/"
                          .$image
                          ."' "
                          ."alt='"
                          ."' />"
                  ."</a></div></div></div>"
                  //."</div>"
                  ."\r\n";
      $links[]=$link;
    }
    echo implode("",$links);
  }
  echo "</div><!--gallery-->\r\n";
}

?>