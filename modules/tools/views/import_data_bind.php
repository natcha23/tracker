
<?php echo ajax_modal_template_header(TEXT_HEADING_BIND_FIELD) ?>

<?php echo form_tag('bind_field_form', url_for('tools/import_data','action=bind_filed'),array('onSubmit'=>'return bind_field(' . $_GET['col'] . ')')) . input_hidden_tag('col',$_GET['col']); ?>

<div class="modal-body">
  
<?php
  $fields_query = db_query("select f.*, t.name as tab_name from app_fields f, app_forms_tabs t where f.type not in (" . fields_types::get_reserverd_types_list() . ',' . fields_types::get_users_types_list(). ",'fieldtype_entity','fieldtype_users','fieldtype_grouped_users','fieldtype_input_numeric_comments','fieldtype_input_file','fieldtype_attachments','fieldtype_related_records') and f.entities_id='" . $_GET['entities_id'] . "' and f.forms_tabs_id=t.id order by t.sort_order, t.name, f.sort_order, f.name");

  echo '<div><label>' . input_radiobox_tag('filed_id',0) . ' ' . TEXT_NONE . '</label></div>';
  
  while($v = db_fetch_array($fields_query))
  {
    if(in_array($v['id'],$import_fields)) continue;
    
    echo '<div><label>' . input_radiobox_tag('filed_id',$v['id']) . ' ' . $v['name'] . '</label></div>';
  }
?>  
        
</div> 
             
<div class="modal-footer">
  <button type="submit" class="btn btn-primary"><?php echo TEXT_BUTTON_BIND ?></button>
  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo TEXT_BUTTON_CLOSE ?></button>    
</div>
    
<script>
  jQuery(document).ready(function() {                  
     appHandleUniform()                     
  });
</script>

</form> 

   
    
 
