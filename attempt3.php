<!-- third attempt -->
<?php

class Connection{
    public $connectedNeuron;
        public $weight =0.1;
        public $dweight = 0;
    function __construct($connectedNeuron){
        $this->connectedNeuron = $connectedNeuron;
    }

    function getconnectedNeuron(){
        return $this->connectedNeuron;
    }

}

class Neuron{
    public $dendrons = array();
    public $error = 0.0;
    public $gradient = 0.0;  #which direction to minimize the error
    public $output = 0.0;
    public $learning_rate = 0.001;
    public $momentum = 0.01; #changing the weight of the previous weight
    public $layer;

    public function __construct($layer){
        print('layer-neuron:  ');
        print_r($layer);
        if(sizeof($layer) == 0){
            return 0;
        }else{
            foreach ($layer as $neuron){
                $con = new Connection($neuron); #create one connection
                array_push($this->dendrons, $con); #push connection which has neuron in it 
            }
        }
    }
    function addError($err){
        return $error = $this->error+$err;
    }

    function sigmoid($x){
        return 1/(1+ exp($x * -1));
    }
    #derivative of sigmoid
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
        #collect all output of all previous layer
        $sumOutput = 0.0;


        if (sizeof($this->dendrons) == 0){ #no connection
            return null;
        }
        foreach($this->dendrons as $dendron){ 
            #$dendron is connection object
            #$dendron->get_connectedNeuron() is neuron object
            $sumOutput = $sumOutput + $dendron->getconnectedNeuron()->getOutput() *  $dendron->weight;  #multiply the weight of the dendron, how much the dendron amplify the output of the previous layer
        }
        $this->output = $this->sigmoid($sumOutput);
    }

    function backPropagate(){
        #suppy the error of the target neuron, check how much error is produce respect to target neuron
        $this->gradient = $this->error * $this->dsigmoid($this->output);
        #update weight, for all dendron
        foreach($this->dendrons as $dendron){
            #output of the previous neuron
            $this->dendron->dweight = $this->$learning_rate *(
                $dendron->getconnectedNeuron()->getOutput() * $this->gradient) + $this->momentum  * $this->dendron->get_dweight();
            $this->dendron->weight = $this->dendron->weight + $this->dendron->get_dweight();

        }
        $this->error = 0.0;

    }
}


class Network{
    private $layers;
    public function __construct($topology){
        $this->layers = array();

        foreach ($topology as $key=> $numNeuron){
            $layer = array();
            for ($i = 0; $i < $numNeuron; $i++){
                # 
                if (sizeof($this->layers) == 0){
                   
                    $neuron = new Neuron(null);

                    array_push($layer, $neuron);
                }else{
                    #print('size of layerss =  '.sizeof($this->layers).' <br>'); CONFIRMED
                    $sliced_min1_layers = array_slice($this->layers, -1 );
                    $neuron = new Neuron($sliced_min1_layers);
                    array_push($layer, $neuron);
                }
                print('layer-network:  ');
                print_r($layer);
                print('<br>');
               
            }

            #add bias neuron
            $neuron = new Neuron(0);
            $neuron->setOutput(1);
            array_push($layer, $neuron);

            #push another layer
            array_push($this->layers, $layer);
            

        }
    }
    function setInput($inputs){
        for ($i = 0; $i < sizeof($inputs); $i++){
            $this->layers[0][$i]->setOutput($inputs[$i]);
        }
    }

    function feedForward(){
        foreach (array_slice($this->layers, 1) as $layer){
            foreach ($layer as $neuron){
                $neuron->feedForward();
            } 
        }
    }
    function backPropagate($targets){
        #target 
        for ($i = 0; $i < sizeof($target); $i++){
            #
            array_slice($this->layers, -1 )[i]->setError($target[i]- array_slice($this->layers, -1 )[i]->getOutput());
        }
        foreach (array_reverse($this->layers) as $layer){
            foreach ($layer as $neuron){
                $neuron->backPropagate();
            }
        }
    }
    function getError($target){
        $err = 0;
        for ($i = 0; $i < sizeof($target); $i++){
            $e = ($target[$i] - array_slice($this->layers, -1)[$i]->getOutput());
            $err += pow($e,2);
        }
        $err /= sizeof($target);
        $err = sqrt($err);
        return $err;
    }

    function getResults(){
        $output = array();
        foreach (array_slice($this->layers, -1) as $neuron){
            array_push($output, $neuron->getOutput());
        }
        array_pop($output);
        return $output;
    }

    function getThResults(){
        $output = array();
        foreach (array_slice($this->layers, -1) as $neuron){
            $o = $neuron->getOutput();
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
            #print_r($value);
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
        if($err < 3){
            break;
        }
    }
    
}

main();



?>