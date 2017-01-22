<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title><?php echo $app_title ?></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="www.rukovoditel.net" name="author"/>
<meta name="MobileOptimized" content="320">
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="template/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="template/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="template/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="template/plugins/select2/select2_conquer.css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="template/css/style-conquer.css" rel="stylesheet" type="text/css"/>
<link href="template/css/style.css" rel="stylesheet" type="text/css"/>
<link href="template/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="template/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="css/skins/<?php echo $app_skin ?>" rel="stylesheet" type="text/css" />

<script src="template/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>

<script type="text/javascript" src="js/validation/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/validation/additional-methods.min.js"></script>
<?php require('js/validation/validator_messages.php'); ?> 

<script type="text/javascript" src="js/main.js"></script>

<link rel="stylesheet" type="text/css" href="css/default.css"/>

<?php if(is_file(DIR_FS_UPLOADS  . '/' . CFG_APP_LOGIN_PAGE_BACKGROUND)): ?>
<style>
.login {
  background: url(uploads/<?php echo CFG_APP_LOGIN_PAGE_BACKGROUND ?>) no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}    

.login-fade-in{
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  background-color: #333;
  opacity: 0.2;
  z-index: -1; 
}

.copyright{
  color: white !important;
}

</style>
<?php endif ?>

<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<!-- BEGIN BODY -->
<body class="login">

<div class="login-fade-in"></div>

<!-- BEGIN LOGO -->
<div class="login-page-logo">

<?php
  if(is_file(DIR_FS_UPLOADS  . '/' . CFG_APP_LOGO))
  {
    if(is_image(DIR_FS_UPLOADS  . '/' . CFG_APP_LOGO))
    {
      $html = '<img src="uploads/' . CFG_APP_LOGO .  '" border="0" title="' . CFG_APP_NAME . '">';
      
      if(strlen(CFG_APP_LOGO_URL)>0)
      {
        $html = '<a href="' . CFG_APP_LOGO_URL . '" target="_new">' . $html . '</a>';
      }
      
      echo $html;
    }
  }
  else
  {
    echo CFG_APP_NAME;
  }
?>
	
</div>
<!-- END LOGO -->
<!-- BEGIN LOGIN -->
<div class="content">

<?php 
  //output alerts if they exists.
  echo $alerts->output(); 
        
//include module views    
  if(is_file($path = 'modules/' . $app_module . '/views/' . $app_action . '.php'))
  {    
    require($path);
  }   
?>

</div>
<!-- END LOGIN -->


<!-- BEGIN COPYRIGHT -->
<div class="copyright">
	 <?php echo (strlen(CFG_APP_COPYRIGHT_NAME)>0 ? '&copy; ' . CFG_APP_COPYRIGHT_NAME . ' ' . date('Y') . '<br>': '') ?>
    <small><?php echo TEXT_POWERED_BY ?>&nbsp;<a target="_blank" href="http://rukovoditel.net" title="<?php echo TEXT_POWERED_BY_TITLE ?>">Rukovoditel</a></small>
</div>
<!-- END COPYRIGHT -->

<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="template/plugins/respond.min.js"></script>
<script src="template/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="template/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="template/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="template/plugins/bootstrap-hover-dropdown/twitter-bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="template/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="template/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="template/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="template/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="template/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="template/scripts/app.js" type="text/javascript"></script>
<!-- END PAGE LEVEL SCRIPTS -->

<script>
jQuery(document).ready(function() {     
  App.init();
});
</script>
<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>