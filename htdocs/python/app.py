from flask import Flask
from flask import render_template, request
from flaskext.mysql import MySQL
from datetime import datetime


app = Flask(__name__)

mysql = MySQL()
app.config['MYSQL_DATABASE_HOST'] = 'localhost'
app.config['MYSQL_DATABASE_USER'] = 'root'
app.config['MYSQL_DATABASE_PASSWORD'] = 'Quimixto1'
app.config['MYSQL_DATABASE_DB'] = 'sistema1'
mysql.init_app(app)

@app.route('/')
def index():
    sql = "SELECT * FROM sistema1.empleados;"
    conn = mysql.connect()
    cursor = conn.cursor()
    cursor.execute(sql)
    empleados = cursor.fetchall()
    conn.commit()
    return render_template('empleados/index.html', empleados = empleados)

@app.route('/store', methods=['POST'])
def store():
    _nombre = request.form['txtnombre']
    _correo = request.form['txtcorreo']
    _foto = request.files['txtfoto']

    now = datetime.now()
    tiempo = now.strftime("%Y-%m-%d %H:%M:%S")
    if _foto.filename != '':
        nuevoNombreVFoto = tiempo + "_" + _foto.filename
        _foto.save("unploads/" + nuevoNombreVFoto)
    else:
        nuevoNombreVFoto = "foto.jpg"



    sql = "insert into sistema1.empleados(id,nombre,correo,foto) Values(null,%s,%s,%s);"
    datos = (_nombre, _correo, _foto.filename)
    conn = mysql.connect()
    cursor = conn.cursor()  

    cursor.execute(sql, datos)
    conn.commit()
    return render_template('empleados/index.html')

@app.route('/create')
def create():
    return render_template('empleados/create.html')



if __name__ == '__main__':
    app.run(debug=True)