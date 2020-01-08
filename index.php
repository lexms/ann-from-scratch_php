<?php
class Connection{
    public $connectedNeuron;
    public $weight;
    public $dweight;

    function __construct($connectedNeuron){
        $this -> connectedNeuron = $connectedNeuron;
    }   
    function get_connectedNeuron(){
        return $this -> connectedNeuron;
    }
}

class Neuron{
    public $dendronds;
    public $error;
    public $gradient;
    public $output;

}

?>