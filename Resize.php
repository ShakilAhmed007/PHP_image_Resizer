<?php 
  class Resize{

    public static $image; //this should be the main dir 


    public static function image($data){
      self::$image = $data;
      return self::$image;
    }


    
    public static function makeImg($mainDir, $name, $fixheight, $fixwidth, $copyDir,$q=80){

      $destination = $mainDir;//main destination where the actual image is
      $copy_destination = $copyDir;//copy destionation where the main image's copy will go
      move_uploaded_file(self::$image['image']['tmp_name'], $destination.$name);
      copy($destination.$name, $copy_destination.$name);
      if(self::$image['image']['type'] == 'image/png'){
        $image = imagecreatefrompng($copy_destination.$name);
      }elseif(self::$image['image']['type'] == 'image/jpeg'){
        $image = imagecreatefromjpeg($copy_destination.$name);
      }elseif(self::$image['image']['type'] == 'image/gif'){
        $image = imagecreatefromgif($copy_destination.$name);
      }else{
        return false;
      }
      
      $filename = $copy_destination.$name;
  
      $thumb_width = $fixheight;
      $thumb_height = $fixwidth;
  
      $width = imagesx($image);
      $height = imagesy($image);
  
      $original_aspect = $width / $height;
      $thumb_aspect = $thumb_width / $thumb_height;
  
      if ( $original_aspect >= $thumb_aspect )
      {
         // If image is wider than thumbnail (in aspect ratio sense)
         $new_height = $thumb_height;
         $new_width = $width / ($height / $thumb_height);
      }
      else
      {
         // If the thumbnail is wider than the image
         $new_width = $thumb_width;
         $new_height = $height / ($width / $thumb_width);
      }
  
  
      $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
  
      // Resize and crop
      imagecopyresampled($thumb,
                         $image,
                         0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                         0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                         0, 0,
                         $new_width, $new_height,
                         $width, $height);
      imagejpeg($thumb, $filename, $q);

    }
  }

  //How to use?
  //============================================================================
  // if(isset($_REQUEST['submit'])){

  //   $image = $_FILES;
  //   $main_dir = "upload/";
  //   $corp_dir = "upload2/";
  //   $name = rand(4,10000)."-".$image['image']['name'];
  //you have to pass $_FILES on image static mehod
  //   Resize::image($_FILES);
  //   Resize::makeImg($main_dir, $name, 300, 300, $main_dir);
  //   
  // }
  //============================================================================
    
