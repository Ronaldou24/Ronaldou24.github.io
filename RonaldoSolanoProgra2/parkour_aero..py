from ursina import *
from ursina.prefabs.first_person_controller import FirstPersonController
import math, random

app = Ursina(title='PARKOUR AERO', borderless=False, fullscreen=False)
window.size = (1280, 720)
window.color = color.rgb(135, 206, 235)

# ══════════════════════════════════════════════
#  PALETA FRUTIGER AERO
# ══════════════════════════════════════════════
AERO_SKY       = color.rgb(100, 180, 240)
AERO_AQUA      = color.rgb(64,  196, 198)
AERO_LIME      = color.rgb(120, 220,  80)
AERO_WHITE     = color.rgb(230, 245, 255)
AERO_GLASS     = color.rgba(180, 230, 255, 120)
AERO_BLUE      = color.rgb( 50, 130, 220)
AERO_TEAL      = color.rgb( 30, 170, 160)
AERO_PINK      = color.rgb(240, 130, 180)
AERO_ORANGE    = color.rgb(255, 170,  60)
AERO_DARK      = color.rgb( 20,  60,  90)
AERO_PLATFORM  = color.rgb( 80, 200, 210)
AERO_BOUNCE    = color.rgb(255, 220,  50)
AERO_ICE       = color.rgb(190, 235, 255)
AERO_LAVA      = color.rgb(255,  90,  40)

# ══════════════════════════════════════════════
#  ESTADO DEL JUEGO
# ══════════════════════════════════════════════
estado = {
    'pantalla': 'menu',
    'nivel': 1,
    'max_nivel': 5,
    'tiempo': 0.0,
    'mejor_tiempo': [None]*6,   # índice 1-5
    'musica_vol': 0.5,
    'sfx_vol': 0.5,
}

# ══════════════════════════════════════════════
#  AUDIO
# ══════════════════════════════════════════════
try:
    musica = Audio('fondoMenu.wav', loop=True, autoplay=True, volume=0.5)
except:
    musica = None

try:
    sfx_salto = Audio('salto.wav', autoplay=False, volume=0.5)
except:
    sfx_salto = None

def play_sfx(sfx):
    if sfx:
        sfx.volume = estado['sfx_vol']
        sfx.play()

