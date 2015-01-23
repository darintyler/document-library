<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  //---PROCESS POSTED DATA---
  if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') :
    if(!isset($_POST['dat_current'])) :
      $_POST['dat_current'] = GetValidDate();
    endif;
    //---ENSURE DOCUMENT HAS A TITLE---
    if(trim($_POST['str_title']) == '') :
      $_POST['str_title'] = '[untitled]';
    endif;
    //---ENSURE DOCUMENT HAS A TITLE---
    if($_POST['int_document_id'] == '0') : //---UPLOAD A NEW DOCUMENT/---
      include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/admin/_insert-action.php');
    else : //---UPDATE AN EXISTING DOCUMENT/--- 
      include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/admin/_update-action.php');
    endif;
  endif;
  //---/PROCESS POSTED DATA---
?>
<div class="body-container">
  <?php echo $str_message; ?>
  <?php if(!$bit_action_completed) : ?>
    <?php
      $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
      mysql_select_db($str_db_name);
      $qry_select_documents = '
        SELECT
          int_id,
          str_title,
          str_description,
          str_keywords,
          str_path,
          dat_uploaded
        FROM
          docs_documents
        WHERE
          int_id = \'' . mysql_real_escape_string($int_document_id) . '\'
        ORDER BY
          str_title
      ';
      $qry_select_documents = mysql_query($qry_select_documents);
      if(mysql_error($link) != ''){
        $bit_error = 1;
        $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
      }
      //---=---
      $str_title = '';
      $str_description = '';
      $str_keywords = '';
      while($obj_document = mysql_fetch_array($qry_select_documents)) :
        $str_title = $obj_document['str_title'];
        $str_description = $obj_document['str_description'];
        $str_keywords = $obj_document['str_keywords'];
      endwhile;
      //---=---
    ?>
    <form action="" method="post" enctype="multipart/form-data">
      <table>
        <tr>
          <th style="width:200px;">Title</th>
          <td><input class="focus-target" type="text" name="str_title" value="<?php echo $str_title; ?>"/></td>
        </tr>
        <tr>
          <th>Description</th>
          <td><textarea name="str_description"><?php echo $str_description; ?></textarea></td>
        </tr>
        <tr>
          <th>Keywords</th>
          <td><input type="text" name="str_keywords" value="<?php echo $str_keywords; ?>"/></td>
        </tr>
        <tr>
          <th>Categories</th>
          <td>
            <?php 
              $qry_select_categories = '
                SELECT
                  int_id,
                  str_category,
                  CASE
                    WHEN
                      int_id IN(
                        SELECT
                          int_category_id
                        FROM
                          docs_document_category_rels
                        WHERE
                          int_document_id = \'' . mysql_real_escape_string($int_document_id) . '\'
                          AND int_document_id != \'0\'
                      )
                    THEN
                      \'1\'
                    ELSE
                      \'0\'
                  END AS bit_selected
                FROM
                  docs_categories
                ORDER BY
                  str_category
              ';
              $qry_select_categories = mysql_query($qry_select_categories);
              if(mysql_error($link) != ''){
                $bit_error = 1;
                $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
              }
              //---=---
              while($obj_category = mysql_fetch_array($qry_select_categories)) : 
                if($obj_category['bit_selected']) :
                  $str_checked_text = 'checked="checked"';
                else :
                  $str_checked_text = '';
                endif;
                echo '<input type="checkbox" name="str_category_' . $obj_category['int_id'] . '" value="1" ' . $str_checked_text . '>' . $obj_category['str_category'] . '<br/>';
              endwhile; 
            ?>
          </td>
        </tr>
        <tr>
          <th>Document</th>
          <td>
            <p>
              <input type="file" name="fil_document_to_upload"/>
            </p>
            <?php if($int_document_id != 0) : ?>
              <p class="small-text">
                (Leave this blank to keep the existing document.)
              </p>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <input type="submit" name="str_submit_button" value="Submit Document"/>
            <input type="hidden" name="int_document_id" value="<?php echo $int_document_id; ?>"/>
          </td>
        </tr>
      </table>
      <input type="hidden" name="dat_current" id="dat_current" value=""/>
      <script type="text/javascript">
        var dat_current = new Date();
        var dat_year = dat_current.getFullYear();
        var dat_month = dat_current.getMonth() + 1;
        var dat_day = dat_current.getDate();
        var dat_hour = dat_current.getHours();
        var dat_minute = dat_current.getMinutes();
        var dat_second = dat_current.getSeconds();
        var str_current_date = String(dat_year + '-' + dat_month + '-' + dat_day + 'T' + dat_hour + ':' + dat_minute + ':' + dat_second);
        $(document).ready(function(){
          $('#dat_current').val(str_current_date);
        });
        //alert(str_current_date);
      </script>
    </form>
    <?php mysql_close($link); ?>
  <?php endif; ?>
</div>