import random

class Sensor:
    def __init__(self, sensor_id):
        self.id = sensor_id
        self.eventos_detectados = set()
        self.conteo_errores = {}
        self.total_eventos_sensor = 0

    def agregar_evento(self, evento_str):
        if evento_str not in self.eventos_detectados:
            self.eventos_detectados.add(evento_str)
            self.total_eventos_sensor += 1
            
            tipo_error = evento_str[2:]
            
            self.conteo_errores[tipo_error] = self.conteo_errores.get(tipo_error, 0) + 1
            
            self.generar_alertas(tipo_error)
            return True
        return False

    def generar_alertas(self, tipo_error_actual):
    
        if self.total_eventos_sensor == 6:
            print(f"[ALERTA CRÍTICA] Sensor {self.id:02d}: Ha superado el límite de 5 eventos totales.")

        if self.conteo_errores[tipo_error_actual] % 5 == 0:
            print(f"[ALERTA ERROR] Sensor {self.id:02d}: Tipo de error {tipo_error_actual} alcanzó {self.conteo_errores[tipo_error_actual]} ocurrencias.")

if __name__ == "__main__":
    sensores = {i: Sensor(i) for i in range(13)}
    
    print(f"--- Iniciando simulación de 50 eventos ---")
    
    for i in range(50):
        id_random = random.randint(0, 12)
        error_random = random.randint(0, 5)
        
        evento = f"{id_random:02d}{error_random:02d}"
        
        sensores[id_random].agregar_evento(evento)

    print(f"\n--- Resumen de Sensores con actividad ---")
    for s in sensores.values():
        if s.total_eventos_sensor > 0:
            print(f"Sensor {s.id:02d}: {s.total_eventos_sensor} eventos únicos. Errores: {s.conteo_errores}")