# ══════════════════════════════════════════════
#  DEFINICIÓN DE 5 NIVELES
# ══════════════════════════════════════════════
# Cada plataforma: (x, y, z, sx, sy, sz, color, tipo)
# tipos: 'normal' | 'bounce' | 'ice' | 'lava' | 'moving'
NIVELES = {
    1: {
        'nombre': 'Jardín Flotante',
        'descripcion': 'Plataformas sobre el cielo cristalino',
        'color_cielo': color.rgb(135, 206, 235),
        'spawn': (0, 2, 0),
        'meta':  (50, 12, 0),
        'plataformas': [
            ( 0,  0,  0,  6, 1,  6, AERO_LIME,  'normal'),
            ( 8,  2,  0,  4, 1,  4, AERO_AQUA,  'normal'),
            (15,  4,  2,  4, 1,  4, AERO_BLUE,  'normal'),
            (22,  6, -1,  3, 1,  3, AERO_WHITE, 'normal'),
            (28,  8,  2,  5, 1,  3, AERO_TEAL,  'normal'),
            (35,  9,  0,  3, 1,  3, AERO_LIME,  'bounce'),
            (42, 12,  0,  6, 1,  6, AERO_AQUA,  'normal'),
            (50, 12,  0,  6, 1,  6, AERO_LIME,  'normal'),
        ],
    },
    2: {
        'nombre': 'Glaciar Digital',
        'descripcion': 'Hielo resbaladizo sobre el vacío',
        'color_cielo': color.rgb(170, 210, 240),
        'spawn': (0, 2, 0),
        'meta':  (55, 18, 0),
        'plataformas': [
            ( 0,  0,  0,  6, 1,  6, AERO_ICE,   'normal'),
            ( 9,  3,  1,  3, 1,  3, AERO_ICE,   'ice'),
            (17,  5, -1,  3, 1,  3, AERO_ICE,   'ice'),
            (23,  7,  2,  4, 1,  4, AERO_BLUE,  'normal'),
            (30,  9,  0,  3, 1,  3, AERO_ICE,   'ice'),
            (36, 11, -2,  3, 1,  3, AERO_WHITE, 'normal'),
            (43, 14,  1,  4, 1,  4, AERO_AQUA,  'bounce'),
            (55, 18,  0,  6, 1,  6, AERO_ICE,   'normal'),
        ],
    },
    3: {
        'nombre': 'Volcán Neón',
        'descripcion': '¡Cuidado con las plataformas de lava!',
        'color_cielo': color.rgb(60, 30, 60),
        'spawn': (0, 2, 0),
        'meta':  (60, 20, 0),
        'plataformas': [
            ( 0,  0,  0,  6, 1,  6, AERO_LIME,   'normal'),
            ( 8,  2,  0,  3, 1,  3, AERO_ORANGE, 'normal'),
            (15,  3,  2,  3, 1,  3, AERO_LAVA,   'lava'),
            (21,  5,  0,  4, 1,  4, AERO_ORANGE, 'normal'),
            (28,  7, -1,  3, 1,  3, AERO_LAVA,   'lava'),
            (34,  9,  1,  3, 1,  3, AERO_PINK,   'bounce'),
            (41, 12, -1,  4, 1,  4, AERO_ORANGE, 'normal'),
            (48, 15,  0,  3, 1,  3, AERO_LAVA,   'lava'),
            (55, 17,  2,  3, 1,  3, AERO_ORANGE, 'normal'),
            (60, 20,  0,  6, 1,  6, AERO_LIME,   'normal'),
        ],
    },
    4: {
        'nombre': 'Bosque Bioluminiscente',
        'descripcion': 'Plataformas que se mueven entre los árboles',
        'color_cielo': color.rgb(20, 60, 40),
        'spawn': (0, 2, 0),
        'meta':  (65, 22, 0),
        'plataformas': [
            ( 0,  0,  0,  6, 1,  6, AERO_LIME, 'normal'),
            ( 9,  3,  0,  3, 1,  3, AERO_TEAL, 'moving'),
            (18,  5,  2,  3, 1,  3, AERO_AQUA, 'normal'),
            (25,  7,  0,  3, 1,  3, AERO_TEAL, 'moving'),
            (33,  9, -2,  4, 1,  4, AERO_LIME, 'normal'),
            (40, 12,  0,  3, 1,  3, AERO_TEAL, 'moving'),
            (48, 14,  1,  3, 1,  3, AERO_AQUA, 'bounce'),
            (55, 17, -1,  4, 1,  4, AERO_LIME, 'normal'),
            (65, 22,  0,  6, 1,  6, AERO_TEAL, 'normal'),
        ],
    },
    5: {
        'nombre': 'Cima del Paraíso',
        'descripcion': '¡El desafío final! Combina todo lo aprendido',
        'color_cielo': color.rgb(255, 180, 220),
        'spawn': (0, 2, 0),
        'meta':  (70, 28, 0),
        'plataformas': [
            ( 0,  0,  0,  6, 1,  6, AERO_PINK,   'normal'),
            ( 9,  3,  1,  3, 1,  3, AERO_ICE,    'ice'),
            (16,  5, -1,  3, 1,  3, AERO_TEAL,   'moving'),
            (23,  7,  0,  3, 1,  3, AERO_LAVA,   'lava'),
            (30,  9,  2,  3, 1,  3, AERO_WHITE,  'bounce'),
            (37, 13,  0,  3, 1,  3, AERO_ICE,    'ice'),
            (44, 15, -1,  3, 1,  3, AERO_TEAL,   'moving'),
            (51, 18,  1,  3, 1,  3, AERO_ORANGE, 'normal'),
            (58, 20,  0,  3, 1,  3, AERO_LAVA,   'lava'),
            (64, 24, -1,  3, 1,  3, AERO_PINK,   'bounce'),
            (70, 28,  0,  6, 1,  6, AERO_WHITE,  'normal'),
        ],
    },
}

# ══════════════════════════════════════════════
#  MUNDO / ENTIDADES
# ══════════════════════════════════════════════
plataformas_entidades = []
particulas = []
meta_entity = None
player = None

def limpiar_mundo():
    global plataformas_entidades, particulas, meta_entity
    for e in plataformas_entidades: destroy(e)
    for p in particulas: destroy(p)
    if meta_entity: destroy(meta_entity)
    plataformas_entidades = []
    particulas = []
    meta_entity = None

