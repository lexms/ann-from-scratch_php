<?php
    class Connection{
        public $connectedNeuron;
        public $weight;
        public $dweight;
        
        function __construct($connectedNeuron){
            $this->connectedNeuron = $connectedNeuron;
        }   
        function get_connectedNeuron(){
            return $this->connectedNeuron;
        }
        function nrand($mean, $sd){
            $x = mt_rand()/mt_getrandmax();
            $y = mt_rand()/mt_getrandmax();
            return sqrt(-2*log($x))*cos(2*pi()*$y)*$sd + $mean;
        }
        function get_weight(){
            $weight = nrand(0,1);
            return $weight;
        }
        function get_dweight(){
            $dweight = 0;
            return $dweight;
        }
    }

    class Neuron{
        public $dendrons = array();
        public $error = 0.0;
        public $gradient = 0.0;
        public $output = 0.0;
        public $learning_rate = 0.001;
        public $momentum = 0.01;

        function __construct($layer){
            if ($layer = 0) {
                //pass
            }else{
                for ($i = 0; $i < $layer; $i++){
                    $con = new Connection($neuron);
                    array_push($this->dendrons, $con);
                }
            }
        }

        function adderror($err){
            return $error = $this->error+$err;
        }
        function sigmoid($x){
            return 1/(1+ exp($x * -1));
        }
        function dsigmoid($x){
            return $x * (1 - $x);
        }
        function setError($err){
            $this->err = $err;
        }
        function setOutput($output){
            $this->output = $output;
        }
        function getOutput(){
            return $this->output;
        }
        function feedForward(){
            $sumOutput = 0;
            if (sizeof($this->dendrons) == 0){
                return null;
            }
            foreach ($this->dendrons as $dendron){
                $sumOutput += $this->dendron->get_connectedNeuron() * $this->dendron-> get_weight();
            }
            $this->output = sigmoid($sumOutput);            
        }
        function backPropagate(){
            $this->gradient = $this->error * dsigmoid($this->output);
            foreach ($this->dendrons as $dendron){
                $this->dendron->dweight = $learning_rate * (
                    $this->dendron->get_connectedNeuron()->$output * $this->gradient) + $this->momentum * $this->dendron->get_dweight();
                    $this->dendron->weight += $this->dendron->get_dweight();
            }
            $this->error = 0;
        }

    }

    class Network{
        function __construct($topology){
            $this->layers = array();
            for ($numNeuron = 0; $numNeuron < $topology; $numNeuron++){
                $layer = array();
                for ($i = 0; $i < $numNeuron; $i++){
                    if (sizeof($this->layers) == 0){
                        $neu = new Neuron($neuron);
                        array_push($layer, $neu);
                    }else{
                        $neu = new Neuron($neuron);
                        array_push($layer, $neu);
                    }
                }
            }

        }
    }



/* 
    $neuron = new Neuron(5);
    $neuron->feedForward(); */


?>