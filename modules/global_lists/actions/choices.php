<?php

$list_info_query = db_query("select * from app_global_lists where id='" . $_GET['lists_id']. "'");
if(!$list_info = db_fetch_array($list_info_query))
{
  redirect_to('global_lists/lists');
}

switch($app_module_action)
{
  case 'save':
      $sql_data = array('lists_id'=>$_GET['lists_id'],
                        'parent_id'=>(strlen($_POST['parent_id'])==0 ? 0 : $_POST['parent_id']),
                        'name'=>$_POST['name'],                                                
                        'is_default'=>(isset($_POST['is_default']) ? $_POST['is_default']:0),
                        'bg_color'=>$_POST['bg_color'],                        
                        'sort_order'=>$_POST['sort_order'],
                        );
                                                                              
      if(isset($_POST['is_default']))
      {
        db_query("update app_global_lists_choices set is_default = 0 where lists_id = '" . db_input($_GET['lists_id']). "'");
      }                        
      
      if(isset($_GET['id']))
      {        
        db_perform('app_global_lists_choices',$sql_data,'update',"id='" . db_input($_GET['id']) . "'");       
      }
      else
      {               
        db_perform('app_global_lists_choices',$sql_data);
      }
      
      redirect_to('global_lists/choices', 'lists_id=' . $_GET['lists_id']);      
    break;
  case 'delete':
      if(isset($_GET['id']))
      {      
        $msg = global_lists::check_before_delete_choices($_GET['id']);
        
        if(strlen($msg)>0)
        {
          $alerts->add($msg,'error');
        }
        else
        {
          $name = global_lists::get_choices_name_by_id($_GET['id']);
          
          $tree = global_lists::get_choices_tree($_GET['lists_id'],$_GET['id']);
          
          foreach($tree as $v)
          {
            db_delete_row('app_global_lists_choices',$v['id']);
          }
          
          db_delete_row('app_global_lists_choices',$_GET['id']);
          
          $alerts->add(sprintf(TEXT_WARN_DELETE_SUCCESS,$name),'success');
        }
        
        redirect_to('global_lists/choices', 'lists_id=' . $_GET['lists_id']);  
      }
    break;  
  case 'sort':
      $choices_sorted = $_POST['choices_sorted'];
      
      if(strlen($choices_sorted)>0)
      {
        $choices_sorted = json_decode($choices_sorted,true);
        
        //echo '<pre>';
        //print_r($choices_sorted);
        
        global_lists::choices_sort_tree($_GET['lists_id'],$choices_sorted);
      }
            
      redirect_to('global_lists/choices', 'lists_id=' . $_GET['lists_id']);
    break;  
}

