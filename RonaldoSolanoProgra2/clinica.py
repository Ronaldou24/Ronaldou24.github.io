'''import json
import glob
import os

CARPETA = "mascotas"


def mostrar_mascotas():
    archivos = glob.glob(os.path.join(CARPETA, "*.json"))

    for archivo in archivos:
        with open(archivo, "r", encoding="utf-8") as f:
            datos = json.load(f)

        print("\n---------------------------")
        print("ID:", datos["id"])
        print("Nombre:", datos["name"])
        print("Edad:", datos["age"])
        print("Hobbies:", datos["hobbies"])
        print("Vacunas:", datos["vaccines"])

        print("Propietarios:")
        for owner in datos["owners"]:
            print("-", owner["namef"], "| Tel:", owner["number"])


def actualizar_vacuna():
    nombre = input("Nombre de la mascota: ")

    archivos = glob.glob(os.path.join(CARPETA, "*.json"))

    for archivo in archivos:
        with open(archivo, "r", encoding="utf-8") as f:
            datos = json.load(f)

        if datos["name"].lower() == nombre.lower():

            print("Vacunas actuales:", datos["vaccines"])

            vacuna = input("Nombre de la vacuna: ")
            estado = input("¿Aplicada? (true/false): ").lower()

            if estado == "true":
                datos["vaccines"][vacuna] = True
            else:
                datos["vaccines"][vacuna] = False

            with open(archivo, "w", encoding="utf-8") as f:
                json.dump(datos, f, indent=4)

            print("Vacuna actualizada")
            return

    print("Mascota no encontrada")


def modificar_propietario():
    nombre = input("Nombre de la mascota: ")

    archivos = glob.glob(os.path.join(CARPETA, "*.json"))

    for archivo in archivos:
        with open(archivo, "r", encoding="utf-8") as f:
            datos = json.load(f)

        if datos["name"].lower() == nombre.lower():

            print("1) Agregar propietario")
            print("2) Eliminar propietario")

            opcion = input("Seleccione opción: ")

            if opcion == "1":

                nombrep = input("Nombre del propietario: ")
                numero = int(input("Número de teléfono: "))

                nuevo = {
                    "namef": nombrep,
                    "number": numero
                }

                datos["owners"].append(nuevo)

            elif opcion == "2":

                eliminar = input("Nombre del propietario a eliminar: ")

                datos["owners"] = [
                    o for o in datos["owners"] if o["namef"] != eliminar
                ]

            with open(archivo, "w", encoding="utf-8") as f:
                json.dump(datos, f, indent=4)

            print("Propietario actualizado")
            return

    print("Mascota no encontrada")


def agregar_mascota():

    os.makedirs(CARPETA, exist_ok=True)

    idm = input("ID mascota: ")
    nombre = input("Nombre: ")
    edad = int(input("Edad: "))
    es_perro = input("¿Es perro? (true/false): ").lower() == "true"

    colonia = input("Colonia: ")
    ciudad = input("Ciudad: ")

    propietario = input("Nombre propietario: ")
    telefono = int(input("Teléfono: "))

    datos = {
        "id": idm,
        "name": nombre,
        "isDog": es_perro,
        "hobbies": [],
        "age": edad,
        "address": {
            "work": None,
            "home": [colonia, ciudad]
        },
        "vaccines": {},
        "owners": [
            {
                "namef": propietario,
                "number": telefono
            }
        ]
    }

    archivo = os.path.join(CARPETA, idm + ".json")

    with open(archivo, "w", encoding="utf-8") as f:
        json.dump(datos, f, indent=4)

    print("Mascota agregada correctamente")


def menu():

    while True:

        print("\n--- CLINICA VETERINARIA ---")
        print("A) Mostrar mascotas")
        print("B) Actualizar vacuna")
        print("C) Modificar propietarios")
        print("D) Agregar mascota")
        print("E) Salir")

        opcion = input("Seleccione opción: ").upper()

        if opcion == "A":
            mostrar_mascotas()

        elif opcion == "B":
            actualizar_vacuna()

        elif opcion == "C":
            modificar_propietario()

        elif opcion == "D":
            agregar_mascota()

        elif opcion == "E":
            print("Saliendo...")
            break

        else:
            print("Opción inválida")


if __name__ == "__main__":
    menu()'''