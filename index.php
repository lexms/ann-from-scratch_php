<?php
    class Connection{
        public $connectedNeuron;
        public $weight;
        public $dweight;
        
        function __construct($connectedNeuron){
            $this->connectedNeuron = $connectedNeuron;
        }   
        public function get_connectedNeuron(){
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
                $sumOutput += $this->dendron->get_connectedNeuron();
            }
            
            
        }

    }

    $neuron = new Neuron(5);
    $neuron->feedForward();


?>