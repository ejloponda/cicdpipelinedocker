<?php 

// UPDATED : JANUARY 29,2011 | MARVIN
//instant pagination
    /*function employee() {
        
        $sql = "SELECT u.id, b.name as branch,d.department_name, CONCAT(u.firstname,' ' ,u.lastname) as Name, p.position, u.status ";   
        $sql .= "FROM s_user u, s_branch b, s_department_head d, s_position p, s_department_user ds ";
        $sql .= " WHERE u.user_status=2 AND (b.id=u.branch_id  AND u.position_id=p.id) AND ds.user_id=u.id AND ds.department_head_id=d.id";
        
        $config['width'] = 850;
        $field_title[] = array('field'=>'firstname','new_field'=>'First Name');
        $action_button[] = array('label'=>'View Details','action'=>'hr/view_employee_details');
        $this->var['view'] = Paginator::loadRecord($sql,$config,$field_title,$action_button);
    
        $this->var['page_title'] = 'Master List';
        $this->view->render('hr/employee/index.php', $this->var);   
        //$this->view->render('hr/master_list.php', $this->var);    
    }*/
    
// manual but easy
    /*$records = Employee::findBySql("SELECT * FROM s_user WHERE user_status=".EMPLOYEE."");
    
    $pages = new Paginator;

    $pages->items_total = count($records);
    $pages->mid_range = 9; // Number of pages to display. Must be odd and > 3
    $pages->paginate();
    
    $this->var['employee'] = $records = Employee::findAll($pages->limit);

    $upper_pages .= $pages->display_pages() . "<span class=\"\">".$pages->display_jump_menu().$pages->display_items_per_page()."</span>";
    $lower_pages .= $pages->display_pages();
    $lower_pages .= "<p class=\"paginate\">Page: $pages->current_page of $pages->num_pages</p>\n";
    $lower_pages .= "<p class=\"paginate\">SELECT * FROM table $pages->limit (retrieve records $pages->low-$pages->high from table - $pages->items_total item total / $pages->items_per_page items per page)";
    
    $this->var['upper_pages'] = $upper_pages;  
    $this->var['lower_pages'] = $lower_pages;*/
    
    //HOW TO CREATE AJAX Pagination
    //1. CREATE a element that will handle the records
    //Example: <div id="photo_list"></div>
    //2. Insert below on your page
    
    
    /*<script>
loadPhotos(<?php echo $album_id; ?>,1,1);

function loadPhotos() {
    $.post(base_folder + 'album/_show_photos?album_id=<?php echo $album_id; ?>',{},
        function(data) {
                $("#photo_list").html(data);
    });
}

 function gotoPage(page,div_id)
 {
     
    $.post(base_folder + 'album/_show_photos?album_id=<?php echo $album_id; ?>&page='+page,{},
        function(data) {
                $("#"+div_id).html(data);
    });
}
 </script>*/
            //3. Add this line into album/_show_photos
            
        /*$query = "SELECT COUNT(*) FROM wg_album_photos WHERE album_id='".$album_id."'";
        $result = mysql_query($query) or die(mysql_error());
        $num_rows = mysql_fetch_row($result);
        
        $pages = new Paginator;
        $pages->form_id='photo_list';
        $pages->items_per_page = 10;
        $pages->items_total = $num_rows[0];
        $pages->mid_range = 9; // Number of pages to display. Must be odd and > 3
        $pages->paginate();
        
        $this->var['display_pages'] =  $pages->display_ajax_pages(); <-- THIS IS THE PAGINATION

        $query = "SELECT * FROM wg_album_photos WHERE album_id=".$album_id." ORDER BY sort ASC ". $pages->limit;
        $this->var['photos']  =  $photos = Model::runSql($query,true); <-- THIS IS THE RECORD
        $this->view->noTemplate();
        $this->view->render('album/front/photos.php',$this->var);
        */
?>

<style type="text/css">
.paginate {
    font-family: Arial, Helvetica, sans-serif;
    font-size: .8em;
}

a.paginate {
    border: 1px solid #E8E8E8;
    padding: 2px 6px 2px 6px;
    text-decoration: none;
    color: #000080;
}


