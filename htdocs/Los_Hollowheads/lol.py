import FreeCAD as App
import Arch

doc = App.ActiveDocument

if doc is None:
    doc = App.newDocument("CasaPlano")

# Limpia el documento actual para evitar duplicados
for obj in doc.Objects:
    doc.removeObject(obj.Name)

# Parámetros generales
wall_thickness = 0.15  # Grosor muros en metros
wall_height = 3.0      # Altura muros en metros
width = 7.0            # Ancho total de la casa
length = 8.0           # Largo total de la casa

# Función para crear muro con posición y rotación
def create_wall(pos, length, angle):
    wall = Arch.makeWall(length=length, width=wall_thickness, height=wall_height)
    wall.Placement = App.Placement(pos, App.Rotation(App.Vector(0,0,1), angle))
    return wall

# Crear muros exteriores (rectángulo)
muro1 = create_wall(App.Vector(0, 0, 0), width, 0)          # Muro inferior
muro2 = create_wall(App.Vector(width, 0, 0), length, 90)    # Muro derecho
muro3 = create_wall(App.Vector(width, length, 0), width, 180) # Muro superior
muro4 = create_wall(App.Vector(0, length, 0), length, 270)  # Muro izquierdo

# Muros interiores para separar espacios
muro_horizontal = create_wall(App.Vector(0, 4, 0), width, 0)    # Horizontal a 4m
muro_vertical = create_wall(App.Vector(4, 0, 0), 4, 90)         # Vertical entre cuarto ensayo y sala
muro_cocina_bano = create_wall(App.Vector(4, 4, 0), 4, 90)      # Vertical entre cocina y baño

# Función para crear puerta
def create_door(pos, angle):
    door = Arch.Door()
    door.Height = 2.0
    door.Width = 0.8
    door.Placement = App.Placement(pos, App.Rotation(App.Vector(0,0,1), angle))
    return door

# Crear puertas
door1 = create_door(App.Vector(5.0, 0, 0), 0)     # Entrada principal a sala
door2 = create_door(App.Vector(4.0, 6.0, 0), 90)  # Puerta baño
door3 = create_door(App.Vector(4.0, 2.0, 0), 90)  # Puerta cuarto de ensayo
door4 = create_door(App.Vector(2.0, 4.0, 0), 0)   # Puerta cocina

# Ajustar vista 3D
if App.GuiUp:
    App.Gui.activeView().viewIsometric()
    App.Gui.SendMsgToActiveView("ViewFit")

doc.recompute()
