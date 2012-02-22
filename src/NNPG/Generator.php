<?php 

class NNPG_Generator
{
    public function generate($newsSourceType, $date)
    {
        $startFilter = new NNPG_FilterCommand();
        $startFilter->name = 'CollectorKTMPost';
        $startFilter->params = array(
            'date' => $date,
        );
        
        $endFilter = new NNPG_FilterCommand();
        $endFilter->name = 'CombinePDF';
        $endFilter->params = array(
            // 'outPath' => FILE_PATH . '/CollectorKTMPost/' . date('Y-m-d',strtotime($date)),
        );
        
        $this->process(array($startFilter, $endFilter));
    }
    
    public function process($filters)
    {
        $filterOut = array();
        foreach ($filters as $filter) {
            // extract Name and Params
            $filterName = $filter->name;
            $filterParams = $filter->params;

            // append the output of previous filter
            $filterParams += $filterOut;
            $filterClassName = 'NNPG_Filter_' . $filterName;
            
            require_once(str_replace('_', '/', $filterClassName . '.php'));
            $filterObj = new $filterClassName($filterParams);
            
            $filterOut = $filterObj->process();
        }
    }
    
    
}

class NNPG_FilterCommand
{
    public $name;
    public $params;
}