a.paginate:hover {
    background-color: #5588bb;
    color: #FFF;
    text-decoration: underline;
}

a.current {
    border: 1px solid #000080;
    font: bold .7em Arial,Helvetica,sans-serif;
    padding: 2px 6px 2px 6px;
    cursor: default;
    background:#000080;
    color: #FFF;
    text-decoration: none;
}

span.inactive {
    border: 1px solid #999;
    font-family: Arial, Helvetica, sans-serif;
    font-size: .7em;
    padding: 2px 6px 2px 6px;
    color: #999;
    cursor: default;
}


</style>
<?php

class Paginator{
    var $items_per_page;
    var $items_total;
    var $current_page;
    var $num_pages;
    var $mid_range;
    var $low;
    var $high;
    var $limit;
    var $return;
    var $default_ipp = 10;
    var $querystring;
    var $form_id; // for ajax div
    public $field_title;


    function Paginator()
    {
    
        $this->current_page = 1;
        $this->mid_range = 7;
        $this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp']:$this->default_ipp;
    }

    function paginate()
    {
        
        if($_GET['ipp'] == 'All')
        {
            $this->num_pages = ceil($this->items_total/$this->default_ipp);
            $this->items_per_page = $this->default_ipp;
        }
        else
        {
            if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
            $this->num_pages = ceil($this->items_total/$this->items_per_page);
        }
        $page = (int) $_GET['page']; // must be numeric > 0
        if($page==0) {
            if($_SESSION['current_page']>1)
            {
                $this->current_page = $_SESSION['current_page'];    
            }else {
                $this->current_page= 1; 
            }
                
        }else {
            $_SESSION['current_page'] = $page;
            $this->current_page = $_SESSION['current_page'];
        }
        
        if($this->current_page < 1 Or !is_numeric($this->current_page)) $this->current_page = 1;
        if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
        $prev_page = $this->current_page-1;
        $next_page = $this->current_page+1;

        if($_GET)
        {
            $args = explode("&",$_SERVER['QUERY_STRING']);
            foreach($args as $arg)
            {
                $keyval = explode("=",$arg);
                if($keyval[0] != "page" And $keyval[0] != "ipp") $this->querystring .= "&" . $arg;
            }
        }

        if($_POST)
        {
            foreach($_POST as $key=>$val)
            {
                if($key != "page" And $key != "ipp") $this->querystring .= "&$key=$val";
            }
        }

        if($this->num_pages > 10)
        {
            $this->return = ($this->current_page != 1 And $this->items_total >= 10) ? "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$prev_page&ipp=$this->items_per_page$this->querystring\">&laquo; Previous</a> ":"<span class=\"inactive\" href=\"#\">&laquo; Previous</span> ";

            $this->start_range = $this->current_page - floor($this->mid_range/2);
            $this->end_range = $this->current_page + floor($this->mid_range/2);

            if($this->start_range <= 0)
            {
                $this->end_range += abs($this->start_range)+1;
                $this->start_range = 1;
            }
            if($this->end_range > $this->num_pages)
            {
                $this->start_range -= $this->end_range-$this->num_pages;
                $this->end_range = $this->num_pages;
            }
            $this->range = range($this->start_range,$this->end_range);

            for($i=1;$i<=$this->num_pages;$i++)
            {
                if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= " ... ";
                // loop through all pages. if first, last, or in range, display
                if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
                {
                    $this->return .= ($i == $this->current_page And $_GET['page'] != 'All') ? "<a title=\"Go to page $i of $this->num_pages\" class=\"current\" href=\"#\">$i</a> ":"<a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page$this->querystring\">$i</a> ";
                }
                if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= " ... ";
            }
            $this->return .= (($this->current_page != $this->num_pages And $this->items_total >= 10) And ($_GET['page'] != 'All')) ? "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$next_page&ipp=$this->items_per_page$this->querystring\">Next &raquo;</a>\n":"<span class=\"inactive\" href=\"#\">&raquo; Next</span>\n";
            $this->return .= ($_GET['page'] == 'All') ? "<a class=\"current\" style=\"margin-left:10px\" href=\"#\">All</a> \n":"<a class=\"paginate\" style=\"margin-left:10px\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">All</a> \n";
        }
        else
        {
            for($i=1;$i<=$this->num_pages;$i++)
            {
                $this->return .= ($i == $this->current_page) ? "<a class=\"current\" href=\"#\">$i</a> ":"<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$i&ipp=$this->items_per_page$this->querystring\">$i</a> ";
            }
            $this->return .= "<a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">All</a> \n";
        }
        $this->low = ($this->current_page-1) * $this->items_per_page;
        $this->high = ($_GET['ipp'] == 'All') ? $this->items_total:($this->current_page * $this->items_per_page)-1;
        $this->limit = ($_GET['ipp'] == 'All') ? "":" LIMIT $this->low,$this->items_per_page";
    }

