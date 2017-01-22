<?php 
  $header_menu_button = ''; 
  
  //add templates menu in header
  if(class_exists('comments_templates'))
  {
    $header_menu_button = comments_templates::render_modal_header_menu($current_entity_id);
  }
  
  echo ajax_modal_template_header($header_menu_button . TEXT_COMMENT_IFNO) 
?>

<?php echo form_tag('fields_form', url_for('items/comments','action=save' . (isset($_GET['id']) ? '&id=' . $_GET['id']:'') ),array('class'=>'form-horizontal')) ?>
<div class="modal-body">
  <div class="form-body">
  
<?php echo input_hidden_tag('path',$_GET['path']) ?>

<?php $obj = (isset($_GET['id']) ?  db_find('app_comments',$_GET['id']): db_show_columns('app_comments')) ?>
      
<?php
if(!isset($_GET['id']))
{
    
  $fields_access_schema = users::get_fields_access_schema($current_entity_id,$app_user['group_id']);
  
  $html = '';
  $fields_query = db_query("select f.* from app_fields f where f.type not in (" . fields_types::get_reserverd_types_list() . ',' . fields_types::get_users_types_list() . ") and  f.entities_id='" . db_input($current_entity_id) . "' and f.comments_status=1 order by f.comments_sort_order, f.name");
  while($v = db_fetch_array($fields_query))
  {       
    //check field access
    if(isset($fields_access_schema[$v['id']])) continue;
    
    //set off required option for comment form
    $v['is_required'] = 0;
    
     $html .='
          <div class="form-group">
          	<label class="col-md-3 control-label" for="fields_' . $v['id']  . '">' . fields_types::get_option($v['type'],'name',$v['name']) . '</label>
            <div class="col-md-9">	
          	  ' . fields_types::render($v['type'],$v,array('field_' . $v['id']=>''),array('parent_entity_item_id'=>$parent_entity_item_id,'form'=>'comment')) . '
              ' . tooltip_text($v['tooltip']) . '
            </div>			
          </div>        
        ';   
  }
  
  echo $html;
}  
?>    

    <div class="form-group">
    	<label class="col-md-3 control-label" for="name"><?php echo TEXT_COMMENT ?></label>
      <div class="col-md-9">	
    	  <?php echo textarea_tag('description',$obj['description'],array('class'=>'form-control ' . ($entity_cfg->get('use_editor_in_comments')==1 ? 'editor':''))) ?>        
      </div>			
    </div>
    
    <div class="form-group">
    	<label class="col-md-3 control-label" for="name"><?php echo TEXT_ATTACHMENTS ?></label>
      <div class="col-md-9">	
    	  <?php echo fields_types::render('fieldtype_attachments',array('id'=>'attachments'),array('field_attachments'=>$obj['attachments'])) ?>
        <?php echo input_hidden_tag('comments_attachments','',array('class'=>'form-control required_group')) ?>        
      </div>			
    </div>
    
<?php
  //render templates fields values
  if(class_exists('comments_templates'))
  {
    echo comments_templates::render_fields_values($current_entity_id);
  }
?>    
    
 </div>
</div>
 
<?php echo ajax_modal_template_footer() ?>    
                  
</form> 


<?php
  //include comments form validation 
  require(component_path('items/comments_form_validation.js')) 
?> 

   