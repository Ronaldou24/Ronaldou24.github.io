from flask import Flask, render_template, request, redirect, url_for

app = Flask(__name__)

# Diccionario con tu información para la carga inicial por defecto
perfil_usuario = {
    "nombre": "Ronaldo Gael",
    "apellidos": "Solano Gutierrez",
    "carrera": "Ingeniería en Comunicaciones y Electrónica",
    "email": "ronaldosolano56@gmail.com",
    "telefono": "322 108 5189",
    "institucion": "IPN — ESIME Zacatenco"
}

@app.route('/')
def ver_portafolio():
    # Renderiza tu espectacular diseño inyectando los datos dinámicos
    return render_template('portfolio.html', programador=perfil_usuario)

@app.route('/actualizar', methods=['POST'])
def actualizar_datos():
    # Esta ruta procesará los cambios cuando decidas conectar un formulario de edición
    global perfil_usuario
    perfil_usuario["nombre"] = request.form.get("nombre", perfil_usuario["nombre"])
    perfil_usuario["apellidos"] = request.form.get("apellidos", perfil_usuario["apellidos"])
    perfil_usuario["email"] = request.form.get("email", perfil_usuario["email"])
    perfil_usuario["telefono"] = request.form.get("telefono", perfil_usuario["telefono"])
    
    return redirect(url_for('ver_portafolio'))

if __name__ == '__main__':
    app.run(debug=True, port=5000)


#