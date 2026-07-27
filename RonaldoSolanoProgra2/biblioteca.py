'''class Libro:

    def __init__(self, isbn, titulo, autores):
        self.isbn = isbn
        self.titulo = titulo
        self.autores = autores
        self.prestado_a = None
        self.calificaciones = {}

    def prestar(self, usuario):
        if self.prestado_a is None:
            self.prestado_a = usuario
            return True
        return False

    def devolver(self):
        self.prestado_a = None


class Biblioteca:

    def __init__(self):
        self.libros = {}

    def agregar_libro(self, libro):
        self.libros[libro.isbn] = libro

    def prestar_libro(self, isbn, usuario):
        if isbn in self.libros:
            if self.libros[isbn].prestar(usuario):
                print("Libro prestado correctamente")
            else:
                print("El libro ya está prestado")
        else:
            print("Libro no encontrado")

    def devolver_libro(self, isbn):
        if isbn in self.libros:
            self.libros[isbn].devolver()
            print("Libro devuelto")

    def buscar_por_autor(self, autor):
        resultados = []
        for libro in self.libros.values():
            if autor in libro.autores:
                resultados.append(libro.titulo)
        return resultados



biblioteca = Biblioteca()

libro1 = Libro("111", "Python Basico", ["Juan Perez"])
libro2 = Libro("222", "Estructuras de Datos", ["Ana Lopez"])
libro3 = Libro("333", "Programacion Avanzada", ["Juan Perez", "Luis Gomez"])

biblioteca.agregar_libro(libro1)
biblioteca.agregar_libro(libro2)
biblioteca.agregar_libro(libro3)

biblioteca.prestar_libro("111", "Carlos")
biblioteca.devolver_libro("111")

print("\nLibros de Juan Perez:")
print(biblioteca.buscar_por_autor("Juan Perez"))'''