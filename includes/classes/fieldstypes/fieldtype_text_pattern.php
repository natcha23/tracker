<?php

class fieldtype_text_pattern
{
  public $options;
  
  function __construct()
  {
    $this->options = array('title' => TEXT_FIELDTYPE_TEXT_PATTERN);
  }
  
  function get_configuration()
  {
    $cfg = array();
    
    $cfg[] = array('title'=>TEXT_PATTERN, 
                   'name'=>'pattern',
                   'type'=>'textarea',    
                   'tooltip'=>TEXT_ENTER_TEXT_PATTERN_INFO,
                   'params'=>array('class'=>'form-control'));      
    
    return $cfg;
  }
  
  function render($field,$obj,$params = array())
  {        
    return '';
  }
  
  function process($options)
  {
    return '';
  }
  
  function output($options)
  {
    global $app_user;
    
    $html = '';
    
    $cfg = new fields_types_cfg($options['field']['configuration']);
        
    $entities_id = $options['field']['entities_id'];
    
    $item = $options['item'];
            
    $fields_access_schema = users::get_fields_access_schema($entities_id,$app_user['group_id']);
    
    if(isset($options['custom_pattern']))
    {
      $pattern = $options['custom_pattern'];
    }
    else
    {
      $pattern = $cfg->get('pattern');
    }
    
    if(strlen($pattern)>0)
    {      
      if(preg_match_all('/\[(\w+)\]/',$pattern,$matches))
      {                
        foreach($matches[1] as $matches_key=>$fields_id)
        {        
            $field_query = db_query("select f.* from app_fields f where f.type not in ('fieldtype_action') and (f.id ='" . db_input($fields_id) . "' or type='fieldtype_" . db_input($fields_id) . "') and  f.entities_id='" . db_input($entities_id) . "'");
            if($field = db_fetch_array($field_query))
            {            
              //check field access
              if(isset($fields_access_schema[$field['id']]))
              {
                if($fields_access_schema[$field['id']]=='hide') continue;
              }
                                          
              switch($field['type'])
              {
                case 'fieldtype_created_by':
                    $value = $item['created_by'];
                  break;
                case 'fieldtype_date_added':
                    $value = $item['date_added'];                
                  break;
                case 'fieldtype_action':                
                case 'fieldtype_id':
                    $value = $item['id'];
                  break;
                default:
                    $value = $item['field_' . $field['id']]; 
                  break;
              }
              
              $output_options = array('class'=>$field['type'],
                                  'value'=>$value,
                                  'field'=>$field,
                                  'item'=>$item,
                                  'is_export'=>true,                              
                                  'path'=>$options['path']);
                                                                                                                                                    
              if(in_array($field['type'],array('fieldtype_textarea_wysiwyg')))
              {
                $output = trim(fields_types::output($output_options));
              }
              else
              {
                $output = trim(strip_tags(fields_types::output($output_options)));
              }   
              
              //echo '<br>' . $fields_id . ' ' . $output . ' ' . $matches[0][$matches_key];  
              
              $pattern = str_replace($matches[0][$matches_key],$output,$pattern);   
                                         
            }        
        
        }
        
        //check if fields was replaced
        if(preg_replace('/\[(\d+)\]/','',$cfg->get('pattern'))!=$pattern)
        {
          $html = $pattern;
        }
        
      }
    }
    
    return $html;
  }
}