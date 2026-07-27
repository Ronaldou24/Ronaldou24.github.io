class Usuario:
    def __init__(self, nombre):
        self.nombre = nombre
        self.amigos = set()  # para que no haya amigos repetidos
        self.grupo = None

    def agregar_amigo(self, otro_usuario):
        self.amigos.add(otro_usuario)
        otro_usuario.amigos.add(self) # La amistad es mutua

    def eliminar_amigo(self, otro_usuario):
        if otro_usuario in self.amigos:
            self.amigos.remove(otro_usuario)
            otro_usuario.amigos.remove(self)

    def unirse_a_grupo(self, nombre_grupo):
        self.grupo = nombre_grupo

    def son_amigos(self, otro_usuario):
        return otro_usuario in self.amigos

    def __repr__(self):
        return self.nombre



u1 = Usuario("Alicia")
u2 = Usuario("Roberto")
u3 = Usuario("Carla")

u1.agregar_amigo(u2)
print(f"Amigos de Alicia: {u1.amigos}")

print(f"¿Son amigos?: {u1.son_amigos(u2)}")

u1.unirse_a_grupo("Programadores")
print(f"Grupo de Alicia: {u1.grupo}")

u3.son_amigos(u1)  # Carla no es amiga de Alicia
print(f"¿Son amigos?: {u3.son_amigos(u1)}")