<?php

/**
 * This is a "CMS" model for quotes, but with bogus hard-coded data.
 * This would be considered a "mock database" model.
 *
 * @author jim
 */
class Order extends CI_Model {

    protected $xml = null;
    protected $burgers = array();
    protected $patty_names = array();
    protected $patties = array();
    protected $order = array();
    protected $filename = "";
    
    // Constructor
    public function __construct() {
        parent::__construct();
    }
    
    //Load an order with the corresponding filename
    //Upd (3/25/15, 1:22PM): TBD: Finish this later - correctly doing the foreach loop in here and in curr_order, <<doing correct SimpleXML access><YKWIM>>. Note the start time was in my BB "More TBD Pg. #12" memo.
    function load_order($filename) {
        $this->filename = $filename;
        $this->xml = simplexml_load_file(DATAPATH . $filename);

        $this->order = $this->xml->order;
        
        //build the list of burgers, as an associative array
        foreach($this->xml->burger as $burger) { //Note: the root XML element apparently is not a stdClass element
            $this->burgers[] = $burger;
        }
        
        /*
        // build the list of patties - approach 1
        foreach ($this->xml->patties->patty as $patty) {
            $this->$patty_names[(string) $patty['code']] = (string) $patty;
        }

        // build a full list of patties - approach 2
        foreach ($this->$xml->patties->patty as $patty) {
            $record = new stdClass();
            $record->code = (string) $patty['code'];
            $record->name = (string) $patty;
            $record->price = (float) $patty['price'];
            $this->$patties[$record->code] = $record;
        }*/
    }

    //return the order, as an associative array for use as template parameters in the receipt (to be merged with $this->data of any respective controllers)
    function curr_order() {
        $xml = $this->xml;
        $curr_order = array(
            'order' => $this->filename,
            'customer' => (string)$xml->customer,
            'ordertype' => (string)$xml['type'],
            'burgers' => array()
        );
        
        //add all of the burgers into $curr_order['burgers']
        $curr_burgernum = 0;
        foreach($this->burgers as $burger) {
            $order_burger = array();
            
            //set burgernum
            $curr_burgernum++;
            $order_burger['burgernum'] = $curr_burgernum;
            
            //get burger base
            $order_burger['pattytype'] = $burger->patty['type'];
            
            //get cheeses (top and bottom)
            $cheeses = "";
            if(isset($burger->cheeses['top']))
                $cheeses .= $burger->cheeses['top'];
            if(isset($burger->cheeses['bottom'])) {
                if($cheeses != "") //line break if there was a top one as well
                    $cheeses .= "<br>";
                $cheeses .= $burger->cheeses['bottom'];
            }
            $order_burger['cheeses'] = $cheeses;
            
            //get all toppings
            $toppings = "";
            foreach($burger->topping as $topping) {
                $toppings .= $topping['type'].", ";
            }
            $toppings = substr($toppings, 0, strlen($toppings)-2); //get rid of last comma and space
            $order_burger['toppings'] = $toppings;
            
            //get all sauces
            $sauces = "";
            foreach($burger->sauce as $sauce) {
                $sauces .= $sauce['type'].", ";
            }
            $sauces = substr($sauces, 0, strlen($sauces)-2); //get rid of last comma and space
            $order_burger['sauces'] = $sauces;
            
            $curr_order['burgers'][] = $order_burger;
        }
        
        return $curr_order;
    }
    
    /*
    // retrieve a list of patties, to populate a dropdown, for instance
    function patties() {
        return $this->patty_names;
    }

    // retrieve a patty record, perhaps for pricing
    function getPatty($code) {
        if (isset($this->patties[$code]))
            return $this->patties[$code];
        else
            return null;
    }*/

}
