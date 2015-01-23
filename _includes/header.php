<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php include_once(dirname(__FILE__) . '/init.php'); //---INITIALIZE PAGE/--- ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>
      <?php echo strip_tags($str_page_title); ?>
    </title>
    <!--=-->
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8"/>
    <meta content="<?php echo strip_tags($str_page_title); ?>" name="description" />
    <meta content="<?php echo strip_tags($str_page_title); ?>" name="keywords" />
    <meta content="Darin Tyler" name="author" />
    <!--=-->
    <link href="<?php echo '/' . $str_document_library_directory_name; ?>/document-library.css" rel="stylesheet" type="text/css"/>
    <!--=-->
    <script src="<?php echo '/' . $str_document_library_directory_name; ?>/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $('.focus-target').focus();
      });
    </script>
  </head>
  <body>
    <h1 class="page-header">
      <?php echo $str_page_title; ?>
    </h1>
    