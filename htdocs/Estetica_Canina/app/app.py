from flask import Flask, render_template, request, redirect, send_from_directory
from flaskext.mysql import MySQL
from datetime import datetime


app = Flask(__name__)
mysql = MySQL()
app.config['MYSQL_DATABASE_HOST'] = 'localhost'
app.config['MYSQL_DATABASE_USER'] = 'root'
app.config['MYSQL_DATABASE_PASSWORD'] = 'Quimixto1'
app.config['MYSQL_DATABASE_DB'] = 'agenda_servicios'
mysql.init_app(app)




@app.route('/')
def index():

    return "LOL"


@app.route('/cambio_horario/<int:folio>')
def edit(folio):
    conn = mysql.connect()
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM agenda_servicios WHERE folio = %s;", (folio,))
    cita = cursor.fetchone()
    conn.close()
    return render_template('funciones/cambio_horario.html', cita=cita)

@app.route('/cambio', methods=['POST'])
def cambio():

    _folio = request.form['nfolio']
    _fecha = request.form['nfecha']
    _hora = request.form['nhora']
    _nombre = request.form['nnombre']
    _mascota = request.form['nmascota']
    _tamano = request.form['ntamano']
    _costo = request.form['ncosto']



    conn = mysql.connect()
    cursor = conn.cursor()

   
    sql = "UPDATE agenda_servicios SET folio = %s, fecha = %s, hora = %s, nombre = %s, mascota = %s, tamano = %s, costo = %s WHERE folio = %s;"

    datos = (_folio, _fecha, _hora, _nombre, _mascota, _tamano, _costo, _folio)
    cursor.execute(sql, datos)

    

    conn.commit()
    conn.close()
    return redirect('/')

@app.route('/agendar', methods=['POST'])
def agendar():
    _folio = request.form['nfolio']
    _fecha = request.form['nfecha']
    _hora = request.form['nhora']
    _nombre = request.form['nnombre']
    _mascota = request.form['nmascota']
    _tamano = request.form['ntamano']
    _costo = request.form['ncosto']

 

    sql = "INSERT INTO agenda_servicios(folio, fecha, hora, nombre, mascota, tamano, costo) VALUES(%s, %s, %s, %s, %s, %s, %s);"
    datos = (_folio, _fecha, _hora, _nombre, _mascota, _tamano, _costo)
    conn = mysql.connect()
    cursor = conn.cursor()
    cursor.execute(sql, datos)
    conn.commit()
    conn.close()
    return redirect('/')

@app.route('/borrar/<int:folio>')
def borrar(folio):
    conn = mysql.connect()
    cursor = conn.cursor()

    # Eliminar el registro de la base de datos
    cursor.execute("DELETE FROM agenda_servicios WHERE folio = %s;", (folio,))
    conn.commit()
    conn.close()
    return redirect('/')

@app.route('/agendar', methods=['GET'])
def mostrar_agendar():
    return render_template('funciones/agendar.html')


if __name__ == '__main__':
    app.run(debug=True)