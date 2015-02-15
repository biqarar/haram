<?php
class query_xlsx_cls extends query_cls {
   
    public function config($allAssoc = false, $title = "Untitled" ,$filename = "Untitled" , $fieltype = "xlsx") {
       
        header('Content-Type: text/html; charset=utf-8'); 
        header("Content-Disposition: attachment; filename=$filename.$fieltype");  
        header("Pragma: no-cache"); 
        header("Expires: 0");
       
        //define separator (defines columns in excel & tabs in word)
        $sep = ($fieltype == "xlsx") ? "\t" : "\t\t\t"; //tabbed character
        
        echo $sep;
        echo $title;
        echo "\n";
        //start of printing column names 

        foreach ($allAssoc[0] as $key => $value) {
            print(gettext($key));
            echo  $sep;
        }
        print("\n"); 


        foreach ($allAssoc as $key => $value) {
            if(is_array($value)){

                foreach ($value as $k => $v) {
                        print(gettext($v) . $sep);
                }
                print "\n";

            }
        }
        print "\n";
        exit();
    }   
}
?>