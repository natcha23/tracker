<h3 class="page-title"><?php echo TEXT_DATABASE_EXPORT_APPLICATION ?></h3>
<p><?php echo TEXT_DATABASE_EXPORT_EXPLANATION ?></p>

<p><?php echo button_tag(TEXT_BUTTON_EXPORT_DATABASE,url_for('tools/db_backup','action=export_template'),false) ?></p>
<?php echo tooltip_text(TEXT_DATABASE_EXPORT_TOOLTIP) ?>