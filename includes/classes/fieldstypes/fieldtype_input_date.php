<?php

class fieldtype_input_date
{
  public $options;
  
  function __construct()
  {
    $this->options = array('title' => TEXT_FIELDTYPE_INPUT_DATE_TITLE);
  }
  
  function get_configuration()
  {
    $cfg = array();
    
    $cfg[] = array('title'=>TEXT_DEFAULT_DATE, 
                   'name'=>'default_value',
                   'type'=>'input',                   
                   'tooltip'=>TEXT_DEFAULT_DATE_INFO,
                   'params'=>array('class'=>'form-control input-small'));
    
    $cfg[] = array('title'=>TEXT_DATE_BACKGROUND, 
                   'name'=>'background',
                   'type'=>'colorpicker',                   
                   'tooltip'=>TEXT_DATE_BACKGROUND_TOOLTIP);
                   
    $cfg[] = array('title'=>TEXT_DAYS_BEFORE_DATE, 
                   'name'=>'day_before_date',
                   'type'=>'input-with-colorpicker',                   
                   'tooltip'=>TEXT_DAYS_BEFORE_DATE_TIP);
                             
    return $cfg;
  } 
    
  function render($field,$obj,$params = array())
  {
    $cfg = new fields_types_cfg($field['configuration']);
    
    if(strlen($obj['field_' . $field['id']])>0)
    {
      $value = date('Y-m-d',$obj['field_' . $field['id']]);
    }
    else
    {
      $value = '';
    }
    
    if(!isset($params['is_new_item']))
    {
      $params['is_new_item'] = false;
    }
         
    //handle default value            
    if($params['is_new_item']==true and strlen($cfg->get('default_value'))>0 and strlen($obj['field_' . $field['id']])==0)
    {
      $value = date('Y-m-d',strtotime("+" . (int)$cfg->get('default_value') . " day"));                                                            
    }
    
    return '<div class="input-group input-medium date datepicker">' . input_tag('fields[' . $field['id'] . ']',$value,array('class'=>'form-control fieldtype_input_date field_' . $field['id'] . ($field['is_required']==1 ? ' required':''))) . '<span class="input-group-btn"><button class="btn btn-default date-set" type="button"><i class="fa fa-calendar"></i></button></span></div>';
  }
  
  function process($options)
  {
    return get_date_timestamp($options['value']);
  }
  
  function output($options)
  {
    if(isset($options['is_export']))
    {
      return format_date($options['value']);
    }
    elseif(strlen($options['value'])>0)
    {
      $cfg = fields_types::parse_configuration($options['field']['configuration']);            
      
      if(!isset($cfg['background'])) $cfg['background']='';
      if(!isset($cfg['day_before_date'])) $cfg['day_before_date']='';
      if(!isset($cfg['day_before_date_color'])) $cfg['day_before_date_color']='';
                
      //highlight field if overdue date    
      if((date('Y-m-d',$options['value'])==date('Y-m-d') or $options['value']<time()) and strlen($cfg['background'])>0)
      {                      
        return render_bg_color_block($cfg['background'],format_date($options['value']));
      }
      
      //highlight field before due date
      if(strlen($cfg['day_before_date'])>0 and strlen($cfg['day_before_date_color'])>0 and $options['value']>time())
      {
        if($options['value']<strtotime('+'.$cfg['day_before_date'] . ' day'))
        {
          return render_bg_color_block($cfg['day_before_date_color'],format_date($options['value'])); 
        }
      }
          
      //return single value      
      return format_date($options['value']);
            
    }
    else
    {
      return '';
    }
  }
  
  function reports_query($options)
  {
    $filters = $options['filters'];
    $sql_query = $options['sql_query'];
  
    $sql = reports::prepare_dates_sql_filters($filters,$options['prefix']);
        
    if(count($sql)>0)
    {
      $sql_query[] =  implode(' and ', $sql);
    }
              
    return $sql_query;
  }
}