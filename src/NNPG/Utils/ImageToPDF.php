<?php
class NNPG_Util_ImageToPDF
{
    public function convert($srcPath, $dstPath)
    {
        // @todo simplified caching, change to based on global caching variable
        if (file_exists($dstPath)) return;
        $cmd = "convert $srcPath $dstPath";
        exec($cmd, $output, $retVal);
        
        print $retVal;
    }
}