<?php

if(!users::has_access('update'))
{        
  redirect_to('dashboard/access_forbidden');
}

switch($app_module_action)
{  
  case 'remove_related_items';
      if(isset($_POST['items']))
      {
        foreach($_POST['items'] as $id)
        {
          db_delete_row('app_related_items', $id);
        }
      }
      
      redirect_to('items/info','path=' . $_GET['path']);
    break;
  case 'remove_related_item':
  
      db_delete_row('app_related_items', $_GET['id']);            
        
      redirect_to('items/info','path=' . $_GET['path']);
    
    exit();
  break;
    
  case 'add_related_item':
                
      if(isset($_POST['items']) and isset($_POST['related_entities_id']))
      {
        $related_entities_id = $_POST['related_entities_id'];
                
        foreach($_POST['items'] as $related_items_id)
        {
          $check_query = db_query("select * from app_related_items where (entities_id ='" . $current_entity_id . "' and items_id ='" . $current_item_id . "' and related_entities_id = '" .$related_entities_id ."' and related_items_id = '" . $related_items_id . "') or (entities_id ='" . $related_entities_id . "' and items_id ='" . $related_items_id . "' and  related_entities_id = '" .$current_entity_id ."' and related_items_id = '" . $current_item_id . "')");
          if(!$check = db_fetch_array($check_query))
          {            
            $sql_data = array('entities_id'=> $current_entity_id,
                              'items_id'=>$current_item_id,
                              'related_entities_id'=> $related_entities_id,
                              'related_items_id'=> $related_items_id);
                              
            db_perform('app_related_items',$sql_data);
                        
          }
        }
      }
      
      redirect_to('items/info','path=' . $_GET['path']);
      
    break; 
}