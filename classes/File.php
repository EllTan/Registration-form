<?php

    class File{

        public $nameFile;   
        
        function set_name($nameFile) {
            $this->nameFile = $nameFile;
          }
          function get_name() {
            return $this->nameFile;
          }
/*             
        function fileReaFull($nameFile){            
            $fd = fopen($nameFile, "r") or die("не удалось открыть файл");     
            while(!feof($fd)){
                $str = htmlentities(fgets($fd));
                echo $str . "<br>";
            }            
        }

        function fileWriteAPluss($nameFile, $str){
            $fd = fopen($nameFile, "a+") or die("не удалось открыть файл"); 
            $a = "\n" . $str;
            fwrite($fd, $a);
            fclose($fd);
        } */
    }
 
?>