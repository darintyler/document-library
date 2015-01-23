<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  //---INITIALIZE PAGE---
  include_once('../_includes/init.php');
  //---GENERATE PAGE TITLE---
  $str_page_title = GenerateNavigation(
    [
      '<a href="/' . $str_document_library_directory_name . '/admin/bulk-delete.php">Bulk&nbsp;Document&nbsp;Delete</a>',
      '<a href="/' . $str_document_library_directory_name . '/">Document&nbsp;Library</a>'
    ]
  );
  //---/GENERATE PAGE TITLE---
  //---/INITIALIZE PAGE---
?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/header.php'); ?>
<?php
  //---PROCESS POSTED DATA---
  if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') :
    if(isset($_POST['bit_confirm_deletion']) && $_POST['bit_confirm_deletion']) : //---DELETION HAS BEEN CONFIRMED/---
      if($_POST['str_document_ids'] == '') : //---DOCUMENT IDS HAVE NOT BEEN SPECIFIED/---
        header('Location: ' . $str_url_root . $str_document_library_directory_name . '/index.php');
      else : //---DOCUMENT IDS HAVE BEEN SPECIFIED; DELETE DOCUMENTS/---/
        $arr_document_ids = array_unique(array_filter(explode(',',$_POST['str_document_ids'])));
        foreach($arr_document_ids AS $int_document_id) :
          include($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/admin/_delete-action.php');
        endforeach;
        //---=---
        $str_message = $str_message . '
          <ul>
            <li><a href="/' . $str_document_library_directory_name . '/index.php" class="focus-target">Go to Document Library homepage</a></li>
          </ul>
        ';
      endif;
    else : //---DELETION HAS NOT YET BEEN CONFIRMED; GENERATE LIST OF IDS/---
      $arr_document_ids = array();
      foreach($_POST AS $key=>$value) :
        $pattern_1 = '/^int_document_id_/';
        $subject_1 = $key;
        $bit_preg_match_result_1 = preg_match($pattern_1,$subject_1,$matches_1);
        $pattern_2 = '/(?<=int_document_id_)[0-9]+/';
        $subject_2 = $key;
        $bit_preg_match_result_2 = preg_match($pattern_2,$subject_2,$matches_2);
        if($bit_preg_match_result_1 && $bit_preg_match_result_2) :
          array_push($arr_document_ids,$matches_2[0]);
        endif;
      endforeach;
      //---=---
      $str_document_ids = implode(',',$arr_document_ids);
      //echo '...' . $str_document_ids . '...';
    endif;
  else :
    header('Location: ' . $str_url_root . $str_document_library_directory_name . '/index.php');
  endif;
  //---/PROCESS POSTED DATA---
?>
<div class="body-container">
  <?php echo $str_message; ?>
  <?php //---=--- ?>
  <?php if(!$bit_action_completed) : //---DISLAY THE DELETE CONFIRMATION/--- ?>
    <?php
      $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
      mysql_select_db($str_db_name);
      //---=---
      $str_sql_expression = '';
      foreach($arr_document_ids AS $int_document_id) :
        $str_sql_expression = $str_sql_expression . 'OR int_id = \'' . mysql_real_escape_string($int_document_id) . '\'';
      endforeach;
      //---=---
      $qry_select_documents = '
        SELECT
          str_title
        FROM
          docs_documents
        WHERE
          1=0
          ' . $str_sql_expression . '
        ORDER BY
          str_title
      ';
      $qry_select_documents = mysql_query($qry_select_documents);
      $int_num_rows = mysql_num_rows($qry_select_documents);
      //echo '...' . $int_num_rows . '...';
      if(mysql_error($link) != ''){
        $bit_error = 1;
        $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
      }
      mysql_close($link);
      //---=---
    ?>
    <?php if($int_num_rows > 0) : ?>
      <form action="" method="post">
        <p>
          Are you sure you want to delete the following documents?
        </p>
        <ul>
          <?php while($document = mysql_fetch_array($qry_select_documents)) : ?>
            <li><strong><?php echo $document['str_title']; ?></strong></li>
          <?php endwhile; ?>
        </ul>
        <p>
          <input type="submit" name="str_submit_button" class="focus-target button" value="Yes"/>
          <input type="button" name="str_cancel_button" value="No" class="button" onclick="javascript:window.location='<?php echo $str_url_root . $str_document_library_directory_name . '/index.php' ;?>';"/>
          <input type="hidden" name="bit_confirm_deletion" value="1"/>
          <input type="hidden" name="str_document_ids" value="<?php echo $str_document_ids; ?>"/>
        </p>
      </form>
    <?php else : ?>
      <p class="red-text">
        No document were selected for deletion.
      </p>
      <ul>
        <li><a href="/<?php echo $str_document_library_directory_name; ?>/index.php" class="focus-target">Go to Document Library homepage</a></li>
      </ul>
    <?php endif; ?>
  <?php endif; ?>
</div>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/footer.php'); ?>
