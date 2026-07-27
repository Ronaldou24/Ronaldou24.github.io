from flask import Flask, render_template, request, url_for, redirect, flash
from flask_mysqldb import MySQL

app = Flask(__name__)
app.secret_key = 'Quimixto1'  

# Configuración de MySQL
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = 'Quimixto1'
app.config['MYSQL_DB'] = 'agenda_servicios'

mysql = MySQL(app)

@app.route('/')
def index():
    try:
        cur = mysql.connection.cursor()
        cur.execute('SELECT * FROM agendar ORDER BY fecha, hora')
        citas = cur.fetchall()
        cur.close()
        return render_template('index.html', citas=citas)
    except Exception as e:
        print(f"Error al cargar citas: {e}")
        flash(f'Error al cargar citas: {str(e)}', 'error')
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
        
        if not all([mascota, fecha, hora, servicio, tamano, costo]):
            flash('Todos los campos son requeridos', 'error')
            return redirect(url_for('index'))
        
        try:
            cur = mysql.connection.cursor()
            if folio:  # Si se proporciona folio específico
                cur.execute('INSERT INTO agendar (folio, mascota, fecha, hora, servicio, tamano, costo) VALUES (%s, %s, %s, %s, %s, %s, %s)', 
                           (folio, mascota, fecha, hora, servicio, tamano, costo))
            else:  # Auto-increment folio
                cur.execute('INSERT INTO agendar (mascota, fecha, hora, servicio, tamano, costo) VALUES (%s, %s, %s, %s, %s, %s)', 
                           (mascota, fecha, hora, servicio, tamano, costo))
            mysql.connection.commit()
            cur.close()
            flash('Cita agendada exitosamente', 'success')
        except Exception as e:
            print(f"Error al agendar: {e}")
            flash(f'Error al agendar cita: {str(e)}', 'error')
        
        return redirect(url_for('index'))

@app.route('/editar/<int:folio>')
def mostrar_editar(folio):
    try:
        cur = mysql.connection.cursor()
        cur.execute('SELECT * FROM agendar WHERE folio = %s', (folio,))
        cita = cur.fetchone()
        cur.close()
        
        if cita:
            return render_template('editar.html', cita=cita)
        else:
            flash('Cita no encontrada', 'error')
            return redirect(url_for('index'))
    except Exception as e:
        print(f"Error al cargar cita: {e}")
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
            cur = mysql.connection.cursor()
            cur.execute('''UPDATE agendar 
                          SET mascota=%s, fecha=%s, hora=%s, servicio=%s, tamano=%s, costo=%s 
                          WHERE folio=%s''', 
                       (mascota, fecha, hora, servicio, tamano, costo, folio))
            mysql.connection.commit()
            cur.close()
            flash('Cita editada exitosamente', 'success')
        except Exception as e:
            print(f"Error al editar: {e}")
            flash(f'Error al editar cita: {str(e)}', 'error')
        
        return redirect(url_for('index'))

@app.route('/eliminar/<int:folio>', methods=['POST', 'GET'])
def eliminar(folio):
    try:
        cur = mysql.connection.cursor()
        
        cur.execute('SELECT * FROM agendar WHERE folio = %s', (folio,))
        cita = cur.fetchone()
        
        if cita:
            cur.execute('DELETE FROM agendar WHERE folio = %s', (folio,))
            mysql.connection.commit()
            flash('Cita eliminada exitosamente', 'success')
        else:
            flash('Cita no encontrada', 'error')
        
        cur.close()
    except Exception as e:
        print(f"Error al eliminar: {e}")
        flash(f'Error al eliminar cita: {str(e)}', 'error')
    
    return redirect(url_for('index'))

# Ruta para probar la conexión
@app.route('/test-db')
def test_db():
    try:
        cur = mysql.connection.cursor()
        cur.execute('SELECT 1')
        result = cur.fetchone()
        cur.close()
        return f"Conexión exitosa: {result}"
    except Exception as e:
        return f"Error de conexión: {str(e)}"

if __name__ == '__main__':
    app.run(port=3000, debug=True)