def crear_mundo(nivel_num):
    global plataformas_entidades, meta_entity, player
    limpiar_mundo()
    datos = NIVELES[nivel_num]
    window.color = datos['color_cielo']

    for (x, y, z, sx, sy, sz, col, tipo) in datos['plataformas']:
        e = Entity(model='cube', position=(x, y, z),
                   scale=(sx, sy, sz), color=col,
                   collider='box', double_sided=True)
        e.tipo    = tipo
        e.base_x  = x
        e.fase    = random.uniform(0, math.pi*2)
        if tipo == 'bounce':
            e.color = AERO_BOUNCE
            Entity(parent=e, model='cube', scale=(0.8, 0.1, 0.8),
                   y=0.55, color=color.yellow, collider=None)
        elif tipo == 'lava':
            e.color = AERO_LAVA
        elif tipo == 'ice':
            e.color = AERO_ICE
        plataformas_entidades.append(e)

    mx, my, mz = datos['meta']
    meta_entity = Entity(model='cube',
                         position=(mx, my+1.5, mz),
                         scale=(1.5, 3, 1.5),
                         color=AERO_AQUA, collider='box')
    Entity(parent=meta_entity, model='cube',
           scale=(1.2, 0.15, 1.2), y=1.6,
           color=AERO_WHITE, collider=None)

    if player:
        sx2, sy2, sz2 = datos['spawn']
        player.position = Vec3(sx2, sy2+2, sz2)
        try: player.velocity = Vec3(0,0,0)
        except: pass

def agregar_particulas(pos, col=AERO_AQUA, cantidad=8):
    for _ in range(cantidad):
        p = Entity(model='cube',
                   position=pos + Vec3(random.uniform(-1,1),
                                       random.uniform(0,1),
                                       random.uniform(-1,1)),
                   scale=0.2, color=col, collider=None)
        p.vida = random.uniform(0.3, 0.8)
        p.vel  = Vec3(random.uniform(-2,2),
                      random.uniform(2,5),
                      random.uniform(-2,2))
        particulas.append(p)

# ══════════════════════════════════════════════
#  JUGADOR
# ══════════════════════════════════════════════
def crear_jugador():
    global player
    if player: destroy(player)
    player = FirstPersonController(
        model='cube', color=AERO_BLUE,
        scale=(0.7,1.8,0.7), position=Vec3(0,4,0),
        speed=10, jump_height=4, jump_duration=0.4,
        enabled=False, gravity=1,
    )
    player.cursor.color = color.clear

crear_jugador()

# ══════════════════════════════════════════════
#  HUD
# ══════════════════════════════════════════════
hud = Entity(parent=camera.ui, enabled=False)
Entity(parent=hud, model='quad', scale=(2, 0.08),
       position=(0, 0.47), color=color.rgba(20,60,90,160))
txt_nivel        = Text(parent=hud, text='Nivel 1', scale=1.4,
                        position=(-0.85, 0.44), color=AERO_WHITE, origin=(-0.5,0))
txt_tiempo       = Text(parent=hud, text='00:00', scale=1.4,
                        position=(0, 0.44), color=AERO_AQUA, origin=(0,0))
txt_nombre_nivel = Text(parent=hud, text='', scale=1.1,
                        position=(0.85, 0.44), color=AERO_LIME, origin=(0.5,0))
crosshair = Entity(parent=camera.ui, model='quad',
                   scale=0.012, color=AERO_WHITE, enabled=False)

# ══════════════════════════════════════════════
#  HELPERS UI
# ══════════════════════════════════════════════
screens = {}

def ocultar_todas():
    for s in screens.values(): s.disable()
    hud.disable()
    crosshair.disable()

def crear_boton_aero(texto, parent, y, on_click,
                     col=None, scale=(0.28,0.072)):
    col = col or AERO_AQUA
    btn = Button(text=texto, parent=parent, scale=scale, y=y,
                 color=col, highlight_color=color.rgba(255,255,255,60),
                 pressed_color=AERO_TEAL, radius=0.1, on_click=on_click)
    btn.text_entity.color = AERO_DARK
    btn.text_entity.scale *= 1.2
    return btn

def crear_panel(parent, y=0, scale=(0.7,0.85),
                col=None, z=0.05):
    col = col or color.rgba(200,235,255,180)
    return Entity(parent=parent, model='quad',
                  scale=scale, y=y, color=col, z=z)

# ══════════════════════════════════════════════
#  MENÚ PRINCIPAL
# ══════════════════════════════════════════════
menu_principal = Entity(parent=camera.ui, enabled=True)
screens['menu'] = menu_principal

Entity(parent=menu_principal, model='quad', scale=(2.2,1.3),
       color=color.rgb(80,160,220), z=0.2)
Entity(parent=menu_principal, model='quad', scale=(2.2,0.65),
       y=-0.35, color=color.rgb(50,120,60), z=0.2)
