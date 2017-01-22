<?php

class fieldtype_input_numeric_comments
{
  public $options;
  
  function __construct()
  {
    $this->options = array('title' => TEXT_FIELDTYPE_INPUT_NUMERIC_COMMENTS_TITLE);
  }
  
  function get_configuration($params = array())
  {
    $cfg[] = array('title'=>tooltip_icon(TEXT_NUMBER_FORMAT_INFO) . TEXT_NUMBER_FORMAT, 'name'=>'number_format','type'=>'input','params'=>array('class'=>'form-control input-small input-masked','data-mask'=>'9/~/~'), 'default'=>CFG_APP_NUMBER_FORMAT);
    $cfg[] = array('title'=>tooltip_icon(TEXT_CALCULATE_TOTALS_INFO) . TEXT_CALCULATE_TOTALS, 'name'=>'calclulate_totals','type'=>'checkbox');
    
    return $cfg;
  }
    
  function render($field,$obj,$params = array())
  {
    if($params['form']=='comment')
    {
      return input_tag('fields[' . $field['id'] . ']',$obj['field_' . $field['id']],array('class'=>'form-control input-small fieldtype_input_numeric field_' . $field['id'] . ($field['is_required']==1 ? ' required':'') . ' number'));
    }
    else
    {
      return '<p class="form-control-static">' . $obj['field_' . $field['id']] . '</p>' . input_hidden_tag('fields[' . $field['id'] . ']',$obj['field_' . $field['id']]);
    }
  }
  
  function get_fields_sum($entity_id,$item_id,$field_id)
  {
    $total = 0;
    
    $comments_query = db_query("select * from app_comments where entities_id='" . db_input($entity_id) . "' and items_id='" . db_input($item_id) . "'");
    while($comments = db_fetch_array($comments_query))
    {
      $history_query = db_query("select * from app_comments_history where comments_id='" . db_input($comments['id']) . "' and fields_id='" . $field_id. "'");
      while($history = db_fetch_array($history_query))
      {        
        $total +=$history['fields_value'];        
      }      
    }
    
    return $total;
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
    elseif(strstr($options['value'],'.'))
    {
      $options['value'] = number_format((float)$options['value'],2,'.','');
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