<?php

function recurseCopy($src,$dst, $childFolder='') { 
    $dir = opendir($src); 
    if(!file_exists($dst)) mkdir($dst);
    if ($childFolder!='') {
        $folder = $dst.'/'.$childFolder;
        if(!file_exists($folder)) mkdir($folder);
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    recurseCopy($src . '/' . $file,$dst.'/'.$childFolder . '/' . $file); 
                } else { 
                    copy($src . '/' . $file, $dst.'/'.$childFolder . '/' . $file); 
                }  
            } 
        }
    } else {
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    recurseCopy($src . '/' . $file,$dst . '/' . $file); 
                } else { 
                    copy($src . '/' . $file, $dst . '/' . $file); 
                }  
            } 
        } 
    }
    closedir($dir); 
}

$src = __DIR__.'/build';
$dst = __DIR__.'/../../../';

recurseCopy($src,$dst);

?>