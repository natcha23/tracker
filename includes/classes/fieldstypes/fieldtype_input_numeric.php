<?php

class fieldtype_input_numeric
{
  public $options;
  
  function __construct()
  {
    $this->options = array('title' => TEXT_FIELDTYPE_INPUT_NUMERIC_TITLE);
  }
  
  function get_configuration($params = array())
  {
    $cfg[] = array('title'=>TEXT_WIDHT, 
                   'name'=>'width',
                   'type'=>'dropdown',
                   'choices'=>array('input-small'=>TEXT_INPTUT_SMALL,'input-medium'=>TEXT_INPUT_MEDIUM,'input-large'=>TEXT_INPUT_LARGE,'input-xlarge'=>TEXT_INPUT_XLARGE),
                   'tooltip'=>TEXT_ENTER_WIDTH,
                   'params'=>array('class'=>'form-control input-medium'));
                   
    $cfg[] = array('title'=>tooltip_icon(TEXT_NUMBER_FORMAT_INFO) . TEXT_NUMBER_FORMAT, 'name'=>'number_format','type'=>'input','params'=>array('class'=>'form-control input-small input-masked','data-mask'=>'9/~/~'), 'default'=>CFG_APP_NUMBER_FORMAT);
    $cfg[] = array('title'=>tooltip_icon(TEXT_CALCULATE_TOTALS_INFO) . TEXT_CALCULATE_TOTALS, 'name'=>'calclulate_totals','type'=>'checkbox');    
    $cfg[] = array('title'=>TEXT_HIDE_FIELD_IF_EMPTY, 'name'=>'hide_field_if_empty','type'=>'checkbox','tooltip_icon'=>TEXT_HIDE_FIELD_IF_EMPTY_TIP);
    $cfg[] = array('title'=>TEXT_IS_UNIQUE_FIELD_VALUE, 'name'=>'is_unique','type'=>'checkbox','tooltip_icon'=>TEXT_IS_UNIQUE_FIELD_VALUE_TIP);
    
    return $cfg;
  }
    
  function render($field,$obj,$params = array())
  {
    $cfg =  new fields_types_cfg($field['configuration']);
    
    $attributes = array('class'=>'form-control ' . $cfg->get('width') .
                        ' fieldtype_input_numeric field_' . $field['id'] . 
                        ($field['is_required']==1 ? ' required':'') .
                        ($cfg->get('is_unique')==1 ? ' is-unique':'') 
                        ); 
    return input_tag('fields[' . $field['id'] . ']',$obj['field_' . $field['id']],$attributes);
  }
  
  function process($options)
  {       
    return str_replace(array(',',' '),array('.',''),$options['value']);
  }
  
  function output($options)
  {
    $cfg = new fields_types_cfg($options['field']['configuration']);
                    
    if(strlen($cfg->get('number_format'))>0 and strlen($options['value'])>0)
    {
      $format = explode('/',str_replace('*','',$cfg->get('number_format')));
                        
      return number_format($options['value'],$format[0],$format[1],$format[2]);
    }
    else
    {
      return $options['value'];
    }
  }
  
  function reports_query($options)
  {
    $filters = $options['filters'];
    $sql_query = $options['sql_query'];
                
    $sql = reports::prepare_numeric_sql_filters($filters);
    
    if(count($sql)>0)
    {
      $sql_query[] =  implode(' and ', $sql);
    }
                
    return $sql_query;
  }
}