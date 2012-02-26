<?php
class NNPG_Utils_ImageToPDF
{
    public function convert($srcPath, $dstPath)
    {
        // @todo simplified caching, change to based on global caching variable
        if (file_exists($dstPath)) return;
        $cmd = "convert $srcPath $dstPath";
        exec($cmd, $output, $retVal);
    }
}