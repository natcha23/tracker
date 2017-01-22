<?php

class fieldtype_formula
{
  public $options;
  
  function __construct()
  {
    $this->options = array('title' => TEXT_FIELDTYPE_FORMULA_TITLE);
  }
  
  function get_configuration()
  {
    $cfg = array();
    
    $cfg[] = array('title'=>TEXT_FORMULA, 'name'=>'formula','type'=>'input','tooltip'=>TEXT_FORMULA_TIP,'params'=>array('class'=>'form-control'));
    
    $cfg[] = array('title'=>tooltip_icon(TEXT_NUMBER_FORMAT_INFO) . TEXT_NUMBER_FORMAT, 'name'=>'number_format','type'=>'input','params'=>array('class'=>'form-control input-small input-masked','data-mask'=>'9/~/~'), 'default'=>CFG_APP_NUMBER_FORMAT);
    $cfg[] = array('title'=>tooltip_icon(TEXT_CALCULATE_TOTALS_INFO) . TEXT_CALCULATE_TOTALS, 'name'=>'calclulate_totals','type'=>'checkbox');    
    
    return $cfg;
  }
  
  function render($field,$obj,$params = array())
  {
    return $obj['field_' . $field['id']] . input_hidden_tag('fields[' . $field['id'] . ']',$obj['field_' . $field['id']]);
  }
  
  function process($options)
  { 
    return $options['value'];
  }
  
  function output($options)
  {
    $value = $options['value'];
    
    $cfg = new fields_types_cfg($options['field']['configuration']);
    
    if(strlen($cfg->get('number_format'))>0 and strlen($value)>0)
    {
      $format = explode('/',str_replace('*','',$cfg->get('number_format')));
                  
            
      $value = number_format($value,$format[0],$format[1],$format[2]);
    }
    elseif(strstr($value,'.'))
    {
      $value = number_format((float)$value,2,'.','');
    }
            
    return $value;
  }
  
  function reports_query($options)
  {
  	global $sql_query_having;
  	  	
    $filters = $options['filters'];
    $sql_query = $options['sql_query'];
                
    $sql = reports::prepare_numeric_sql_filters($filters,'');
    
    if(count($sql)>0)
    {
      $sql_query_having[$options['entities_id']][] =  implode(' and ', $sql);
    }
                
    return $sql_query;
  } 
  
  /**
   *  function to prepare sql 
   *  by default funciton reurn string with formulas query
   *  $prepare_field_sum with ture retusn fields sum (using in graph report)
   *  $listing_sql_query_select as array return list of sql query in array (using in listing total calculation)
   */
  public static function prepare_query_select($entities_id, $listing_sql_query_select,$prepare_field_sum = false)
  {
    $numeric_fields = array();
    $fields_query = db_query("select * from app_fields where entities_id='" . db_input($entities_id) . "' and type in ('fieldtype_input_numeric','fieldtype_input_numeric_comments')");
    while($fields = db_fetch_array($fields_query))
    {
      $numeric_fields[] = $fields['id'];
    }
    
    $fields_query = db_query("select * from app_fields where entities_id='" . db_input($entities_id) . "' and type='fieldtype_formula'");
    while($fields = db_fetch_array($fields_query))
    {
      $cfg = fields_types::parse_configuration($fields['configuration']);    
    
      $formula = $cfg['formula'];
      
      if(strlen($formula)>0)
      {
        foreach($numeric_fields as $fields_id)
        {
          $formula = str_replace('[' . $fields_id . ']','e.field_' . $fields_id,$formula);
        }
        
        if(strstr($formula,'{') and class_exists('functions'))
        {
          $formula = functions::prepare_formula_query($entities_id, $formula);
        }
        
        if(!strstr($formula,'[') and !strstr($formula,'{'))
        {    
          if($prepare_field_sum)
          {
            $listing_sql_query_select .= ", sum(" . $formula . ") as sum_field_" . $fields['id'] . " ";
          }
          elseif(is_array($listing_sql_query_select))
          {
            $listing_sql_query_select[] = "(" . $formula . ") as field_" . $fields['id']; 
          }
          else
          {    
            $listing_sql_query_select .= ", (" . $formula . ") as field_" . $fields['id'];
          }          
        }
        else
        {
          echo '<div class="alert alert-danger">' . TEXT_ERROR_FORMULA_CALCULATION .' ' . $cfg['formula'] .  '</div>';
        }
      }
    }
    
    return $listing_sql_query_select;
  } 
}