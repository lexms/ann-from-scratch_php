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
                foreach ($layer as $neuron=>$value){
                    #layer berisi object neuron
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
                $sumOutput += $this->dendron->get_connectedNeuron()->getOutput() * $this->dendron-> get_weight();
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
        private $layers = [];
        function __construct($topology){
            $this->layers = array();

            # Push Neuron Object to Layer based on topology
            foreach ($topology as $key=>$item){
                $layer = array();
                for ($i = 0; $i < $item; $i++){
                    if (sizeof($this->layers) == 0){
                        $neu = new Neuron(0);
                        array_push($layer, $neu);
                    }else{
                        $neu = new Neuron($this->layers[sizeof($layer)-1]);
                        array_push($layer, $neu);
                        
                    }
                }
                #Push Neuron none
                $neu = new Neuron(0);
                array_push($layer, $neu);

                #set output last layer to 1
                $layer[sizeof($layer)-1]->setOutput(1);

                #push another layer
                array_push($this->layers, $layer);
            }
            print_r($this->layers);
        }
        function setInput($inputs){
            foreach ($inputs as $key=>$value){
                $this->layers[0][$key]->setOutput($value);
            }
        }
        function feedForward(){
            $sliced_1_nTheRest = array_slice($this->layers, 1);
            foreach ($sliced_1_nTheRest as $layer){
                for ($i = 0; $i < $layer; $i++){
                    $neu = new Neuron($i);
                    $neu->feedForward();
                } 
            }
        }

        function backPropagate($target){
            for ($i = 0 ; $i < sizeof($target); $i++){
                $sliced_min1_layers = array_slice($this->layers, -1 );
                $sliced_min1_layers[$i]->setError($target[$i]- $this->$sliced_min1_layers[$i]->getOutput());
            }
            foreach (array_reverse($this->layers) as $layer){
                for ($i = 0; $i < $layer; $i++){
                    $neu = new Neuron($layer);
                    $neu->backPropagate();
                } 
                
            }
        }
        function getError($target){
            $err = 0;
            for ($i = 0 ; $i < sizeof($target); $i++){
                $sliced_min1_layers = array_slice($this->layers, -1 );
                $e = ($target[$i] - $sliced_min1_layers[$i]->getOutput());
                $err += pow($e,2);
            }
            $err /= sizeof($target);
            $err = sqrt($err);
            return $err;
        }
        function getResults(){
            $output = array();
            for ($i = 0; $i < array_slice($this->layers, -1) ; $i++){
                $neu = new Neuron($this->layers, -1);
                array_push($output, $neu->getOutput());
            }
            return $output;
        }
        function getThResults(){
            $output = array();
            for ($i = 0; $i < array_slice($this->layers, -1) ; $i++){
                $neu = new Neuron($this->layers, -1);
                $o = $neu->getOutput();
                if ($o > 0.5){
                    $o = 1;
                }else{
                    $o = 0;
                }
                array_push($output, $o);
            }
            array_pop($output);
            return $output;
        }
    }

    function main(){
        $steps = 0;
        $firststep = 1;
        $topology = [2,3,2];
        $net = new Network($topology);
        $learning_rate = 0.09;
        $momentum = 0.015;

        for ($j = 0 ; $j < 10; $j++){
            $err = 0;
            $inputs = [[0, 0], [0, 1], [1, 0], [1, 1]];
            $outputs = [[0, 0], [1, 0], [1, 0], [0, 1]];
            foreach ($inputs as $key=>$value){
                print("input : ");
                print_r($value);
                print(" of " . strval($key) . " th input");
                $net->setInput($value);
                $net->feedForward();
                $net->backPropagate($outputs[$key]);
                print("output: " . strval($net->getResults()));
                $err += $net->getError($outputs[$key]);

                $steps+=$firststep;
                print("steps=  " . $steps);
            }
            print ("error: " . $err);
            if($err < 1){
                break;
            }
        }
        
    }

    main();
    




?>