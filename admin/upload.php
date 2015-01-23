<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  //---INITIALIZE PAGE---
  include_once('../_includes/init.php');
  $int_document_id = 0;
  //---GENERATE PAGE TITLE---
  $str_page_title = GenerateNavigation(
    [
      '<a href="/' . $str_document_library_directory_name . '/admin/upload.php">Upload&nbsp;New&nbsp;Document</a>',
      '<a href="/' . $str_document_library_directory_name . '/">Document&nbsp;Library</a>'
    ]
  );
  //---/GENERATE PAGE TITLE---
  //---/INITIALIZE PAGE---
  include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/header.php');
?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/admin/_document-editor.php'); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/footer.php'); ?>
