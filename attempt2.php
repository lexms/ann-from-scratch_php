<!-- second attempt revised program -->
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
        function __construct($topology){
            $this->layers = array();
            for ($numNeuron = 0; $numNeuron < count($topology); $numNeuron++){
                $layer = array();
                for ($i = 0; $i < $numNeuron; $i++){
                    if (sizeof($this->layers) == 0){
                        $neu = new Neuron(0);
                        array_push($layer, $neu);
                    }else{
                        $sliced_min1_layers = array_slice($this->layers, -1 );
                        $neu = new Neuron($this->layers);
                        array_push($layer, $neu);
                    }
                }
                $neu = new Neuron(0);
                array_push($layer, $neu);

                #array_slice($layer, -1)->setOutput(1);
                $layer(count($layer) -1)->setOutput(1);

                array_push($this->layers, $layer);
                
            }

        }
        function setInput($inputs){
            for ($i = 0; $i < sizeof(inputs); $i++){
                $this->layers[0][$i]->setOutput(inputs[$i]);
            }
        }
        function feedForward(){
            foreach ($this->layers[1] as $layer){
                for ($i = 0; $i < $layer; $i++){
                    $neu = new Neuron($layer);
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
        $firststeps = 1;
        $topology = array();
        array_push($topology, 2);
        array_push($topology, 3);
        array_push($topology, 2);

        $net = new Network($topology);
        $learning_rate = 0.09;
        $momentum = 0.015;

        while(true==true){
            $err = 0;
            $inputs = [[0, 0], [0, 1], [1, 0], [1, 1]];
            $outputs = [[0, 0], [1, 0], [1, 0], [0, 1]];
            for ($i = 0; $i < sizeof(inputs); $i++){
                print("input: " + strval($inputs[$i] + " of " + strval($i) + "th input"));
                $net->setInput($inputs[$i]);
                $net->feedForward();
                $net->backPropagate($outputs[$i]);
                print("output: " + strval($net->getResults()));
                $err += $net->getError($outputs[$i]);

                $steps+=$firststep;
                print("steps=  " + $steps);
            }
            print ("error: " + $err);
            if($err <0.1){
                break;
            }
        }
        
    }

    main();
    



/* 
    $neuron = new Neuron(5);
    $neuron->feedForward(); */


?>