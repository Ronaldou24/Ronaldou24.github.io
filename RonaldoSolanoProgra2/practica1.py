import csv

class Alumnos:


    def __init__(self, apellidos, nombres, genero, fecha_nac, promedio, edad, estado):
        self.apellidos = str(apellidos)
        self.nombres = str(nombres)
        self.genero = str(genero).upper()
        self.fecha_nac = str(fecha_nac)  
        self.promedio = float(promedio)
        self.edad = int(edad)
        self.estado = str(estado).lower()

    def obtener_rfc(self):
        """
        Calcula un RFC simplificado.
        Regla: 2 letras apellido + 1 letra nombre + año(2) + mes(2) + día(2)
        """
        try:
            partes = self.fecha_nac.split('-')
            fecha_str = partes[0][2:] + partes[1] + partes[2]
            iniciales = (self.apellidos[:2] + self.nombres[:1]).upper()
            return f"{iniciales}{fecha_str}"
        except (IndexError, ValueError):
            return "RFC-INVALIDO"

    def imprimir_datos(self):
        """ Muestra todos los valores almacenados en los atributos """
        print(f"Alumno: {self.apellidos}, {self.nombres} | Género: {self.genero} | "
              f"Promedio: {self.promedio} | Edad: {self.edad} | "
              f"Estado: {self.estado} | RFC: {self.obtener_rfc()}")

def procesar_datos(lista, titulo):
    """
    Función modular para calcular porcentajes y mostrar datos.
    Evita la duplicidad de código (Principio DRY).
    """
    if not lista:
        print(f"\nNo hay registros para {titulo}.")
        return

    total = len(lista)
    aprobados = sum(1 for x in lista if x.promedio >= 6.0)
    reprobados = total - aprobados
    
    frecuencia_estados = {}
    for obj in lista:
        frecuencia_estados[obj.estado] = frecuencia_estados.get(obj.estado, 0) + 1

    print(f"\n{'='*20} REPORTE {titulo} {'='*20}")
    print(f"Total: {total} alumnos")
    print(f"1. Aprobados: {(aprobados/total)*100:.1f}% | Reprobados: {(reprobados/total)*100:.1f}%")
    
    print("2. Distribución Geográfica:")
    for edo, cant in frecuencia_estados.items():
        print(f"   - {edo.title()}: {(cant/total)*100:.1f}%")

    print("3. Listado de Alumnos:")
    for alumno in lista:
        alumno.imprimir_datos()

def main():
    """ Punto de entrada principal del programa """
    hombres = []
    mujeres = []

    try:
        with open('alumnos.csv', mode='r', encoding='utf-8') as file:
            reader = csv.reader(file)
            for row in reader:
                if len(row) < 7: continue 
                estudiante = Alumnos(row[0], row[1], row[2], row[3], row[4], row[5], row[6])
                
                if estudiante.genero == 'H':
                    hombres.append(estudiante)
                else:
                    mujeres.append(estudiante)

        procesar_datos(hombres, "HOMBRES")
        procesar_datos(mujeres, "MUJERES")

    except FileNotFoundError:
        print("Error crítico: El archivo 'alumnos.csv' no existe en el directorio.")

if __name__ == "__main__":
    main()