for cx,cy in [(-0.5,0.35),(0.2,0.3),(0.7,0.38),(-0.8,0.25)]:
    Entity(parent=menu_principal, model='quad', scale=(0.25,0.1),
           position=(cx,cy), color=color.rgba(255,255,255,200), z=0.19)
crear_panel(menu_principal, z=0.1)

Text(parent=menu_principal, text='PARKOUR', scale=8, origin=(0,0),
     y=0.32, color=AERO_WHITE, z=0.08)
Text(parent=menu_principal, text='A E R O', scale=3, origin=(0,0),
     y=0.22, color=AERO_AQUA, z=0.08)

def ir_jugar():         iniciar_nivel(estado['nivel'])
def ir_niveles():
    estado['pantalla'] = 'niveles'
    ocultar_todas(); screens['niveles'].enable()
def ir_config_menu():
    estado['pantalla'] = 'config'
    ocultar_todas(); screens['config'].enable()

crear_boton_aero('▶  JUGAR',      menu_principal, y= 0.05, on_click=ir_jugar,      col=AERO_LIME)
crear_boton_aero('🗺  NIVELES',   menu_principal, y=-0.06, on_click=ir_niveles,     col=AERO_AQUA)
crear_boton_aero('⚙  AJUSTES',   menu_principal, y=-0.17, on_click=ir_config_menu, col=AERO_BLUE)
crear_boton_aero('✕  SALIR',      menu_principal, y=-0.28, on_click=application.quit, col=AERO_PINK)
Text(parent=menu_principal, text='v1.0  •  Frutiger Aero Edition',
     scale=0.9, origin=(0,0), y=-0.43, color=color.rgba(255,255,255,150), z=0.08)

# ══════════════════════════════════════════════
#  SELECCIÓN DE NIVELES
# ══════════════════════════════════════════════
menu_niveles = Entity(parent=camera.ui, enabled=False)
screens['niveles'] = menu_niveles

Entity(parent=menu_niveles, model='quad', scale=(2.2,1.3),
       color=color.rgb(40,100,60), z=0.2)
crear_panel(menu_niveles, z=0.1, scale=(0.9,0.92))
Text(parent=menu_niveles, text='SELECCIONAR NIVEL',
     scale=3.5, origin=(0,0), y=0.37, color=AERO_WHITE, z=0.08)

COLS_NIVEL = [AERO_LIME, AERO_ICE, AERO_ORANGE, AERO_TEAL, AERO_PINK]
POS_Y      = [0.22, 0.10, -0.02, -0.14, -0.26]

def sel_nivel(n):
    def fn(): iniciar_nivel(n)
    return fn

for i in range(1,6):
    dat  = NIVELES[i]
    col_b = COLS_NIVEL[i-1]
    yp   = POS_Y[i-1]
    r,g,b,_ = col_b.rgba
    Entity(parent=menu_niveles, model='quad', scale=(0.75,0.095),
           y=yp, color=color.rgba(int(r*255),int(g*255),int(b*255),120), z=0.09)
    Text(parent=menu_niveles, text=f'NIVEL {i} — {dat["nombre"]}',
         scale=1.5, origin=(-0.5,0), position=(-0.34, yp+0.012),
         color=AERO_DARK, z=0.08)
    Text(parent=menu_niveles, text=dat['descripcion'],
         scale=0.9, origin=(-0.5,0), position=(-0.34, yp-0.02),
         color=color.rgba(20,50,80,200), z=0.08)
    crear_boton_aero('JUGAR', menu_niveles, y=yp,
                     on_click=sel_nivel(i), col=col_b, scale=(0.1,0.06))

def volver_menu():
    estado['pantalla'] = 'menu'
    ocultar_todas(); screens['menu'].enable()

crear_boton_aero('← MENÚ', menu_niveles, y=-0.40,
                 on_click=volver_menu, col=AERO_BLUE, scale=(0.2,0.065))

# ══════════════════════════════════════════════
#  CONFIG
# ══════════════════════════════════════════════
menu_config = Entity(parent=camera.ui, enabled=False)
screens['config'] = menu_config

Entity(parent=menu_config, model='quad', scale=(2.2,1.3),
       color=color.rgb(30,60,110), z=0.2)
crear_panel(menu_config, z=0.1, scale=(0.65,0.82))
Text(parent=menu_config, text='AJUSTES', scale=4, origin=(0,0),
     y=0.32, color=AERO_WHITE, z=0.08)

