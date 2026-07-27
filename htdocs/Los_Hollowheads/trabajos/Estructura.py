from Nodos import Nodo

class Pila:
    def __init__(self):
        self.top = None  

    def push(self, dato):
        nuevo_nodo = Nodo(dato)
        nuevo_nodo.siguiente = self.top
        self.top = nuevo_nodo

    def pop(self):
        if self.top is None:
            return None
        dato = self.top.dato
        self.top = self.top.siguiente
        return dato

    def peek(self):
        if self.top is None:
            return None
        return self.top.dato

class Cola:
    def __init__(self):
        self.frente = None
        self.final = None

    def enqueue(self, dato):
        nuevo_nodo = Nodo(dato)
        if self.final is None:
            self.frente = nuevo_nodo
            self.final = nuevo_nodo
        else:
            self.final.siguiente = nuevo_nodo
            self.final = nuevo_nodo

    def dequeue(self):
        if self.frente is None:
            return None
        dato = self.frente.dato
        self.frente = self.frente.siguiente
        if self.frente is None:
            self.final = None
        return dato

    def peek(self):
        if self.frente is None:
            return None
        return self.frente.dato