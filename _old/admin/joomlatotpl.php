<?php
//------------------------------Convert  Joomla Template--------------------------

function joomlaToTemplate($pathtoindex = 'index.php', $pathtoxml = 'templateDetails.xml', $originaltemplateid, $pathtoindextemp = '') {

    $tempMessage = array();
    if(file_exists($pathtoindex)){
       if(file_exists($pathtoxml)){}
       else{
        $tempMessage['message'] = "Template cannot be uploaded. Unable to read index file";
        $tempMessage['result'] = 'error'; 
       }
    }
    else{
      $tempMessage['message'] = "Template cannot be uploaded. Unable to read XML file";
      $tempMessage['result'] = 'error'; 
      
    }
   if (empty($tempMessage)) {
        //---------Get Contents of index file
        $stripedhtmlcontent = file_get_contents($pathtoindex);

        // TO REMOVE BASE URLS
        
        $stripedhtmlcontent=str_replace("<?php echo \$this->baseurl ?>", "BASE_URL_REPLACEMENT", $stripedhtmlcontent);
        $stripedhtmlcontent=str_replace("<? echo \$this->baseurl ?>", "BASE_URL_REPLACEMENT", $stripedhtmlcontent);
      

        //---------Parse the XML and remove the params with default value--------------

        $xml = simplexml_load_file($pathtoxml);
            //if(!$xml){

            //}
            $paramsarray = (array) $xml->params;
            $positionarray = (array) $xml->positions;
            $paramsarray = (array) $xml->params;
            //echopre($paramsarray['param']);exit;
            if (is_array($paramsarray['param']) and sizeof($paramsarray['param']) > 0) {
                foreach ($paramsarray['param'] as $items) {
                    $items = (array) $items;
                    //echo"<pre>";print_r($items); 
                    foreach ($items as $item) {
                        //echo"<pre>";print_r($item);
                        if (array_key_exists('name', $item)) {
                            //echo"item",$item['name'];
                            $regex = '/\<\?php?(.*?)params?(.*?)' . $item[name] . '?(.*?)\?>/';
                            //$regex='/'.$item[name].'/';
                            //preg_match_all($regex, $stripedhtmlcontent, $matches);
                            $stripedhtmlcontent = preg_replace($regex, $item['default'], $stripedhtmlcontent);
                        }
                    }
                }
            }
            //---------Parse the XML--------------

            //-------------Remove php tags----------
            $stripedhtmlcontent = preg_replace('#<\?(?:php)?(.*?)\?>#s', NULL, $stripedhtmlcontent);


            //--------------Get all jdoc------------------- 
            preg_match_all('<jdoc:include?(.*?)\/>', $stripedhtmlcontent, $matches);
            //echo"<pre>";print_r($matches);echo"</pre>";exit;

            //---------Search and replace the editable positions------------
            foreach ($matches[0] as $match) {
                $attribute_array = explode(' ', $match);
                //echo"<pre>";print_r($attribute_array);echo"</pre>";//exit;
                if (in_array('type="head"', $attribute_array)) {//head 
                    //echo"<pre>";print_r($attribute_array);echo"</pre>";
                    //$stripedhtmlcontent =preg_replace('<jdoc:include\s+type="head"\s+?(.*?)\/>', '!--start head-->&nbsp<!--end head--', $stripedhtmlcontent); 
                } elseif (in_array('type="component"', $attribute_array)) {//component 
                    //$stripedhtmlcontent =preg_replace('<jdoc:include\s+type="component"\s+?(.*?)\/>', '!--start component--><!--end component--', $stripedhtmlcontent); 
                } elseif (in_array('type="message"', $attribute_array)) {//message
                    // $stripedhtmlcontent =preg_replace('<jdoc:include\s+type="message"\s+?(.*?)\/>', '!--start message--><!--end message--', $stripedhtmlcontent); 
                } elseif (in_array('type="modules"', $attribute_array)) {//modules
                    foreach ($positionarray['position'] AS $position) {
                        //echo"<pre>";print_r($position);echo"</pre>";
                        if (in_array('name="' . $position . '"', $attribute_array)) {
                            $search_string = implode(' ', $attribute_array);
                            $search_tag = "<" . $search_string . '>';
                            $replace_string = "<!--start " . $position . "-->pos_" . $position . "<!--end " . $position . "-->";
                            $stripedhtmlcontent = str_replace($search_tag, $replace_string, $stripedhtmlcontent, $count);
                        }
                    }
                } elseif (in_array('type="module"', $attribute_array)) {//module
                    foreach ($positionarray['position'] AS $position) {
                        //echo"<pre>";print_r($position);echo"</pre>";
                        if (in_array('name="' . $position . '"', $attribute_array)) {
                            $search_string = implode(' ', $attribute_array);
                            $search_tag = "<" . $search_string . '>';
                            $replace_string = "<!--start " . $position . "-->pos_" . $position . "<!--end " . $position . "-->";
                            $stripedhtmlcontent = str_replace($search_tag, $replace_string, $stripedhtmlcontent, $count);
                        }
                    }
                }
            }
            //echo $stripedhtmlcontent;exit;
            return convertHtmlToTemplate($stripedhtmlcontent, $positionarray['position'], $pathtoindextemp, $originaltemplateid);
   }
   else{
       return $tempMessage;
   }

    
}

