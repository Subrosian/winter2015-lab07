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
        $xmlmap = array(); //view parameters for the xml orders
        
        foreach($map as $file) { //Filter out the .xml files, and the menu.xml file
            if($this->endsWith($file, ".xml") && substr($file, 0, strlen($file)-4) != "menu") {
                $filename = substr($file, 0, strlen($file)-4);
                $customer = simplexml_load_file(DATAPATH . $filename.".xml")->customer;
                $xmlmap[] = array('filename' => $filename, 'customer' => $customer);
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
        
        // Build a receipt for the chosen order, retrieving all info from the model
        // All of the view parameter information is stored in curr_order()
        $this->data = array_merge($this->data, $this->order->curr_order()); //accessing the order property
            //access the contents of $elem as (string)$elem, and attributes as $elem[attr]
        
        
	// Present the list to choose from
	$this->data['pagebody'] = 'justone';
	$this->render();
    }
    

}