    function display_items_per_page()
    {
        $items = '';
        $ipp_array = array(5,10,25,50,100,'All');
        foreach($ipp_array as $ipp_opt) $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";
        return "<span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select>\n";
    }
    
    function display_items_per_page_ajax() {
        $items = '';
        $ipp_array = array(5,10,25,50,100,'All');
        foreach($ipp_array as $ipp_opt) $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";
        return "<span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=javascript:\">$items</select>\n";
    }
    
    //not yet done
    function display_jump_menu_ajax($id)
    {
        for($i=1;$i<=$this->num_pages;$i++)
        {
            $option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";
        }
        return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onClick=javascript:gotoPage(this[this.selectedIndex].value,\"'.$this->form_id.'\");>$option</select>\n";
    }

    function display_jump_menu()
    {
        for($i=1;$i<=$this->num_pages;$i++)
        {
            $option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option>\n":"<option value=\"$i\">$i</option>\n";
        }
        return "<span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page$this->querystring';return false\">$option</select>\n";
    }
    
    function display_ajax_pages()
    {
        $total = $this->items_total; // total _records;
    
        $this->max_records=$this->items_per_page;
        $this->page= $this->current_page;

    
        
        if($total>$this->max_records){
        // Build the recordset paging links
        $num_pages = ceil($total / $this->max_records);
        
        $last_page = $num_pages;
        $nav = '';
        
        if($this->page!=1) {
            $nav .= '<a class=paginate href=javascript:gotoPage(1,"'.$this->form_id.'");><span>First</span></a> ';
        }
        
        // Can we have a link to the previous page?
        if($this->page > 1){
            $prev = $this->page-1;
            $nav .= '<a class=paginate href=javascript:gotoPage('.$prev.',"'.$this->form_id.'");><span>Prev</span></a> ';
        }
        if($num_pages>10) {
            
            if($this->page<=3) {
                $x=1;
            }else {
                $x= $this->page-3;  
                
                if($this->page>4) {
                    $nav .= ' ... ';
                }   
            }
            
            $temp = $last_page - 3;
            if($this->page>=$temp ) {
                $nav_last = '';
                $num_pages = $last_page;
            }else {
                $nav_last = ' ... ';
                $num_pages = $this->page+3; 
            }
            

        }else {
            $x=1;   
        }

            for($i = $x; $i < $num_pages+1; $i++)
            {
                if($this->page == $i)
                {
                  // Bold the page and dont make it a link
                  $nav .= ' <a class=current>'.$i.'</a>';
                }
                else
                {
                  // Link the page
                 // $nav .= ' <a class=paginate href="'.$_SERVER['PHP_SELF'].'?page='.$i.'">'.$i.'</a>';
                  
                  $nav .= ' <a class=paginate  href=javascript:gotoPage('.$i.',"'.$this->form_id.'");>'.$i.'</a>';
                 
                
                }
            }
          $nav .= $nav_last;
        // Can we have a link to the next page?
        if($this->page < $num_pages){
        //$nav .= ' <a class=paginate href="'.$_SERVER['PHP_SELF'].'?page=' . ($this->page+1) . '">Next</a>';
            $next_page = $this->page+1;
            $nav .= ' <a class=paginate href=javascript:gotoPage('.$next_page.',"'.$this->form_id.'");>Next</a>';
        }
        
        if($this->page!=$last_page) {
            $nav .= ' <a class=paginate  href=javascript:gotoPage('.$last_page.',"'.$this->form_id.'");>Last</a>';
        }
        
        // Strip the trailing pipe if there is one
        $nav = ereg_replace('@|$@', "", $nav);
        //echo $nav;
        return $nav;
        }

    }

    function display_pages()
    {
        return $this->return;
    }
    
    function build_friendly_names($field) {
    
    
        if(substr($field, -3) == '_id'){
            
            $new_name = self::checkDefineFieldTitle($field);
            
            
        }else {
            $new_name = self::checkDefineFieldTitle($field);
        }
        
        
        return $new_name;
    }
    
    function checkDefineFieldTitle($field) {
        
        if(substr($field, -3) == '_id'){
            $new_title = substr($field, 0, -3);
            $new_title = ucwords(str_replace('_', ' ', $field));
        }else {
            $new_title = ucwords(str_replace('_', ' ', $field)); 
        }
        
        foreach($this->field_title as $key=>$val)
        {
            $a=0;
            foreach($val as $k => $v)
            {
                if($v==$field) {
                    $new_title = $val['new_field'];
                }
                $a++;
            }
        }
        return $new_title;
    }
    
    /**
    * This method builds the search bar display
    *
    */
    function build_search_bar($fields = array()) {
        if(count($fields)==0)
        {
            // build the fields menu
            $fielddropdown = '<select name="field">';
            $fieldselect = mysql_query('SHOW FIELDS FROM '.$this->table);
            while($fields = mysql_fetch_assoc($fieldselect)){
                $fielddropdown .= '<option value="'.$fields['Field'].'">'.$this->build_friendly_names($fields['Field']).'</option>';
            }
            $fielddropdown .= '</select>';
            $searchterm = (!empty($_POST['searchterm'])) ? $_POST['searchterm'] : '' ;
            $search = '';
            $search .=  "<form name=\"searchbar\" id=\"searchbar\" action=\"$_SERVER[PHP_SELF]?page=$this->current_page&ipp=$this->items_per_page$this->querystring';return false\" method=\"get\" style=\"display:inline;\">"
            //$search .= ''
                    . $fielddropdown
                    . '<select name="compare">'
                    . '<option value="=">Is Equal To</option>'
                    . '<option value="LIKE">Contains</option>'
                    . '<option value="<">Is Less Than</option>'
                    . '<option value=">">Is Greater Than</option>'
                    . '</select>'
                    . '<input type="text" name="searchterm" value ="'.$searchterm.'">'
                                                                              
                    . "<input type=\"submit\" name=\"Search\" value=\"Search\" >"
                    . '</form><br>';
                    
            $return = $search;  
        }else
        {
            $fielddropdown = '<select name="field">';
            foreach($fields as $value)
            {               
                $fielddropdown .= '<option value="'.$value.'">'.$this->build_friendly_names($value).'</option>';
            }
            $fielddropdown .= '</select>';
            //$search .=  '<form name="searchbar" id="searchbar" action="'.$_SERVER['PHP_SELF'].'" method="post" style="display:inline;"><input type="hidden" name="search" value="search">'
            $search .=  ''
                    . $fielddropdown
                    . '<input type="hidden" name="compare" value="LIKE"> &nbsp;'
                    . '<input type="text" name="searchterm" value ="'.$searchterm.'">'
                    . '<input type="button" name="Search" value="Search" onclick=\"window.location=\'.$_SERVER[PHP_SELF]?page=\'+this[this.selectedIndex].value+&ipp=\'$this->items_per_page$this->querystring\';return false\"  />';       
                    //. '</form><br>';
                    
            $return = $search;  
            
        }
        return $return; 
    }
    
    
    public static function loadRecord($sql='',$config = array(),$field_title=array(),$action_button=array()) {
        
        $raw = mysql_query($sql)or die(mysql_error());
        
        $pages = new Paginator;
        $pages->items_total = mysql_num_rows($raw);
        $pages->mid_range = 1; // Number of pages to display. Must be odd and > 3
        $pages->field_title = $field_title;
        $pages->paginate();
        
        
        //$view .= $pages->build_search_bar();
        $view .= $pages->display_pages();
        
        $view .= "<span class=\"\">".$pages->display_jump_menu().$pages->display_items_per_page()."</span>";
        $view .= "<br><br>";
        $result = mysql_query($sql . $pages->limit) or die(mysql_error());
        
        (!empty($config['width'])) ? $width = ' width="'.$config['width'].'"' : $width = NULL;
        
        $view .= "<table ".$width." border=0 cellpadding=6 cellspacing=1 bgcolor=#CCCCCC>";
        $view .= "<tr>";
        //TITLE
        $select = mysql_query($sql) or die(mysql_error());
        $i=0;
        while($i < mysql_num_fields($select)){
            $column = mysql_fetch_field($select, $i);
                //if($column->name != 'id' ){
                $view .= '<th align="center" bgcolor="#5588BB" style="color:white">'.$pages->build_friendly_names($column->name).'</th>';   
            //}
            $i++;
        }
        $view .= '<th colspan=2 bgcolor="#5588BB" style="color:white" >Action</th>';
        $view .="</tr>";
        
        
        //RECORDS
        $count = 0;
        //$fields = mysql_query($sql) or die(mysql_error());
        while($arr = mysql_fetch_assoc($result)){
            
            //$page .= (!($count % 2) == 0) ? '<tr class="even" style="background:'.$this->row_even.';">' : '<tr class="odd" style="background:'.$this->row_odd.';">';
            //$view .= (!($count % 2) == 0) ? '<tr onmouseover=\"hilite(this)\" onmouseout=\"lowlite(this)\"  bgcolor="white" >' : '<tr onmouseover=\"hilite(this)\" onmouseout=\"lowlite(this)\"   bgcolor="#F7F7F7">';
            $view .= "<tr onmouseover=\"hilite(this)\" onmouseout=\"lowlite(this)\" bgcolor=#F7F7F7>";
            foreach($arr as $col => $val){
                $view .= '<td >';
                $view .= $val;
                $view .= '</td >';
            }
            
            $count ++;
            
                if(count($action_button)) {
                    $view .= "<td><div align=center>";
                    foreach($action_button as $key=>$value) {
                        $view .= "<a href=". url($value['action']."/".$arr['id']) ." ><span>".$value['label']."</span></a> &nbsp;&nbsp;&nbsp;";
                    }
                    $view .= "</div></td>";
                }
            $view .="</tr>";
            
            }
        
        $view .= "</table>";
        $view .= "<br>";
        $view .= $pages->display_pages();
        
        $view .="<p class=\"paginate\">Page: $pages->current_page of $pages->num_pages</p>\n";
        //$view .="<p class=\"paginate\">SELECT * FROM table $pages->limit (retrieve records $pages->low-$pages->high from table - $pages->items_total item total / $pages->items_per_page items per page)";    
        
        return $view;       
    }
} ?>

<script>
function hilite(elem)
{
    elem.style.background = '#FFC';
}

function lowlite(elem)
{
    elem.style.background = '';
}
</script>
<?php
//if you want to make the table manually
/*$query = "SELECT COUNT(*) FROM City";
$result = mysql_query($query) or die(mysql_error());
$num_rows = mysql_fetch_row($result);

$pages = new Paginator;
$pages->items_total = $num_rows[0];
$pages->mid_range = 9; // Number of pages to display. Must be odd and > 3
$pages->paginate();

echo $pages->display_pages();
echo "<span class=\"\">".$pages->display_jump_menu().$pages->display_items_per_page()."</span>";

$query = "SELECT City.Name,City.Population,Country.Name,Country.Continent,Country.Region FROM City,Country WHERE City.CountryCode = Country.Code ORDER BY City.Name ASC $pages->limit";
$result = mysql_query($query) or die(mysql_error());

echo "<table>";
echo "<tr><th>City</th><th>Population</th><th>Country</th><th>Continent</th><th>Region</th></tr>";
while($row = mysql_fetch_row($result))
{
    echo "<tr onmouseover=\"hilite(this)\" onmouseout=\"lowlite(this)\"><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td></tr>\n";
}
echo "</table>";

echo $pages->display_pages();
echo "<p class=\"paginate\">Page: $pages->current_page of $pages->num_pages</p>\n";
echo "<p class=\"paginate\">SELECT * FROM table $pages->limit (retrieve records $pages->low-$pages->high from table - $pages->items_total item total / $pages->items_per_page items per page)";
*/

?>
