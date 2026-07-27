class Producto:
    def __init__(self, nombre, precio, cantidad):
        self.nombre = nombre
        self.precio = precio
        self.cantidad = cantidad

    def __str__(self):
        return f"Producto: {self.nombre} | Precio: ${self.precio:.2f} | Stock: {self.cantidad}"

class Inventario:
    def __init__(self):
        self.productos = []

    def agregar(self, producto: Producto):
        self.productos.append(producto)
        print(f"'{producto.nombre}' agregado con éxito.")

    def buscar_por_nombre(self, nombre_buscado: str):
        for p in self.productos:
            if p.nombre.lower() == nombre_buscado.lower():  
                return p
        return "Producto no encontrado."

    def valor_total(self):
        total = sum(p.precio * p.cantidad for p in self.productos)
        return total

if __name__ == "__main__":
    mi_inventario = Inventario()
    
    mi_inventario.agregar(Producto("Resistor 1k", 0.50, 100))
    mi_inventario.agregar(Producto("Capacitor 10uF", 1.20, 50))
    mi_inventario.agregar(Producto("LED Rojo", 0.15, 200))

    print("Buscando 'LED Rojo':")
    print(mi_inventario.buscar_por_nombre("LED Rojo"))

    print(f"Valor total del inventario: ${mi_inventario.valor_total():.2f}")