//------------------------------Convert HTML to Template--------------------------//
function convertHtmlToTemplate($htmlPageContents, $panels = array(), $pathtoindextemp, $originaltemplateid) {
    //------------- code to check all the panels in the template file------------
    $tempPanels = (array) $panels;
    $tempMessage = array();
    foreach ($tempPanels as $panel) {
        //echo($panel);
        $starteditableareaposition = strpos($htmlPageContents, "start " . $panel);
        if ($starteditableareaposition === false) {
            $tempMessage['message'].= "<br>Unable to locate 'start " . $panel . "' in the html file";
            $tempMessage['result'] = 'error';
        }
        $endeditableareaposition = strpos($htmlPageContents, "end " . $panel);
        if ($endeditableareaposition === false) {
            $tempMessage['message'].= "<br>Unable to locate 'end " . $panel . "' in the html file";
            $tempMessage['result'] = 'error';
        }
    }

    // -----------code to create new template and db operations-------------
    if (empty($tempMessage)) {
        /*
         * create new template and insert into database
         */
        foreach ($tempPanels as $panel) {
            $startEditPosition = strpos($htmlPageContents, "start " . $panel);
            $endEditPosition = strpos($htmlPageContents, "end " . $panel);
            /* ------fetch the editable area from html file. -------------*/
            $editableelements = substr($htmlPageContents, $startEditPosition, $endEditPosition - $startEditPosition);
            $editableelemntswithouthtmlcomment = substr($editableelements, strpos($editableelements, "-->") + 3, strpos($editableelements, "<!--") - strpos($editableelements, "-->") - 3);
            $neweditcontent = "{\$editable" . $panel . "}";
            $htmlPageContents = str_replace($editableelemntswithouthtmlcomment, $neweditcontent, $htmlPageContents);
            $sql = "insert into  tbl_template_panel(temp_id,panel_type,panel_html) 
                    values('" . $originaltemplateid . "','" . $panel . "',
                    'Dummy content')";
            mysql_query($sql) or die(mysql_error());
            $tempMessage['result'] = 'success';
        }
        
        //----------Parse the css and js files----------
        $doc = new DOMDocument();
        @$doc->loadHTML($htmlPageContents);
        $tags = $doc->getElementsByTagName('link'); //css files
        foreach ($tags as $tag) {
            $hrefValue = $tag->getAttribute('href');
            if (preg_match('/\.css$/', $hrefValue, $match)) {
                $pathArray = explode('/', $hrefValue);
                $count = sizeof($pathArray);
                $tag->setAttribute('href', 'BASE_URL_REPLACEMENT/css/' . $pathArray[$count - 1]);
                //----------Insert css files to table---------------
                $sql = "insert into  tbl_joomla_template_files(ntemplate_mast_id,vjoomla_template_file,ejoomla_template_file_type) 
                            values('" . $originaltemplateid . "','" . $pathArray[$count - 1] . "',
                            'C')";
                mysql_query($sql) or die(mysql_error());
            }
        }
        
        $tags = $doc->getElementsByTagName('script');//JS files
        foreach ($tags as $tag) {
            $hrefValue = $tag->getAttribute('src');
            if (preg_match('/\.js$/', $hrefValue, $match)) {
                $pathArray = explode('/', $hrefValue);
                $count = sizeof($pathArray);
                $tag->setAttribute('href', 'BASE_URL_REPLACEMENT/js/' . $pathArray[$count - 1]);
                 //----------Insert js files to table---------------
                $sql = "insert into  tbl_joomla_template_files(ntemplate_mast_id,vjoomla_template_file,ejoomla_template_file_type) 
                            values('" . $originaltemplateid . "','" . $pathArray[$count - 1] . "',
                            'J')";
                mysql_query($sql) or die(mysql_error());
            }
        }
        $htmlPageContents = $doc->saveHTML();

        //-------Create the html files---------
        $fp = fopen($pathtoindextemp . "/index_temp.htm", "w");
        fputs($fp, $htmlPageContents);
        fclose($fp);
        copy($pathtoindextemp . "/index_temp.htm", $pathtoindextemp . "/subpage_temp.htm");
        //echo $htmlPageContents;exit;
    }
    /* New valiation section ends here */
    return $tempMessage;
}
    


?>