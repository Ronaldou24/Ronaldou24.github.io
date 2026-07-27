from Estructura import Pila


impresora1 = Pila()
impresora2 = Pila()
impresora3 = Pila()
impresora4 = Pila()

archivo = open("trabajos.txt", "r")

for linea in archivo:
    datos = linea.strip().split(",")

    identificador = datos[0]
    paginas = int(datos[1])
    prioridad = int(datos[2])
    if prioridad == 3:
        trabajo = f"{identificador} - {paginas} pags - prioridad {prioridad}"

        if paginas < 10:
            impresora1.push(trabajo)

        elif paginas <= 20:
            impresora2.push(trabajo)

        elif paginas <= 50:
            impresora3.push(trabajo)

        else:
            impresora4.push(trabajo)
archivo.seek(0)
for linea in archivo:
    datos = linea.strip().split(",")

    identificador = datos[0]
    paginas = int(datos[1])
    prioridad = int(datos[2])
    if prioridad == 2:
        trabajo = f"{identificador} - {paginas} pags - prioridad {prioridad}"

        if paginas < 10:
            impresora1.push(trabajo)

        elif paginas <= 20:
            impresora2.push(trabajo)

        elif paginas <= 50:
            impresora3.push(trabajo)

        else:
            impresora4.push(trabajo)
archivo.seek(0)
for linea in archivo:
    datos = linea.strip().split(",")

    identificador = datos[0]
    paginas = int(datos[1])
    prioridad = int(datos[2])
    if prioridad == 1:
        trabajo = f"{identificador} - {paginas} pags - prioridad {prioridad}"

        if paginas < 10:
            impresora1.push(trabajo)

        elif paginas <= 20:
            impresora2.push(trabajo)

        elif paginas <= 50:
            impresora3.push(trabajo)

        else:
            impresora4.push(trabajo)
archivo.close()

print("IMPRESORA 1")
while impresora1.top is not None:
    print(impresora1.pop())

print("\nIMPRESORA 2")
while impresora2.top is not None:
    print(impresora2.pop())

print("\nIMPRESORA 3")
while impresora3.top is not None:
    print(impresora3.pop())

print("\nIMPRESORA 4")
while impresora4.top is not None:
    print(impresora4.pop())