Text(parent=menu_config, text='🎵  Volumen Música', scale=1.4, origin=(-0.5,0),
     position=(-0.28, 0.17), color=AERO_AQUA, z=0.08)
slider_musica = ThinSlider(min=0, max=1, default=0.5,
                           position=(-0.28,0.09), scale=(0.56,1),
                           parent=menu_config)

Text(parent=menu_config, text='🔊  Volumen Efectos', scale=1.4, origin=(-0.5,0),
     position=(-0.28, 0.0), color=AERO_AQUA, z=0.08)
slider_sfx = ThinSlider(min=0, max=1, default=0.5,
                        position=(-0.28,-0.08), scale=(0.56,1),
                        parent=menu_config)

def guardar_config():
    estado['musica_vol'] = slider_musica.value
    estado['sfx_vol']    = slider_sfx.value
    if musica: musica.volume = estado['musica_vol']
    volver_menu()

crear_boton_aero('← VOLVER', menu_config, y=-0.24,
                 on_click=guardar_config, col=AERO_BLUE)

# ══════════════════════════════════════════════
#  PAUSA
# ══════════════════════════════════════════════
menu_pausa = Entity(parent=camera.ui, enabled=False)
screens['pausa'] = menu_pausa

Entity(parent=menu_pausa, model='quad', scale=(2.2,1.3),
       color=color.rgba(10,30,60,200), z=0.2)
crear_panel(menu_pausa, z=0.1, scale=(0.55,0.75))
Text(parent=menu_pausa, text='PAUSA', scale=5, origin=(0,0),
     y=0.30, color=AERO_WHITE, z=0.08)

def reanudar():
    estado['pantalla'] = 'jugando'
    ocultar_todas()
    hud.enable(); crosshair.enable()
    mouse.locked = True
    player.enabled = True

def ir_menu_pausa():
    estado['pantalla'] = 'menu'
    mouse.locked = False; player.enabled = False
    ocultar_todas(); screens['menu'].enable()

def reiniciar_nivel():
    iniciar_nivel(estado['nivel'])

crear_boton_aero('▶  REANUDAR',   menu_pausa, y= 0.10, on_click=reanudar,       col=AERO_LIME)
crear_boton_aero('🔄  REINICIAR', menu_pausa, y=-0.02, on_click=reiniciar_nivel, col=AERO_AQUA)
crear_boton_aero('🏠  MENÚ',      menu_pausa, y=-0.14, on_click=ir_menu_pausa,   col=AERO_BLUE)

# ══════════════════════════════════════════════
#  VICTORIA
# ══════════════════════════════════════════════
menu_victoria = Entity(parent=camera.ui, enabled=False)
screens['victoria'] = menu_victoria

Entity(parent=menu_victoria, model='quad', scale=(2.2,1.3),
       color=color.rgb(20,120,80), z=0.2)
crear_panel(menu_victoria, z=0.1, scale=(0.65,0.82),
            col=color.rgba(180,255,220,200))
Text(parent=menu_victoria, text='¡NIVEL SUPERADO!', scale=3.5, origin=(0,0),
     y=0.32, color=AERO_DARK, z=0.08)

txt_tiempo_final = Text(parent=menu_victoria, text='', scale=2, origin=(0,0),
                        y=0.16, color=AERO_DARK, z=0.08)
txt_mejor_tiempo = Text(parent=menu_victoria, text='', scale=1.5, origin=(0,0),
                        y=0.05, color=AERO_TEAL, z=0.08)

def sig_nivel():
    n = estado['nivel'] + 1
    if n <= estado['max_nivel']: iniciar_nivel(n)
    else: ir_menu_pausa()

crear_boton_aero('▶  SIGUIENTE',   menu_victoria, y=-0.08, on_click=sig_nivel,       col=AERO_LIME)
crear_boton_aero('🔄  REINTENTAR', menu_victoria, y=-0.20, on_click=reiniciar_nivel,  col=AERO_AQUA)
crear_boton_aero('🏠  MENÚ',       menu_victoria, y=-0.32, on_click=ir_menu_pausa,    col=AERO_BLUE)

# ══════════════════════════════════════════════
#  DERROTA
# ══════════════════════════════════════════════
menu_derrota = Entity(parent=camera.ui, enabled=False)
screens['derrota'] = menu_derrota

Entity(parent=menu_derrota, model='quad', scale=(2.2,1.3),
       color=color.rgb(90,20,20), z=0.2)
crear_panel(menu_derrota, z=0.1, scale=(0.55,0.60),
            col=color.rgba(255,200,200,200))
