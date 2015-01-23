<?php /**
* The Document Library by Darin Tyler is licensed under a Creative Commons Attribution-ShareAlike 4.0 International License. 
* You many remix, tweak, and build upon the Document Library, even for commercial purposes, as long as you credit Darin Tyler, include a link back to http://www.darintyler.com, and license your new creations under identical terms. 
* To view a copy of the Creative Commons Attribution-ShareAlike 4.0 International License, visit http://creativecommons.org/licenses/by-sa/4.0/.
*/ ?>
<?php
  //---INITIALIZE PAGE---
  include_once('_includes/init.php');
  //---GENERATE PAGE TITLE---
  $str_page_title = GenerateNavigation(
    [
      '<a href="/' . $str_document_library_directory_name . '/">Document&nbsp;Library</a>'
    ]
  );
  //---/GENERATE PAGE TITLE---
  $str_no_results_message = '
    <p>
      There are no documents in the library.
    </p>
  ';
  //---ENSURE ALL $_GET AND $_POST VARIABLE VALUES ARE LOWERCASE---
  foreach($_GET AS $key=>$value) :
    $_GET[$key] = strtolower($value);
  endforeach;
  foreach($_POST AS $key=>$value) :
    $_GET[$key] = strtolower($value);
  endforeach;
  //---/ENSURE ALL $_GET AND $_POST VARIABLE VALUES ARE LOWERCASE---
  //---INITIALIZE $_GET VARIABLES---
  $bit_paginate_view_more = isset($_GET['more']) ? $_GET['more'] : '';
  $int_pagination_start_row = isset($_GET['start']) ? $_GET['start'] : '';
  $str_sort_category = isset($_GET['sort']) ? $_GET['sort'] : '';
  $bit_sort_asc = isset($_GET['asc']) ? $_GET['asc'] : '';
  $str_keywords = isset($_GET['keywords']) ? $_GET['keywords'] : '';
  $int_category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
  //---/INITIALIZE $_GET VARIABLES---
  //---INITIALIZE $_POST VARIABLES---
  if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') : //---OVERWRITE $_GET VARIABLES WITH $_POST VARIABLES/---
    $bit_paginate_view_more = isset($_POST['bit_paginate_view_more']) ? $_POST['bit_paginate_view_more'] : $bit_paginate_view_more;
    $int_pagination_start_row = isset($_POST['int_pagination_start_row']) ? $_POST['int_pagination_start_row'] : $int_pagination_start_row;
    $str_sort_category = isset($_POST['str_sort_category']) ? $_POST['str_sort_category'] : $str_sort_category;
    $bit_sort_asc = isset($_POST['bit_sort_asc']) ? $_POST['bit_sort_asc'] : $bit_sort_asc;
    $str_keywords = isset($_POST['str_keywords']) ? $_POST['str_keywords'] : $str_keywords;
    $int_category_id = isset($_POST['int_category_id']) ? $_POST['int_category_id'] : $int_category_id;
  endif;
  //---/INITIALIZE $_POST VARIABLES---
  //---VALIDATE VARIABLES---
  if($bit_paginate_view_more != 0 && $bit_paginate_view_more != 1) :
    $bit_paginate_view_more = 0;
  endif;
  //---=---
  if(!is_numeric($int_pagination_start_row)) :
    $int_pagination_start_row = 1;
  endif;
  $int_pagination_start_row = floor(abs($int_pagination_start_row)); //---ENSURE START ROW IS NOT A DECIMAL OR NEGATIVE/---
  //---=---
  if($str_sort_category != 'description' && $str_sort_category != 'date') :
    $str_sort_category = 'description';
  endif;
  //---=---
  if(!($bit_sort_asc == 1 || $bit_sort_asc == 0) || $bit_sort_asc == '') :
    $bit_sort_asc = 1;
  endif;
  $str_message .= ($bit_debug) ? '<p>$bit_sort_asc = ' . $bit_sort_asc . '</p>' : '';
  $str_message .= ($bit_debug) ? '<p>$str_sort_category = ' . $str_sort_category . '</p>' : '';
  $str_message .= ($bit_debug) ? '<p>$str_keywords = ' . $str_keywords . '</p>' : '';
  $str_message .= ($bit_debug) ? '<p>$int_category_id = ' . $int_category_id . '</p>' : '';
  //---/VALIDATE VARIABLES---
  //---CONFIGURE $_GET QUERY STRING---
  $arr_get_variables = [];
  if($bit_paginate_view_more == 1) array_push($arr_get_variables, 'more=' . $bit_paginate_view_more);
  if($int_pagination_start_row > 1) array_push($arr_get_variables, 'start=' . $int_pagination_start_row);
  if($str_keywords != '') array_push($arr_get_variables, 'keywords=' . $str_keywords);
  if($int_category_id != '') array_push($arr_get_variables, 'category_id=' . $int_category_id);
  //---=---
  $str_asc_get_variable = 'asc=' . (int)(!$bit_sort_asc); //---WHEN SET, $_GET['asc'] SHOULD BE OPPOSITE THE CURRENT SETTING/---
  //---=---
  $str_get_variables = '';
  foreach($arr_get_variables AS $int_index=>$str_current_get_variable) :
    $str_get_variables .= ($int_index > 0) ? '&' : ''; //---PREPEND AN AMPERSTAND (&) IF NEEDED/---
    $str_get_variables .= $str_current_get_variable;
  endforeach;
  $str_message .= ($bit_debug) ? '$str_get_variables = ' . $str_get_variables . '<br/>' : '';
  //---/CONFIGURE $_GET QUERY STRING---
  //---INITIALIZE DATABASE---
  $link = mysql_connect($str_db_address,$str_db_username,$str_db_password);
  mysql_select_db($str_db_name);
  //---CREATE KEYWORD QUERY EXPRESSION---
  if($str_keywords != '') :
    $str_qry_keywords_expression = '';
    $arr_keywords = preg_split('/[\s,]+/',$str_keywords,0,PREG_SPLIT_NO_EMPTY);
    if(count($arr_keywords) > 0) :
      foreach($arr_keywords AS $str_keyword) :
        $str_qry_keywords_expression .= '
          AND(
            D.str_title LIKE \'%' . mysql_real_escape_string($str_keyword) . '%\'
            OR D.str_keywords LIKE \'%' . mysql_real_escape_string($str_keyword) . '%\'
          )
        ';
      endforeach;
    endif;
  else :
    $arr_keywords = [];
    $str_qry_keywords_expression = '';
  endif;
  //---CREATE CATEGORY QUERY EXPRESSION---
  if($int_category_id != '') :
    $str_qry_category_expression = '
      JOIN
        docs_document_category_rels DCR
      ON 
        DCR.int_document_id = D.int_id
        AND DCR.int_category_id = \'' . mysql_real_escape_string($int_category_id) . '\'
    ';
  else :
    $str_qry_category_expression = '';
  endif;
  //---/CREATE CATEGORY QUERY EXPRESSION---
  //---CREATE ORDER BY QUERY EXPRESSION---
  $str_qry_sort_order_term = ($bit_sort_asc) ? 'ASC' : 'DESC';
  $str_qry_alternate_sort_order_term = ($bit_sort_asc) ? 'DESC' : 'ASC';
  $str_qry_order_by_expression = ($str_sort_category == 'description')
  ? '
      D.str_title ' . $str_qry_sort_order_term . ',
      D.str_description ' . $str_qry_sort_order_term . ',
      D.dat_last_updated ' . $str_qry_sort_order_term . '
    '
  : '
      D.dat_last_updated ' . $str_qry_alternate_sort_order_term . ',
      D.str_title ' . $str_qry_sort_order_term . ',
      D.str_description ' . $str_qry_sort_order_term . '
    '
  ;
  //---/CREATE ORDER BY QUERY EXPRESSION---
  //---GET DOCUMENTS---
  $qry_select_documents = '
    SELECT
      D.int_id,
      D.str_title,
      D.str_description,
      D.str_keywords,
      D.str_path,
      D.dat_last_updated
    FROM
      docs_documents D
    ' . $str_qry_category_expression . '
    WHERE
      str_username = \'' . mysql_real_escape_string($str_username) . '\'
      ' . $str_qry_keywords_expression . '
    ORDER BY
      ' . $str_qry_order_by_expression . '
  ';
  $str_message .= ($bit_debug) ? '<p>$qry_select_documents = ' . $qry_select_documents . '</p>' : '';
  $qry_select_documents = mysql_query($qry_select_documents);
  //---=---
  $int_num_rows = mysql_num_rows($qry_select_documents);
  $str_message .= ($bit_debug) ? '<p>$int_num_rows = ' . $int_num_rows . '</p>' : '';
  if(($str_qry_category_expression != '' || $str_qry_keywords_expression != '') && $int_num_rows < 1):
    $str_no_results_message = '
      <p>
        No documents were found which match your search query.
      </p>
    ';
  endif;
  //---=---
  if(mysql_error($link) != ''){
    $bit_error = 1;
    $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
  }
  //---/GET DOCUMENTS---
  //---GET CATEGORIES---
  $qry_select_categories = '
    SELECT
      int_id,
      str_category
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
  //---/GET CATEGORIES---
  //---/INITIALIZE DATABASE---
  //---INITIALIZE PAGINATION VARIABLES---
  if(!$bit_paginate_view_more) :
    $int_pagination_rows_per_page = 5; //---CUSTOMIZABLE/---
  else :
    $int_pagination_rows_per_page = 15; //---CUSTOMIZABLE/---
  endif;
  //---=---
  $int_pagination_pages_to_display = 4; //---WILL AUTOMATICALLY BE ADJUSTED TO AT LEAST 3/--- //---CUSTOMIZABLE/---
  $int_current_pagination_page = ceil($int_pagination_start_row/$int_pagination_rows_per_page);
  $int_next_pagination_page_start_row = $int_pagination_start_row+$int_pagination_rows_per_page;
  $int_pagination_end_row = min($int_num_rows,$int_next_pagination_page_start_row-1);
  //---CREATE NUMBER OF RESULTS MESSAGE---
  if($int_num_rows >= 1): //---THERE ARE SOME DOCUMENTS. DISPLAY A RESULTS MESSAGE/---
    $str_num_results_message = '
      <p class="small-text">
        Displaying documents <strong>' . $int_pagination_start_row . '</strong> 
        through <strong>' . $int_pagination_end_row . '</strong>
    ';
    if($str_qry_category_expression != '' || $str_qry_keywords_expression != '') : //---A SEARCH WAS PERFORMED/---
      $str_num_results_message .= 'out of <strong>' . $int_num_rows . '</strong> search results.';
    else : //---NO SEARCH WAS PERFORMED/---
      $str_num_results_message .= 'out of <strong>' . $int_num_rows . '</strong> total documents.</p>';
    endif;
    $str_num_results_message .= '</p>';
  else : //---THERE ARE NO DOCUMENTS. DO NOT DISPLAY A RESULTS MESSAGE/---
    $str_num_results_message = '';
  endif;
  //---/CREATE NUMBER OF RESULTS MESSAGE---
  $str_pagination_hidden_field_text = '
    <input
      type="hidden" name="str_keywords" value="' . $str_keywords . '"
    /><input
      type="hidden" name="int_category_id" value="' . $int_category_id . '"
    /><input
      type="hidden" name="sort" value="' . $str_sort_category . '"
    /><input
      type="hidden" name="asc" value="' . $bit_sort_asc . '"
    />
  ';
  //---=---
  $int_total_pagination_pages = ceil($int_num_rows/$int_pagination_rows_per_page);
  $str_message .= ($bit_debug) ? '<p>$int_total_pagination_pages = ' . $int_total_pagination_pages . '</p>' : '';
  $int_total_pagination_controls_to_display = 
    ($int_total_pagination_pages <= $int_pagination_pages_to_display) //---TOTAL PAGES IS GREATER THAN PAGES TO DISPLAY/---
        ? $int_total_pagination_pages //---DISPLAY ALL CONTROLS
        : max($int_pagination_pages_to_display,3); //---ELSE DISPLAY ABBREVIATED CONTROLS/---
  $str_message .= ($bit_debug) ? '<p>$int_total_pagination_controls_to_display = ' . $int_total_pagination_controls_to_display . '</p>' : '';
  //---CONFIGURE START AND END PAGE---
  $int_start_pagination_page = $int_current_pagination_page-floor($int_total_pagination_controls_to_display/2);
  $int_end_pagination_page = $int_current_pagination_page-1+ceil($int_total_pagination_controls_to_display/2);
  $str_message .= ($bit_debug) ? '<p>$int_start_pagination_page (initial) = ' . $int_start_pagination_page . '</p>' : '';
  $str_message .= ($bit_debug) ? '<p>$int_end_pagination_page (initial) = ' . $int_end_pagination_page . '</p>' : '';
  //--ENSURE START PAGE IS NOT TOO LOW---
  if($int_start_pagination_page < 1) :
    $int_start_pagination_page = $int_start_pagination_page-1; //---SKIP PAGE 0/--- 
    $int_end_pagination_page += $int_start_pagination_page*(-1); //---ADD EXTRA START PAGE OFFSET TO END PAGE OFFSET/---
    $int_start_pagination_page = 1; //---REMOVE NEGATIVE OFFSET; START ON PAGE 1/---
  endif;
  $str_message .= ($bit_debug) ? '<p>$int_start_pagination_page (altered) = ' . $int_start_pagination_page . '</p>' : '';
  //--/ENSURE START PAGE IS NOT TOO LOW---
  //--ENSURE END PAGE IS NOT TOO HIGH---
  if($int_end_pagination_page > $int_total_pagination_pages) :
    $int_start_pagination_page -= $int_end_pagination_page-$int_total_pagination_pages;
    if($int_start_pagination_page < 1) :
      $int_start_pagination_page = 1;
    endif;
    $int_end_pagination_page = $int_total_pagination_pages-1;
  endif;
  $str_message .= ($bit_debug) ? '<p>$int_end_pagination_page (altered) = ' . $int_end_pagination_page . '</p>' : '';
  //--/ENSURE END PAGE IS NOT TOO HIGH---
  //---REPLACE START PAGE WITH ABBREVIATION IF NEEDED---
  if($int_start_pagination_page < $int_current_pagination_page-1): //---START PAGE IS NOT ADJACENT TO CURRENT PAGE (WE WILL ALWAYS DISPLAY PAGES ADJACENT TO CURRENT PAGE)
    $int_start_pagination_page++; //---DO NOT DISPLAY SECOND-TO-LAST PAGE; WE WILL DISPLAY AN ABBREVIATION INSTEAD/---
  endif;
  $str_message .= ($bit_debug) ? '<p>$int_start_pagination_page (altered 2) = ' . $int_start_pagination_page . '</p>' : '';
  //---=---
  if($int_start_pagination_page >= 3) :
    $bit_display_previous_page_abbreviation = 1;
  else :
    $bit_display_previous_page_abbreviation = 0;
  endif;
  //---/REPLACE START PAGE WITH ABBREVIATION IF NEEDED---
  //---REPLACE END PAGE WITH ABBREVIATION IF NEEDED---
  if($int_end_pagination_page > $int_current_pagination_page+1) : //---END PAGE IS NOT ADJACENT TO CURRENT PAGE (WE WILL ALWAYS DISPLAY PAGES ADJACENT TO CURRENT PAGE)
    $int_end_pagination_page--; //---DO NOT DISPLAY SECOND-TO-LAST PAGE; WE WILL DISPLAY AN ABBREVIATION INSTEAD/---
  endif;
  $str_message .= ($bit_debug) ? '<p>$int_end_pagination_page (altered 2) = ' . $int_end_pagination_page . '</p>' : '';
  //---=---
  if($int_end_pagination_page <= $int_total_pagination_pages-2) :
    $bit_display_next_page_abbreviation = 1;
  else :
    $bit_display_next_page_abbreviation = 0;
  endif;
  //---/REPLACE END PAGE WITH ABBREVIATION IF NEEDED---
  //---/CONFIGURE START AND END PAGE---
  //---/INITIALIZE PAGINATION VARIABLES---
  //---/INITIALIZE PAGE---
  include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/header.php');
