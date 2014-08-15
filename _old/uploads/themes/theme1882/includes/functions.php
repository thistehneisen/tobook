<?php 
defined('_JEXEC') or die;

//Wrap Title words with Spans
function wrap_with_span($string){
  if(strpos($string, '||')){
    $string_delim_arr = explode('||', $string);
    $string = $string_delim_arr[0];
  }
  $string_array = explode(" ", $string); 
  $string_spans[] = "";
    foreach ($string_array as $key => $value) {
      $string_spans[] = '<span class="item_title_part' . $key . '">'.$value.'</span> ';
    }
  $wrapped_string = implode($string_spans);
  return  $wrapped_string;
  }  

//Limit words
function limit_words($string, $word_limit){
  $words = explode (" ",$string);
  return implode (" ",array_splice ($words,0,$word_limit));
}

//Wrap with tags
function wrap_with_tag($string, $tag){
  $wrapped_string = '<'. $tag .'>'. $string .'</'. $tag .'>';
  return  $wrapped_string;
}