Text(parent=menu_derrota, text='¡CAÍSTE!', scale=5, origin=(0,0),
     y=0.22, color=AERO_LAVA, z=0.08)
Text(parent=menu_derrota, text='Inténtalo de nuevo', scale=1.5, origin=(0,0),
     y=0.09, color=AERO_DARK, z=0.08)

crear_boton_aero('🔄  REINTENTAR', menu_derrota, y=-0.04, on_click=reiniciar_nivel,  col=AERO_ORANGE)
crear_boton_aero('🏠  MENÚ',       menu_derrota, y=-0.16, on_click=ir_menu_pausa,    col=AERO_BLUE)

# ══════════════════════════════════════════════
#  INICIAR NIVEL
# ══════════════════════════════════════════════
def fmt_tiempo(t):
    return f'{int(t)//60:02d}:{int(t)%60:02d}'

def iniciar_nivel(n):
    estado['nivel']    = n
    estado['tiempo']   = 0.0
    estado['pantalla'] = 'jugando'
    ocultar_todas()
    crear_mundo(n)
    datos = NIVELES[n]
    sx, sy, sz = datos['spawn']
    player.position = Vec3(sx, sy+2, sz)
    try: player.velocity = Vec3(0,0,0)
    except: pass
    player.enabled = True
    mouse.locked   = True
    hud.enable(); crosshair.enable()
    txt_nivel.text        = f'Nivel {n}'
    txt_nombre_nivel.text = datos['nombre']

# ══════════════════════════════════════════════
#  UPDATE
# ══════════════════════════════════════════════
def update():
    if musica: musica.volume = slider_musica.value
    estado['musica_vol'] = slider_musica.value
    estado['sfx_vol']    = slider_sfx.value

    if estado['pantalla'] != 'jugando' or not player or not player.enabled:
        return

    estado['tiempo'] += time.dt
    txt_tiempo.text = fmt_tiempo(estado['tiempo'])

    # Salto con SFX
    if held_keys['space'] and player.grounded:
        play_sfx(sfx_salto)

    # Tipos de plataforma
    for e in plataformas_entidades:
        if not hasattr(e, 'tipo'): continue
        dist = distance(player.position, e.position)
        if dist < 5 and player.grounded:
            if e.tipo == 'lava':
                _mostrar_derrota(); return
            elif e.tipo == 'bounce' and held_keys['space']:
                try: player.jump(); player.jump()
                except: pass

    # Plataformas móviles
    t = time.time
    for e in plataformas_entidades:
        if hasattr(e,'tipo') and e.tipo == 'moving':
            e.x = e.base_x + math.sin(t*1.5 + e.fase) * 3

    # Partículas
    muertas = []
    for p in particulas:
        p.vida -= time.dt
        p.position += p.vel * time.dt
        p.scale = max(0, p.vida * 0.3)
        if p.vida <= 0:
            destroy(p); muertas.append(p)
    for m in muertas: particulas.remove(m)

    # Caída al vacío
    if player.y < -15:
        _mostrar_derrota(); return

    # Meta
    if meta_entity and distance(player.position, meta_entity.position) < 3:
        _mostrar_victoria()

def _mostrar_victoria():
    t = estado['tiempo']
    n = estado['nivel']
    if estado['mejor_tiempo'][n] is None or t < estado['mejor_tiempo'][n]:
        estado['mejor_tiempo'][n] = t
    txt_tiempo_final.text = f'Tiempo: {fmt_tiempo(t)}'
    txt_mejor_tiempo.text = f'Mejor: {fmt_tiempo(estado["mejor_tiempo"][n])}'
    agregar_particulas(player.position, AERO_AQUA, 20)
    estado['pantalla'] = 'victoria'
    mouse.locked = False; player.enabled = False
    ocultar_todas(); screens['victoria'].enable()

def _mostrar_derrota():
    estado['pantalla'] = 'derrota'
    mouse.locked = False; player.enabled = False
    ocultar_todas(); screens['derrota'].enable()

# ══════════════════════════════════════════════
#  INPUT GLOBAL
# ══════════════════════════════════════════════
def input(key):
    if key == 'escape':
        if estado['pantalla'] == 'jugando':
            estado['pantalla'] = 'pausa'
            mouse.locked = False; player.enabled = False
            ocultar_todas(); screens['pausa'].enable()
        elif estado['pantalla'] == 'pausa':
            reanudar()

Sky()
camera.position = (0, 5, -15)
camera.rotation_x = 10
app.run()