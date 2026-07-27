lista = []
class Alumnos:
    def __init__(self, lista):
        self.apellido = lista[0]
        self.nombre = lista[1]
        self.genero = lista[2]
        self.nac = lista[3]
        self.promedio = lista[4]
        self.edad = lista[5]
        self.estado = lista[6]

    def imprimir(self):
        print(f"Nombre: {self.apellido} {self.nombre}")
        print(f"Nombre: {self.edad} >> {self.nac} >> {self.estado}")
        print(f"Genero: {self.genero} >> {self.promedio}")



if __name__ =="__main__":
    archivo = open("alumnos.csv", "r")
    datos = []
    for linea in archivo.readlines():
        linea = linea.rstrip()
        linea = linea.split(",")
        linea = float(linea[5])
        print(linea)
        obj = Alumnos(linea)
        datos.append(obj)
        
    for item in (datos):
        item.imprimir()

#segunda practica matrices
