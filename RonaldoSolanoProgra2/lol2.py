import json
datos_recibidos = '{"vendedor": "Iker", ' \
'"juegos": ["Halo", "Zelda", "Halo"], ' \
'"precios": [50, 60]}'

inventario = json.loads(datos_recibidos)
inventario["juegos"] = set(inventario["juegos"])
inventario["juegos"] = inventario.append("Mario")
inventario["precios"] = tuple(inventario["precios"])

resultado1 = inventario.get("vendedor", "anonimo")
resultado2 = inventario.get("ofertas", "No hay")