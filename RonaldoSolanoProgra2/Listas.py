from Nodos import Nodo

class Listas:
    def __init__(self):
        self.inicio = None

    def insertar(self, dato):
        nuevo = Nodo(dato)
        nuevo.siguiente = self.inicio
        self.inicio = nuevo

    def muestraTusDatos(self):
        actual = self.inicio
        while actual is not None:
            print(actual.dato)
            actual = actual.siguiente

    def borrarUnNodo(self, dato):
        actual = self.inicio
        anterior = None
        while actual is not None:
            if actual.dato == dato:
                if anterior is None:
                    self.inicio = actual.siguiente
                    del actual
                    return
                else:
                    anterior.siguiente = actual.siguiente
                del actual
                return
            anterior = actual
            actual = actual.siguiente
        print("Tu dato no se encontró en los nodos")

    def indicatuInicio(self):
        if self.inicio is not None:
            print(self.inicio.dato)
        else:
            print("La lista está vacía")
    
    def liberaMemoria(self):
        actual = self.inicio
        while actual is not None:
            siguiente = actual.siguiente
            del actual
            actual = siguiente
        self.inicio = None

    def ordenar_burbuja(self):
        if self.inicio is None or self.inicio.siguiente is None:
            return 

        intercambio = True
        while intercambio:
            intercambio = False
            actual = self.inicio
            
            while actual.siguiente is not None:
                if actual.dato > actual.siguiente.dato:
                    actual.dato, actual.siguiente.dato = actual.siguiente.dato, actual.dato
                    intercambio = True
                actual = actual.siguiente