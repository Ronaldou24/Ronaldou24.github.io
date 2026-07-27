'''class Interseccion:
    def __init__(self, id, calles, estados):
        self.id = id
        self.calles = calles
        self.estado_semaforo = estados  # True es Verde, False es Rojo

    def cambiar_estado(self):
        for i in range(len(self.estado_semaforo)):
            self.estado_semaforo[i] = not self.estado_semaforo[i]

    def encontrar_verdes(self):
        verdes = []
        for i in range(len(self.calles)):
            if self.estado_semaforo[i] == True:
                verdes.append(self.calles[i])
        return verdes

mi_cruce = Interseccion(1, ["Calle A", "Calle B"], [True, False])

print("Verdes al inicio:", mi_cruce.encontrar_verdes())

mi_cruce.cambiar_estado()

print("Verdes después del cambio:", mi_cruce.encontrar_verdes())'''