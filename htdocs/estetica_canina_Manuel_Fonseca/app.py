from flask import Flask, render_template, request, url_for, redirect, flash
from flaskext.mysql import MySQL

app = Flask(__name__)
app.secret_key = 'Programacion'

mysql = MySQL()
app.config['MYSQL_DATABASE_HOST'] = 'localhost'
app.config['MYSQL_DATABASE_USER'] = 'root'
app.config['MYSQL_DATABASE_PASSWORD'] = 'Programacion'
app.config['MYSQL_DATABASE_DB'] = 'agenda_servicios'
mysql.init_app(app)

@app.route('/')
def index():
   
    try:
        conn = mysql.connect()
        cur = conn.cursor()
        cur.execute('SELECT * FROM agenda_servicios.agendar ORDER BY fecha, hora')
        citas = cur.fetchall()
        cur.close()
        conn.close()
        return render_template('index.html', citas=citas)
    except Exception as e:
        print(f"Error al cargar citas: {e}")
        return render_template('index.html', citas=[])

@app.route('/agendar', methods=['POST'])
def agendar():
    if request.method == 'POST':
        folio = request.form.get('folio', '')
        mascota = request.form.get('mascota', '')
        fecha = request.form.get('fecha', '')
        hora = request.form.get('hora', '')
        servicio = request.form.get('servicio', '')
        tamano = request.form.get('tamano', '')
        costo = request.form.get('costo', 0)
        
        if not all([folio, mascota, fecha, hora, servicio, tamano, costo]):
            flash('Todos los campos son requeridos', 'error')
            return redirect(url_for('index'))
        
        try:
            conn = mysql.connect()
            cur = conn.cursor()
            cur.execute('INSERT INTO agenda_servicios.agendar (folio, mascota, fecha, hora, servicio, tamano, costo) VALUES (%s, %s, %s, %s, %s, %s, %s)', 
                       (folio, mascota, fecha, hora, servicio, tamano, costo))
            conn.commit()
            cur.close()
            conn.close()
            flash('Cita agendada exitosamente', 'success')
        except Exception as e:
            flash(f'Error al agendar cita: {str(e)}', 'error')
        
        return redirect(url_for('index'))

@app.route('/editar/<int:folio>')
def mostrar_editar(folio):
    try:
        conn = mysql.connect()
        cur = conn.cursor()
        cur.execute('SELECT * FROM agenda_servicios.agendar WHERE folio = %s', (folio,))
        cita = cur.fetchone()
        cur.close()
        conn.close()
        
        if cita:
            return render_template('editar.html', cita=cita)
        else:
            flash('Cita no encontrada', 'error')
            return redirect(url_for('index'))
    except Exception as e:
        flash(f'Error al cargar cita: {str(e)}', 'error')
        return redirect(url_for('index'))

@app.route('/editar', methods=['POST'])
def editar():
    if request.method == 'POST':
        folio = request.form.get('folio', '')
        mascota = request.form.get('mascota', '')
        fecha = request.form.get('fecha', '')
        hora = request.form.get('hora', '')
        servicio = request.form.get('servicio', '')
        tamano = request.form.get('tamano', '')
        costo = request.form.get('costo', 0)
        
        if not all([folio, mascota, fecha, hora, servicio, tamano, costo]):
            flash('Todos los campos son requeridos', 'error')
            return redirect(url_for('index'))
        
        try:
            conn = mysql.connect()
            cur = conn.cursor()
            cur.execute('''UPDATE agenda_servicios.agendar 
                          SET mascota=%s, fecha=%s, hora=%s, servicio=%s, tamano=%s, costo=%s 
                          WHERE folio=%s''', 
                       (mascota, fecha, hora, servicio, tamano, costo, folio))
            conn.commit()
            cur.close()
            conn.close()
            flash('Cita editada exitosamente', 'success')
        except Exception as e:
            flash(f'Error al editar cita: {str(e)}', 'error')
        
        return redirect(url_for('index'))

@app.route('/eliminar/<int:folio>', methods=['POST', 'GET'])
def eliminar(folio):
    try:
        conn = mysql.connect()
        cur = conn.cursor()
        
        cur.execute('SELECT * FROM agenda_servicios.agendar WHERE folio = %s', (folio,))
        cita = cur.fetchone()
        
        if cita:
            cur.execute('DELETE FROM agenda_servicios.agendar WHERE folio = %s', (folio,))
            conn.commit()
            flash('Cita eliminada exitosamente', 'success')
        else:
            flash('Cita no encontrada', 'error')
        
        cur.close()
        conn.close()
    except Exception as e:
        flash(f'Error al eliminar cita: {str(e)}', 'error')
    
    return redirect(url_for('index'))

if __name__ == '__main__':
    app.run(port=3000, debug=True)