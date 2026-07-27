from flask import Flask 
from flask import render_template, request, redirect
from flaskext.mysql import MySQL 
from datetime import datetime
import os

app = Flask(__name__)
mysql = MySQL()
app.config['MYSQL_DATABASE_HOST'] = 'localhost'
app.config['MYSQL_DATABASE_USER'] = 'root'
app.config['MYSQL_DATABASE_PASSWORD'] = 'Quimixto1'
app.config['MYSQL_DATABASE_DB'] = 'sistema1'
mysql.init_app(app)
CARPETA = os.path.join('uploads')
app.config['CARPETA'] = CARPETA


@app.route('/') # estableciendo la ruta para index
def index():
    sql = "SELECT * FROM empleados;"
    conn = mysql.connect()
    cursor = conn.cursor()
    cursor.execute(sql)
    empleados = cursor.fetchall()
    conn.commit()

    return render_template('empleados/index.html', empleados = empleados)


app.route('/update/<int:id>')
def edit(id):
    conn = mysql.connect()
    cursor = conn.cursor()
    cursor.execute = ("SELECT * FROM empleados WHERE id = %s;",(id))
    empleado = cursor.fetchone()
    conn.commit()

    return render_template('empleados/edit.html', empleado = empleado)

app.route('/update', methods=['POST'])
def update():
    _nombre = request.form['txtNombre']
    _correo = request.form['txtCorreo']
    _foto   = request.files['txtFoto' ]
    _id = request.form['txtId']


    sql = "update empleados set nombre = %s, correo = %s where id = %s;"
    datos = (_nombre, _correo, _id) 
    conn = mysql.connect()
    cursor = conn.cursor()

    now = datetime()
    tiempo = now.strftime("%Y%H%M%S")
    if _foto.filename != '':
        nuevaFoto = tiempo + "_" + _foto.filename
        _foto.save("uploads/" + nuevaFoto)
    
        sql = "select foto from empleados where id = %s;"
        fila = cursor.fetchone()
        os.remove(os.path.join(app.config['CARPETA'], fila[0]))
        cursor.execute(   )

  
   







    cursor.execute(sql, datos)
    conn.commit()
    return redirect('/')

@app.route('/store', methods=['POST'])
def store():
    _nombre = request.form['txtNombre']
    _correo = request.form['txtCorreo']
    _foto   = request.files['txtFoto']

    now = datetime.now()
    tiempo = now.strftime("%Y%H%M%S")
    if _foto.filename != '':
        nuevoNombreFoto = tiempo + "_" + _foto.filename
        _foto.save("uploads/" + nuevoNombreFoto)
    else:
        nuevoNombreFoto = "foto.jpg"

    sql = "insert into empleados(id, nombre, correo, foto) " \
    "values(null, %s, %s, %s);"
    datos = (_nombre, _correo, nuevoNombreFoto)
    conn = mysql.connect()
    cursor = conn.cursor()

    cursor.execute(sql, datos)
    conn.commit()
    return redirect('/')

@app.route('/destroy/<int:id>')
def destroy(id):
    sql = "delete from empleados where id = %s;"
    datos = (id,)
    conn = mysql.connect()
    cursor = conn.cursor()
    cursor.execute(sql, datos)
    conn.commit()
    return redirect('/')
def method_name():
    pass




@app.route('/create')
def create():
    return render_template('empleados/create.html')


if __name__ == '__main__':
    app.run(debug=True)   # para dejar la app activa