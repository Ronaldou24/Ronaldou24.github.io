class NodoVuelo:
    def __init__(self, identificador, aerolinea, destino, horario):
        self.identificador = identificador
        self.aerolinea = aerolinea
        self.destino = destino
        self.horario = horario  
        self.siguiente = None