from datetime import timedelta
from Nodos import NodoVuelo

class ColaPista:
    def __init__(self):
        self.frente = None
        self.final = None

    def encolar_con_ajuste(self, id_v, aero, dest, horario_deseado):
        duracion_despegue = timedelta(minutes=30)
        nuevo_horario = horario_deseado

        if self.final is not None:
            hora_liberacion_pista = self.final.horario + duracion_despegue
            if nuevo_horario < hora_liberacion_pista:
                nuevo_horario = hora_liberacion_pista

        nuevo_nodo = NodoVuelo(id_v, aero, dest, nuevo_horario)

        if self.frente is None:
            self.frente = nuevo_nodo
            self.final = nuevo_nodo
        else:
            self.final.siguiente = nuevo_nodo
            self.final = nuevo_nodo

    def desencolar(self):
        if self.frente is None:
            return None
        aux = self.frente
        self.frente = self.frente.siguiente
        if self.frente is None:
            self.final = None
        return aux

    def esta_vacia(self):
        return self.frente is None