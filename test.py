import math
import numpy as np

#py testing

class Connection:
    def __init__(self, connectedNeuron):
        self.connectedNeuron = connectedNeuron
        self.weight = np.random.normal()
        self.dWeight = 0.0

    

class Neuron:
    learning_rate = 0.001
    momentum = 0.01

    def __init__(self, layer):
        self.dendrons = []
        self.error = 0.0
        self.gradient = 0.0
        self.output = 0.0
        if layer is None:
            pass
        else:
            for neuron in layer:
                con = Connection(neuron)
                self.dendrons.append(con)

    def sigmoid(self, x):
        return 1 / (1 + math.exp(-x * 1.0))

    def feedForward(self):
        sumOutput = 0
        if len(self.dendrons) == 0:
            return
        for dendron in self.dendrons:
            sumOutput = sumOutput + (dendron.connectedNeuron.getOutput() * dendron.weight)
        self.output = self.sigmoid(sumOutput)


class Network:
    def __init__(self, topology):
        self.layers = []
        for numNeuron in topology:
            layer = []
            for i in range(numNeuron):
                if (len(self.layers) == 0):
                    print(i)
                    layer.append(Neuron(None)) #yang masuk array layer itu neuron juga
                else:
                    layer.append(Neuron(self.layers[-1]))
            layer.append(Neuron(None))
            layer[-1].setOutput(1)
            self.layers.append(layer) #disini layer ditambahakan
        
    def setInput(self, inputs):
        for i in range(len(inputs)):
            self.layers[0][i].setOutput(inputs[i])

    def feedForword(self):
        for layer in self.layers[1:]:
            for neuron in layer:
                neuron.feedForword()

    def test(self):
        self.layer1 = [[1, 5, 12, 91, 102], [10, 8, 12, 5]]
        print("test")        
        for layer in self.layers[1:]:
            print ("msk for1")
            for neuron in layer:
                print ("mneuronk for2")
                neuron.feedForward()
                print(neuron)
                print(neuron.feedForward())


x = Network(2)
x.test()