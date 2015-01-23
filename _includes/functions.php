<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  //---=---
  function GenerateNavigation($arr_links = []){
    global $bit_debug,$bit_error,$str_message,$str_document_library_directory_name;
    //---=---
    $str_navigation_text = '';
    //---=---
    foreach($arr_links AS $int_index => $str_current_link) :
      if($int_index == 0 && count($arr_links) > 1) $str_navigation_text .= '<span style="text-decoration:underline;">';
      //---=---
      if($int_index == 1) $str_navigation_text .= '<span class="small-text-2">';
      //---=---
      if($int_index > 0) $str_navigation_text .= '
        <span style="display:inline-block;">
          <img src="/' . $str_document_library_directory_name . '/images/left-arrow.png" alt="<" style="vertical-align:middle;height:12px;"/>
      ';
      //---=---
      $str_navigation_text .= $str_current_link;
      //---=---
      if($int_index > 0) $str_navigation_text .= '</span>';
      if($int_index == (count($arr_links)-1)) $str_navigation_text .= '</span>';
      //---=---
      if($int_index == 0 && count($arr_links) > 1) $str_navigation_text .= '</span>';
    endforeach;
    return $str_navigation_text;
  }
  //---=---
  function GetIconFileName($str_source_file){
    global $bit_debug,$bit_error,$str_message;
    //---=---
    $arr_current_path_components = explode('.',$str_source_file);
    $file_type = $arr_current_path_components[count($arr_current_path_components)-1];
    $str_message .= ($bit_debug) ? '$file_type = ' . $file_type . '<br/>': '';
    switch($file_type) :
      case 'docx':
        $file_type = 'doc';
        break;
      case 'xlsx':
        $file_type = 'xls';
        break;
      case 'jpeg':
        $file_type = 'jpg';
        break;
    endswitch;
    if(false === array_search(
        $file_type,
        ['doc','xls','pdf','jpg','png']
      )
    ) :
      $file_type = 'generic';
    endif;
    return 'icon-' . $file_type . '.png';
  }
  //---=---
  function RecursiveCopy($src,$dst){
    global $bit_error,$str_message;
    //---=---
    try{
      //---ENSURE DIRECTORY PATHS END WITH A '/'---
      if(substr($src, strlen($src) - 1, 1) != '/'){
        $src .= '/';
      }
      if(substr($dst, strlen($dst) - 1, 1) != '/'){
        $dst .= '/';
      }
      //---/ENSURE DIRECTORY PATHS END WITH A '/'---
      $dir = opendir($src); 
      @mkdir($dst); 
      while(false !== ( $file = readdir($dir)) ){ 
        if (( $file != '.' ) && ( $file != '..' )){ 
          if ( is_dir($src . $file) ){ 
            RecursiveCopy($src . $file, $dst . $file); 
          } 
          else{ 
            copy($src . $file, $dst . $file); 
          } 
        } 
      } 
      closedir($dir);
    }
    catch(RuntimeException $e){
      $bit_error = 1;
      $str_message .= $str_message . $e->getMessage();
      return 0;
    }
    return 1;
    //---=---
    //source: gimmicklessgpt@gmail.com, http://php.net/manual/en/function.copy.php
  } 
  //---=---
  function GetValidDate($dat_date = ''){ //---ACCEPTS DATE IN 'YYYY-MM-DDTHH:MM:SS' FORMAT/---
    $str_timestamp = strtotime($dat_date);
    if($str_timestamp < 1) :
      $dat_date = date('Y-m-d H:i:s', time());
    endif;
    return $dat_date;
  }
  //---=---
  function UploadFile($fil_file_to_upload,$str_destination_directory = '/_protected-files/user-files/'){
    global $bit_error,$str_message;
    //---=---
    try{
      if(substr($str_destination_directory, strlen($str_destination_directory) - 1, 1) != '/'){ //---ENSURE DIRECTORY PATH END WITH A '/'/---
        $str_destination_directory .= '/';
      }
      //---CHECK FOR AN UNDEFINED FILE, MULTIPLE FILES, OR CORRUPT FILES
      if(
        !isset($fil_file_to_upload['error']) ||
        is_array($fil_file_to_upload['error'])
      ){
        $bit_error = 1;
        $str_message .= $str_message . '<p class="bold-text red-text">Invalid parameters were specified.</p>';
        //throw new RuntimeException('Invalid parameters.');
      }
      //---/CHECK FOR AN UNDEFINED FILE, MULTIPLE FILES, OR CORRUPT FILES
      if(!$bit_error) :
        //---CHECK FOR UPLOAD ERRORS IN $fil_file_to_upload['error']---
        switch ($fil_file_to_upload['error']) {
          case UPLOAD_ERR_OK:
            break;
          case UPLOAD_ERR_NO_FILE:
            if(!$bit_error) :
              $bit_error = 1;
              $str_message .= $str_message . '<p class="bold-text red-text">Your file could not be found.</p>';
              //throw new RuntimeException('Your file could not be found.');
            endif;
          case UPLOAD_ERR_INI_SIZE:
          case UPLOAD_ERR_FORM_SIZE:
            if(!$bit_error) :
              $bit_error = 1;
              $str_message .= $str_message . '<p class="bold-text red-text">Your file was either empty or it was too big.</p>';
              //throw new RuntimeException('Your file exceeded the filesize limit.');
            endif;
          default:
            if(!$bit_error) :
              $bit_error = 1;
              $str_message .= $str_message . '<p class="bold-text red-text">An unknown error occured.</p>';
              //throw new RuntimeException('An unknown error occured.');
            endif;
        }
        //---/CHECK FOR UPLOAD ERRORS IN $fil_file_to_upload['error']---
      endif;
      if(!$bit_error) :
        //---RESTRICT THE FILESIZE---
        if($fil_file_to_upload['size'] > 1000000){
          $bit_error = 1;
          $str_message .= $str_message . '<p class="bold-text red-text">Your file was too big.</p>';
          //throw new RuntimeException('Your file exceeded the filesize limit.');
        }
        //---/RESTRICT THE FILESIZE---
      endif;
      if(!$bit_error) :
        //---RESTRICT THE MIME TYPE---
        $obj_fileinfo_mime_type = new finfo(FILEINFO_MIME_TYPE);
        $str_file_mime_type = $obj_fileinfo_mime_type->file($fil_file_to_upload['tmp_name']);
        switch($str_file_mime_type) :
          case 'application/msword': 
            $str_file_extension = 'doc';
            break;
          case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
            $str_file_extension = 'docx';
            break;
          case 'application/rtf':
            $str_file_extension = 'rtf';
            break;
          case 'application/vnd.ms-excel':
            $str_file_extension = 'xls';
            break;
          case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            $str_file_extension = 'xlsx';
            break;
          case 'application/pdf':
            $str_file_extension = 'pdf';
            break;
          case 'image/jpg':
          case 'image/jpeg':
          case 'image/pjpeg':
            $str_file_extension = 'jpg';
            break;
          case 'image/png':
            $str_file_extension = 'png';
            break;
          default:
            $str_file_extension = '';
            break;
        endswitch;
        if($str_file_extension == '') :
          $bit_error = 1;
          $str_message .= $str_message . '<p class="bold-text red-text">Your file has an invalid file format: ' . $str_file_mime_type . '</p>';
        endif;
        //---/RESTRICT THE MIME TYPE---
      endif;
      //---=---
      if(!$bit_error) :
        //---GENERATE A SAFE, UNIQUE NAME FROM THE FILE'S BINARY DATA---
        $str_destination_file_name = sprintf(
          '%s.%s',
          sha1_file($fil_file_to_upload['tmp_name']),
          $str_file_extension
        );
        //---/GENERATE A SAFE UNIQUE NAME FROM THE FILE'S BINARY DATA---
        //---MOVE THE FILE TO ITS DESTINATION DIRECTORY---
        if(
          !move_uploaded_file(
            $fil_file_to_upload['tmp_name'],
            $str_destination_directory . $str_destination_file_name
          )
        ){
          $bit_error = 1;
          $str_message .= $str_message . '<p class="bold-text red-text">An error occured while importing your file.</p>';
          //throw new RuntimeException('An error occured while importing your file.');
        }
        //---/MOVE THE FILE TO ITS DESTINATION DIRECTORY---
      endif;
      //echo 'Your file was uploaded.';
      return $str_destination_file_name;
    }
    catch(RuntimeException $e){
      $bit_error = 1;
      $str_message .= $str_message . $e->getMessage();
      return 0;
    }
    //---=---
  }
  //---=---
  function DeleteDirectoryContent($str_directory_path){
    global $bit_error,$str_message;
    //---=---
    try{
      if(substr($str_directory_path, strlen($str_directory_path) - 1, 1) != '/'){ //---ENSURE DIRECTORY PATH END WITH A '/'/---
        $str_directory_path .= '/';
      }
      //---=---
      $handle = opendir($str_directory_path);
      while(($str_sub_directory = readdir($handle)) !== false) :
        if ($str_sub_directory != '.' && $str_sub_directory != '..') :
          DeleteDirectory($str_directory_path . $str_sub_directory);
        endif;
      endwhile;
      closedir($handle);
    }
    catch(RuntimeException $e){
      $bit_error = 1;
      $str_message .= $str_message . $e->getMessage();
      return 0;
    }
    return 1;
  }
  //---=---
  function DeleteDirectory($str_directory_path){
    global $bit_error,$str_message;
    //---=---
    //echo 'start...';
    if(!is_dir($str_directory_path)){
      $bit_error = 1;
      $str_message .= '<p class="bold-text red-text">An error occured while preparing your directory.</p>';
      //echo 'error...';
    }
    //---=---
    if(substr($str_directory_path, strlen($str_directory_path) - 1, 1) != '/'){ //---ENSURE DIRECTORY PATH END WITH A '/'/---
      $str_directory_path .= '/';
    }
    //---=---
    $files = glob($str_directory_path . '*', GLOB_MARK);
    foreach($files AS $file){
      //echo 'foreach...';
      if(is_dir($file)){
        self::DeleteDirectory($file);
      }
      else{
        unlink($file);
      }
    }
    //---=---
    rmdir($str_directory_path);
    //---=---
    //source: http://stackoverflow.com/questions/3349753/delete-directory-with-files-in-it
    /*
    */
  }
?>
