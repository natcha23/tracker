<?php echo ajax_modal_template_header(TEXT_CONFIGURE_DASHBOARD) ?>

<?php
$common_reports_query = db_query("select count(*) as total from app_reports r, app_entities e, app_entities_access ea  where r.entities_id = e.id and e.id=ea.entities_id and length(ea.access_schema)>0 and ea.access_groups_id='" . db_input($app_user['group_id']) . "' and find_in_set(" . $app_user['group_id'] . ",r.users_groups) and r.reports_type = 'common' order by r.dashboard_sort_order, r.name");
$common_reports = db_fetch_array($common_reports_query);
$common_reports_count = $common_reports['total'];
?>


<?php echo form_tag('dashboard', url_for('dashboard/','action=save')) ?>

<div class="modal-body ajax-modal-width-790">

<ul class="nav nav-tabs" id="form_tabs">
  <li class="active" ><a data-toggle="tab" href="#form_tab_standard_reports"><?php echo TEXT_STANDARD_REPORTS ?></a></li>
  
<?php if($common_reports_count>0): ?>  
  <li><a data-toggle="tab" href="#form_tab_common_reports"><?php echo TEXT_EXT_COMMON_REPORTS ?></a></li>
<?php endif ?>
  
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="form_tab_standard_reports">
  
<div><?php echo TEXT_CONFIGURE_DASHBOARD_INFO ?></div><br>

<table style="width: 100%; max-width: 960px;">
  <tr>
    <td valign="top" width="50%">
      <fieldset>
        <legend><?php echo TEXT_REPORTS_ON_DASHBOARD ?></legend>
<div class="cfg_listing">        
  <ul id="reports_on_dashboard" class="sortable">
  <?php
  $reports_query = db_query("select r.*,e.name as entities_name from app_reports r, app_entities e where e.id=r.entities_id and r.created_by='" . db_input($app_logged_users_id) . "' and r.reports_type='standard' and r.in_dashboard=1 order by r.dashboard_sort_order, r.name");
  while($v = db_fetch_array($reports_query))
  {
    echo '<li id="report_' . $v['id'] . '"><div>' . $v['name'] . '</div></li>';
  }
  ?> 
  </ul>         
</div>
              
      </fieldset>
    
    </td>
    <td style="padding-left: 25px;" valign="top">
    
      <fieldset>
        <legend><?php echo TEXT_MY_REPORTS ?></legend>
<div class="cfg_listing">        
<ul id="reports_excluded_from_dashboard" class="sortable">
<?php
  $reports_query = db_query("select r.*,e.name as entities_name from app_reports r, app_entities e where e.id=r.entities_id and r.created_by='" . db_input($app_logged_users_id) . "' and r.reports_type='standard' and r.in_dashboard!=1 order by e.name, r.name");
  while($v = db_fetch_array($reports_query))
  {
    echo '<li id="report_' . $v['id'] . '"><div>' . $v['name'] . '</div></li>';
  }
?> 
</ul>
</div>                     
      </fieldset>
      
      
    </td>
  </tr>
</table>

  </div>
  
<?php if($common_reports_count>0): ?>  
  <div class="tab-pane" id="form_tab_common_reports">
    <p><?php echo TEXT_EXT_COMMON_REPORTS_DASHBOARD_DESCRIPTION ?></p>
<?php
  $common_reports_list = array();
  $reports_query = db_query("select r.* from app_reports r, app_entities e, app_entities_access ea  where r.entities_id = e.id and e.id=ea.entities_id and length(ea.access_schema)>0 and ea.access_groups_id='" . db_input($app_user['group_id']) . "' and find_in_set(" . $app_user['group_id'] . ",r.users_groups) and r.reports_type = 'common' order by r.dashboard_sort_order, r.name");
  while($reports = db_fetch_array($reports_query))
  {
    $common_reports_list[$reports['id']] = $reports['name']; 
  }
  
  echo select_checkboxes_tag('hidden_common_reports',$common_reports_list,$app_users_cfg->get('hidden_common_reports'));
?>  
  
  </div>
<?php endif ?>  

  </div>
</div>

<?php echo ajax_modal_template_footer() ?>

</form> 

<script>
  $(function() {         
    	$( "ul.sortable" ).sortable({
    		connectWith: "ul",
    		update: function(event,ui){  
          data = '';  
          $( "ul.sortable" ).each(function() {data = data +'&'+$(this).attr('id')+'='+$(this).sortable("toArray") });                            
          data = data.slice(1)                      
          $.ajax({type: "POST",url: '<?php echo url_for("dashboard/","action=sort_reports")?>',data: data});
        }
    	});
      
  });  
</script>