?>
<div class="body-container">
  <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" style="display:inline;">
    <input class="padding focus-target" type="text" name="str_keywords" id="str_keywords" value="<?php echo $str_keywords; ?>"/>
    <script type="text/javascript">
      $(document).ready(function(){
        $('#str_keywords').focus(function(){
          $(this).select();
        });
      });
    </script>
    <select name="int_category_id">
      <option class="padding" value="">-All Categories-</option>
      <?php 
        while($obj_category = mysql_fetch_array($qry_select_categories)) :
          $str_category_selected_text = ($int_category_id == $obj_category['int_id'])
            ? 'selected="selected"'
            : '';
          echo '<option class="padding" ' . $str_category_selected_text . 'value="' . $obj_category['int_id'] . '">' . $obj_category['str_category'] . '</option>';
        endwhile;
      ?>
    </select>
    <input class="button" type="submit" name="str_submit_button" value="Search"/>
    <input type="hidden" name="sort" value="<?php echo $str_sort_category; ?>"/>
    <input type="hidden" name="asc" value="<?php echo $bit_sort_asc; ?>"/>
  </form>
  <a class="button" style="float:right;" href="admin/upload.php">
    Upload New Document
  </a>
  <div style="clear:both;height:0;"></div>
  <?php if($int_num_rows > 0) : ?>
    <?php echo $str_num_results_message; ?>
    <form action="admin/bulk-delete.php" method="post" id="document-library-form">
      <table>
        <tr>
          <th>Download</th>
          <th>
            <a 
              class="sort-order-link" 
              <?php if($str_sort_category == 'description') echo 'style="text-decoration:underline;"'; ?>
              href="<?php 
                echo $_SERVER['SCRIPT_NAME'] . '?' . $str_get_variables;
                if($str_get_variables != '') echo '&';
                echo 'sort=description';
                if($str_sort_category == 'description') echo '&' . $str_asc_get_variable;
              ?>"
            >
              Description 
              <?php 
                if($str_sort_category == 'description') : 
                  echo ($bit_sort_asc) 
                    ? '<span class="small-text">(A-Z)</span>' 
                    : '<span class="small-text">(Z-A)</span>'
                  ; 
                endif; 
              ?>
            </a>
          </th>
          <th>Categories</th>
          <th>
            <a 
              class="sort-order-link" 
              <?php if($str_sort_category == 'date') echo 'style="text-decoration:underline;"'; ?>
              href="<?php 
                echo $_SERVER['SCRIPT_NAME'] . '?' . $str_get_variables;
                if($str_get_variables != '') echo '&';
                echo 'sort=date';
                if($str_sort_category == 'date') echo '&' . $str_asc_get_variable;
              ?>"
            >
                Date&nbsp;Last&nbsp;Updated 
              <?php 
                if($str_sort_category == 'date') : 
                  echo ($bit_sort_asc) 
                    ? '<span class="small-text">(New-Old)</span>' 
                    : '<span class="small-text">(Old-New)</span'
                  ; 
                endif; 
              ?>
            </a>
          </th>
          <th>Administration</th>
          <th>
            <select name="str_bulk_action" class="bulk-action-field">
              <option value="" selected="selected">-Bulk Action-</option>
              <option value="delete">Delete</option>
            </select>
            <input type="submit" name="str_submit_button" class="submit-button" value="Submit"/>
            <script type="text/javascript">
              $(document).ready(function(){
                $('.submit-button').css('display','none');
                $('.bulk-action-field').change(function(){
                  if($(this).val() == 'delete'){
                    $('#document-library-form').submit();
                  }
                });
              });
            </script>
          </th>
        </tr> 
        <?php 
          for(
            $int_current_row = $int_pagination_start_row;
            $int_current_row <= $int_pagination_end_row;
            $int_current_row++
          ) :
            mysql_data_seek($qry_select_documents,$int_current_row-1);
            $arr_document = mysql_fetch_assoc($qry_select_documents);
        ?>
          <?php
            $qry_select_document_categories = '
              SELECT
                C.int_id,
                C.str_category
              FROM
                docs_document_category_rels AS DCR
              JOIN
                docs_categories AS C
              ON
                C.int_id = DCR.int_category_id
              WHERE
                DCR.int_document_id = \'' . mysql_real_escape_string($arr_document['int_id']) . '\'
              ORDER BY
                str_category
            ';
            $qry_select_document_categories = mysql_query($qry_select_document_categories);
            if(mysql_error($link) != ''){
              $bit_error = 1;
              $str_message = $str_message . '<p class="bold-text red-text">' . mysql_error($link) . '</p>';
            }
            //---BOLD MATCHED KEYWORDS---
            $str_current_modified_title = $arr_document['str_title']; //---INITIALIZE MODIFIED TITLE TO SAME AS ORIGINAL TITLE/---
            foreach($arr_keywords AS $str_current_keyword) :
              $str_message .= ($bit_debug) ? '$str_current_keyword = ' . $str_current_keyword . '<br/>' : '';
              $pattern = '/(?<!\<strong\>)' . $str_current_keyword . '(?![^\<]+\>)(?!\<\/strong\>)/i';
              $subject = $str_current_modified_title;
              $bit_preg_match_result = preg_match_all($pattern,$subject,$matches,PREG_OFFSET_CAPTURE);
              for(
                $int_current_index = count($matches[0])-1;
                $int_current_index >= 0;
                $int_current_index--
              ) :
                $str_message .= ($bit_debug) ? '$int_current_index = ' . $int_current_index . '<br/>' : '';
                //---CREATE BOLDED MATCHED WORD---
                $str_current_modified_matched_word = substr(
                  $str_current_modified_title, 
                  $matches[0][$int_current_index][1],
                  strlen($matches[0][$int_current_index][0])
                ); //---GET MATCHED WORD FROM TITLE---
                $str_message .= ($bit_debug) ? '$str_current_modified_matched_word = ' . $str_current_modified_matched_word . '<br/>' : '';
                $str_current_modified_matched_word = '<strong>' . $str_current_modified_matched_word . '</strong>'; //---BOLD MATCHED WORD/---
                $str_message .= ($bit_debug) ? '$str_current_modified_matched_word (bold) = ' . $str_current_modified_matched_word . '<br/>' : '';
                //---/CREATE BOLDED MATCHED WORD---
                $str_current_modified_title = substr_replace(
                  $str_current_modified_title, 
                  $str_current_modified_matched_word, 
                  $matches[0][$int_current_index][1],
                  strlen($matches[0][$int_current_index][0])
                ); //---REPLACE MATCHED WORD WITH BOLDED MATCHED WORD IN MODIFIED TITLE/---
                $str_message .= ($bit_debug) ? '$str_current_modified_title = ' . $str_current_modified_title . '<br/>' : '';
              endfor;
            endforeach;
            //---/BOLD MATCHED KEYWORDS---
          ?>
          <tr>
            <td><a class="button" href="download.php?document_id=<?php echo $arr_document['int_id']?>">Download</a></td>
            <td>
              <table class="unstyled">
                <tr>
                  <td style="width:1px;">
                    <a style="display:inline-block;margin:0 5px 0 0;" href="details.php?document_id=<?php echo $arr_document['int_id']?>">
                      <img style="width:50px;" src="images/<?php echo GetIconFileName($arr_document['str_path']); ?>" alt="Document Icon"/>
                    </a>
                  </td>
                  <td>
                    <p>
                      <a href="details.php?document_id=<?php echo $arr_document['int_id']?>">
                        <?php echo $str_current_modified_title; ?>
                      </a>
                    </p>
                    <p>
                      <?php echo $arr_document['str_description']; ?>
                    </p>
                  </td>
                </tr>
              </table>
            </td>
            <td>
              <?php 
                while($obj_category = mysql_fetch_array($qry_select_document_categories)) : 
                  echo '<p><a href="index.php?category_id=' . $obj_category['int_id'] . '">' . $obj_category['str_category'] . '</a></p>';
                endwhile;
              ?>
            </td>
            <td>
              <?php 
                $dat_last_updated = new DateTime($arr_document['dat_last_updated']);
                echo $dat_last_updated->format('F j, Y') . ' at ' . $dat_last_updated->format('g:i:s a');
              ?>
            </td>
            <td>
              <a class="button" href="admin/update.php?document_id=<?php echo $arr_document['int_id']; ?>">
                Update
              </a>
              <a class="button" href="admin/delete.php?document_id=<?php echo $arr_document['int_id']; ?>">
                Delete
              </a>
            </td>
            <td style="vertical-align:middle;text-align:center;">
              <input type="checkbox" name="int_document_id_<?php echo $arr_document['int_id']; ?>" value="<?php echo $arr_document['int_id']; ?>"/>
            </td>
          </tr>
        <?php 
          endfor;
        ?>
      </table>
    </form>
    <?php 
      //---DSISPLAY PAGINATION CONTROLS---
      if(1||$int_total_pagination_pages > 1) : //---ALWAYS DISPLAY PAGINATION CONTROLS/---
    ?>
      <div class="margin-top" style="text-align:center;">
        <?php if(($int_pagination_start_row-$int_pagination_rows_per_page) >= 1) : ?>
          <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" style="display:inline;margin:0;padding:0;"
            ><input 
              type="submit" name="prev" value="Prev" class="pagination-button button"
            /><input
              type="hidden" name="int_pagination_start_row" value="<?php echo $int_pagination_start_row-$int_pagination_rows_per_page; ?>"
            /><input
              type="hidden" name="bit_paginate_view_more" value="<?php echo $bit_paginate_view_more; ?>"
            /><?php echo $str_pagination_hidden_field_text; ?>
          </form>
        <?php else : ?>
          <span class="deactivated-pagination-button">
            Prev
          </span>
        <?php endif; ?>
        <?php //---DISPLAY FIRST PAGINATION PAGE--- ?>
        <?php if($int_current_pagination_page == 1) : ?>
          <span class="active-pagination-button button">1</span>
        <?php else : ?>
          <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" style="display:inline;margin:0;padding:0;"
            ><input 
              type="submit" name="int_current_pagination_page" value="1" class="pagination-button button"
            /><input
              type="hidden" name="int_pagination_start_row" value="1"
          /><input
            type="hidden" name="bit_paginate_view_more" value="<?php echo $bit_paginate_view_more; ?>"
            /><?php echo $str_pagination_hidden_field_text; ?>
          </form>
        <?php endif; ?>
        <?php //---/DISPLAY FIRST PAGINATION PAGE--- ?>
        <?php 
          if($bit_display_previous_page_abbreviation) :
            echo '...';
          endif;
        ?>
        <?php //---DISPLAY MIDDLE PAGINATION PAGES--- ?>
        <?php
          for(
            $int_pagination_page_index = $int_start_pagination_page;
            $int_pagination_page_index <= $int_end_pagination_page;
            $int_pagination_page_index++
          ) :
            if(
              $int_pagination_page_index == 1
              || $int_pagination_page_index == $int_total_pagination_pages
            ) :
              continue; //---SKIP FIRST AND LAST PAGINATION; WE ALREADY DISPLAYED IT/WILL DISPLAY IT/---
            endif;
            //---=---
        ?>
          <?php if($int_current_pagination_page == $int_pagination_page_index) : ?>
            <span class="active-pagination-button button"><?php echo $int_pagination_page_index; ?></span>
          <?php else : ?>
            <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" style="display:inline;margin:0;padding:0;"
              ><input 
                type="submit" name="int_current_pagination_page" value="<?php echo $int_pagination_page_index; ?>" class="pagination-button button"
              /><input
                type="hidden" name="int_pagination_start_row" value="<?php echo($int_pagination_page_index*$int_pagination_rows_per_page)-($int_pagination_rows_per_page-1); ?>"
            /><input
              type="hidden" name="bit_paginate_view_more" value="<?php echo $bit_paginate_view_more; ?>"
              /><?php echo $str_pagination_hidden_field_text; ?>
            </form>
          <?php endif; ?>
          <?php //---=--- ?>
        <?php endfor; ?>
        <?php //---/DISPLAY MIDDLE PAGINATION PAGES--- ?>
        <?php
          if($bit_display_next_page_abbreviation) :
            echo '...';
          endif;
        ?>
        <?php //---DISPLAY LAST PAGINATION PAGE--- ?>
        <?php if($int_total_pagination_pages > 1) : //---ONLY DISPLAY IF THERE IS MORE THAN 1 PAGINATION PAGE/--- ?>
          <?php if($int_current_pagination_page == $int_total_pagination_pages) : ?>
            <span class="active-pagination-button button"><?php echo $int_current_pagination_page; ?></span>
          <?php else : ?>
            <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" style="display:inline;margin:0;padding:0;"
              ><input 
                type="submit" name="int_current_pagination_page" value="<?php echo $int_total_pagination_pages; ?>" class="pagination-button button"
              /><input
                type="hidden" name="int_pagination_start_row" value="<?php echo($int_total_pagination_pages*$int_pagination_rows_per_page)-($int_pagination_rows_per_page-1); ?>"
            /><input
              type="hidden" name="bit_paginate_view_more" value="<?php echo $bit_paginate_view_more; ?>"
              /><?php echo $str_pagination_hidden_field_text; ?>
            </form>
          <?php endif; ?>
        <?php endif; ?>
        <?php //---/DISPLAY LAST PAGINATION PAGE--- ?>
        <?php if($int_next_pagination_page_start_row <= $int_num_rows) : ?>
          <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" style="display:inline;margin:0;padding:0;"
            ><input 
              type="submit" name="1" value="Next" class="pagination-button button"
            /><input 
              type="hidden" name="int_pagination_start_row" value="<?php echo $int_pagination_start_row+$int_pagination_rows_per_page; ?>"
            /><input
              type="hidden" name="bit_paginate_view_more" value="<?php echo $bit_paginate_view_more; ?>"
            /><?php echo $str_pagination_hidden_field_text; ?>
          </form>
        <?php else : ?>
          <span class="deactivated-pagination-button">
            Next
          </span>
        <?php endif; ?>
        <?php //---=--- ?>
        <?php if($bit_paginate_view_more) : ?>
          <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" style="display:inline">
            <input 
              type="submit" name="str_pagination_button" id="str_pagination_button" value="View Less" class="pagination-button button"
            /><input
              type="hidden" name="bit_paginate_view_more" value="0"
            /><?php echo $str_pagination_hidden_field_text; ?>
          </form>
        <?php else : ?>
          <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" style="display:inline">
            <input 
              type="submit" name="str_pagination_button" id="str_pagination_button" value="View More" class="pagination-button button"
            /><input
              type="hidden" name="bit_paginate_view_more" value="1"
            /><?php echo $str_pagination_hidden_field_text; ?>
          </form>
        <?php endif; ?>
      </div>
    <?php
      endif;
      //---/DSISPLAY PAGINATION CONTROLS---
    ?>
  <?php else : ?>
    <?php echo $str_no_results_message; ?>
  <?php endif; ?>
  <?php echo $str_message; ?>
</div>
<?php mysql_close($link); ?>
<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/' . $str_document_library_directory_name . '/_includes/footer.php'); ?>