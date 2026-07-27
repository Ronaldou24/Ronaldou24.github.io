from datetime import datetime
from Estructura import ColaPista

def obtener_hora(texto_hora):
    return datetime.strptime(texto_hora.strip(), "%H:%M")

def simular_aeropuerto():
    pistas = [ColaPista() for _ in range(4)]
    vuelos_lista = []

    try:
        with open("despegues.txt", "r") as f:
            for linea in f:
                datos = linea.strip().split(",")
                if len(datos) == 4:
                    vuelos_lista.append((datos[0], datos[1], datos[2], obtener_hora(datos[3])))
    except FileNotFoundError:
        print("Error: No se encontró despegues.txt")
        return

    vuelos_lista.sort(key=lambda x: x[3]) 

    pista_actual = 0
    for v in vuelos_lista:
        pistas[pista_actual].encolar_con_ajuste(v[0], v[1], v[2], v[3])
        pista_actual = (pista_actual + 1) % 4  
 
    print("=== PLAN DE DESPEGUES AJUSTADO ===")
    for i, pista in enumerate(pistas):
        print(f"\n--- PISTA {i+1} ---")
        if pista.esta_vacia():
            print("Sin despegues.")
        while not pista.esta_vacia():
            v = pista.desencolar()
            hora_str = v.horario.strftime("%H:%M")
            print(f"[{hora_str}] Vuelo: {v.identificador} | Destino: {v.destino} | {v.aerolinea}")

if __name__ == "__main__":
    simular_aeropuerto()