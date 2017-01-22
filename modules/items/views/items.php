
<?php require(component_path('items/navigation')) ?>

<?php 
  app_reset_selected_items();
   
  $listing_container = 'entity_items_listing' . $reports_info['id'] . '_' . $reports_info['entities_id'];
  
  echo input_hidden_tag('entity_items_listing_path',$_GET['path']); 
?>

<?php 
  $filters_preivew = new filters_preivew($reports_info['id'],false);
  $filters_preivew->path = $current_path;
  $filters_preivew->redirect_to = 'listing';
  
  echo $filters_preivew->render();  
?>

<div class="row">
  <div class="col-md-5">
    <div class="entitly-listing-buttons-left">
      <?php if(users::has_access('create')) echo button_tag((strlen($entity_cfg->get('insert_button'))>0 ? $entity_cfg->get('insert_button') : TEXT_ADD), url_for('items/form','path=' . $_GET['path'])) . ' '; ?>
      
      
      <div class="btn-group">
				<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" data-hover="dropdown">
				<?php echo TEXT_WITH_SELECTED ?> <i class="fa fa-angle-down"></i>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li>
						<?php echo link_to_modalbox(TEXT_EXPORT,url_for('items/export','path=' . $_GET['path'] . '&reports_id=' . $reports_info['id'] )) ?>
					</li>          
<?php 
  echo plugins::render_simple_menu_items('with_selected','&reports_id=' . $reports_info['id']); 
?>          
				</ul>
			</div>            
    </div>
  </div>
  <div class="col-md-7">
    <div class="entitly-listing-buttons-right1">    
      <?php echo render_listing_search_form($entity_info['id'],$listing_container) ?>        
    </div>
                    
  </div>
</div> 

<div class="row">
  <div class="col-xs-12">
    <div id="<?php echo $listing_container ?>" class="entity_items_listing entity_items_listing_loading"></div>
  </div>
</div>

<?php echo input_hidden_tag($listing_container . '_order_fields',$reports_info['listing_order_fields']) ?>

<?php require(component_path('items/load_items_listing.js')); ?>

<script>
 
  $(function() {     
    load_items_listing('<?php echo $listing_container ?>',1);                                                                         
  });    
  
    
</script> 