<?php

/**
 * Our homepage. Show the most recently added quote.
 * 
 * controllers/Welcome.php
 *
 * ------------------------------------------------------------------------
 */
class Welcome extends Application {

    function __construct()
    {
	parent::__construct();
    }

    //Credit goes to stackoverflow.com for this function.
    function endsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
    }
    
    //-------------------------------------------------------------
    //  Homepage: show a list of the orders on file
    //-------------------------------------------------------------

    function index()
    {
	// Build a list of orders
        
        $map = directory_map('./data/', 1);
        $xmlmap = array();
        foreach($map as $file) { //Filter out the .xml files
            if($this->endsWith($file, ".xml")) {
                $filename = substr($file, 0, strlen($file)-4);
                $xmlmap[] = array('filename' => $filename);
            }
        }
        $this->data['orders'] = $xmlmap;
	
	// Present the list to choose from
	$this->data['pagebody'] = 'homepage';
	$this->render();
    }
    
    //-------------------------------------------------------------
    //  Show the "receipt" for a specific order
    //-------------------------------------------------------------

    function order($filename)
    {
        $this->order->load_order($filename.".xml");
        
        $this->data = array_merge($this->data, $this->order->curr_order()); //accessing the order property
            //access the contents of $elem as (string)$elem, and attributes as $elem[attr]
        
        // Build a receipt for the chosen order, retrieving all info from the model
        
        
	// Present the list to choose from
	$this->data['pagebody'] = 'justone';
	$this->render();
    }
    

}
