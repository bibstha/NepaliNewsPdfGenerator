<?php
/**
 * In general a filter has
 * a. Input
 * b. Parameters
 * c. Processes input according to parameters and it's logic and returns an output
 * 
 * Errors should be handled through Exceptions
 * 
 * @author Bibek Shrestha <bibekshrestha [at] gmail [dot] com>
 *
 */
interface NNPG_Filter_Interface
{
    public function getName();
    public function setParams($params);
    public function getParams();
    public function setInput($input);
    public function process();
}