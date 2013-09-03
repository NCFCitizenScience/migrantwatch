<?
function paginator($base_path, $cur_page, $total_items, $per_page, $footer_bar=false, $name) {
  $remainder = $total_items % $per_page;
  $total_pages = ($total_items - $remainder)/$per_page;
  if($remainder > 0) $total_pages += 1;

  $start_num = 1 + ($cur_page-1)*$per_page;
  $end_num = $start_num + $per_page - 1;
  if ($end_num > $total_items) $end_num = $total_items;

  $next_page = $cur_page + 1;
  $prev_page = $cur_page - 1;

  $start_page = $cur_page - 2;
  $end_page = $cur_page + 2;

  if($end_page > $total_pages) {
    $end_page = $total_pages;
    $start_page = $total_pages - 4;
  } 

  if($start_page < 1) {
    $start_page = 1;
    if ($total_pages >= 5) $end_page = 5;
    else $end_page = $total_pages;
  }
      
  $class = $footer_bar ? "footer_bar" : "summary_bar";

  $ret  = '<div class="bar clearfix ' . $class . '">';
  if(!$footer_bar) $ret .= '<div class="summary">Displaying ' . $name . ' ' . $start_num . ' - ' . $end_num . ' of ' . $total_items . '.</div>';

  if($total_pages > 1) {
   $ret .= '<ul class="pagerpro">';
    if($cur_page != 1) $ret .= '<li><a href="' . $base_path . '?page=' . $prev_page . '">Prev</a>';
    for($i=$start_page;$i<=$end_page;$i++) {
      $ret .= '<li';
      if($i == $cur_page) $ret .= ' class="current"';
      $ret .= '>';
      $ret .= '<a href="' . $base_path . '&page=' . $i . '">' . $i . '</a></li>';
    }
    if ($cur_page != $total_pages) $ret .= '<li><a href="' . $base_path . '&page=' . $next_page . '">Next</a></li>';
    $ret .= '</ul>';

  }

  $ret .= '</div>';

  return $ret;
}
/*
$base_path='http://wildindia.org/viewtags.php';
$cur_page=1;
$total_items=100;
$name='hello';

 echo paginator('pagination.php', $cur_page, $total_items, 20, false, 'pics'); 
*/

?>
<style>
.pagerpro { text-align:right }
.pagerpro li { list-style-type:none; display:inline; text-align:right }
.pagerpro li a{  list-style-type:none; display:inline; border: solid 1px;  margin-right: 5px;
        margin-bottom: 5px; padding: 0.3em 0.5em;

text-decoration: none;
        border: solid 1px #d95c15;
        color: #000;

}

.pagerpro a{ width:70px; }
.pagerpro li a:hover  {
    background:#d95c15;
    color:#fff;
    a { color : #fff; }
       
      
}

.pagerpro .current a  {
    background:#d95c15;
    color:#fff;
}


</style>