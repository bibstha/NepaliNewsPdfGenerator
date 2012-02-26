<?php 

class NNPG_Generator
{
    public function generate($newsSourceType, $date)
    {
        $collectorName = 'NNPG_Filter_Collector' . $newsSourceType;
        $startFilter = new $collectorName;
        $startFilter->setParams( array(
            'date' => $date,
        ) );
        
        $endFilter = new NNPG_Filter_CombinePDF();
        $endFilter->setParams( array(
            'outPath' => sprintf("%s/combined/%s/%s.pdf", FILE_PATH, $startFilter->getName(),
                $startFilter->_getDateForFileName())
        ) );
        
        $this->process(array($startFilter, $endFilter));
    }
    
    public function process($filters)
    {
        $filterOutput = array();
        foreach ($filters as $filterObj) {
            $filterInput = $filterOutput;
            $filterObj->setInput($filterInput);
            $filterOutput = $filterObj->process();
        }
    }
}
