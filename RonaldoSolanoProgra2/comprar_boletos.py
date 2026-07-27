'''def simular_ventas():
    eventos = {
        "Evento 1": [100, 0],
        "Evento 2": [200, 0],
        "Evento 3": [300, 0]
    }

    def mostrar_menu():
        print("\n--- SISTEMA DE VENTAS DE BOLETOS ---")
        print("1. Comprar boletos")
        print("2. Mostrar resumen de ventas y total")
        print("3. Salir")
        return input("Seleccione una opción: ")

    while True:
        opcion = mostrar_menu()

        if opcion == "1":
            print("\nEventos disponibles:")
            for nombre, datos in eventos.items():
                print(f"- {nombre}: ${datos[0]}")
            
            nombre_evento = input("Ingrese el nombre del evento (Evento 1/2/3): ").strip()
            
            if nombre_evento in eventos:
                try:
                    cantidad = int(input(f"¿Cuántos boletos desea para {nombre_evento}?: "))
                    if cantidad > 0:
                        eventos[nombre_evento][1] += cantidad
                        print(f"¡Compra exitosa! Se han registrado {cantidad} boletos.")
                    else:
                        print("La cantidad debe ser mayor a cero.")
                except ValueError:
                    print("Error: Por favor ingrese un número entero válido.")
            else:
                print("Evento no encontrado. Verifique que esté escrito correctamente.")

        elif opcion == "2":
            print("\n--- RESUMEN ACTUAL EN DICCIONARIO ---")
            print("Formato: {'Nombre': [Precio Unitario, Boletos Vendidos]}")
            print(eventos)
            
            ganancia_total = 0
            print("\n--- DETALLE DE GANANCIAS ---")
            for nombre, datos in eventos.items():
                subtotal = datos[0] * datos[1]
                ganancia_total += subtotal
                print(f"{nombre}: {datos[1]} boletos vendidos | Subtotal: ${subtotal}")
            
            print("-" * 30)
            print(f"GANANCIA TOTAL: ${ganancia_total}")

        elif opcion == "3":
            print("Saliendo del sistema...")
            break
        else:
            print("Opción no válida.")

if __name__ == "__main__":
    print("Iniciando simulación automática de venta de 5 boletos...")
    
    eventos_simulados = {
        "Evento 1": [100, 2], 
        "Evento 2": [200, 1], 
        "Evento 3": [300, 2]  
    }
    
    total_simulacion = sum(v[0] * v[1] for v in eventos_simulados.values())    
    print("\n--- RESUMEN DE LA SIMULACIÓN (5 Boletos) ---")
    print(f"Diccionario: {eventos_simulados}")
    print(f"Ganancia Total: ${total_simulacion}")''''
    
