'''class Estudiante:

    def __init__(self, matricula):
        self.matricula = matricula
        self.calificaciones = {}

    def agregar_calificacion(self, materia, nota):
        self.calificaciones[materia] = nota

    def promedio(self):
        if len(self.calificaciones) == 0:
            return 0
        return sum(self.calificaciones.values()) / len(self.calificaciones)

    def nota_alta(self):
        if not self.calificaciones:
            return None
        materia = max(self.calificaciones, key=self.calificaciones.get)
        return materia, self.calificaciones[materia]

    def mostrar_info(self):
        print(f"\nMatricula: {self.matricula}")
        print("Calificaciones:", self.calificaciones)
        print("Promedio:", round(self.promedio(),2))
        mejor = self.nota_alta()
        if mejor:
            print("Materia con nota más alta:", mejor)



materias = [
    "Programacion Avanzada",
    "Fisica II",
    "Circuitos Electricos",

]

estudiantes = [
    Estudiante("2025200100"),
    Estudiante("2025200101"),
    Estudiante("2025200102"),



]
import random

for est in estudiantes:
    for materia in materias:
        nota = round(random.uniform(6,10),1)
        est.agregar_calificacion(materia, nota)

for est in estudiantes:
    est.mostrar_info()'''