from flask import Flask, request, jsonify, send_from_directory
import smtplib
import os
from dotenv import load_dotenv
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from datetime import datetime, timedelta
import threading
import time

app = Flask(__name__)

# Cargar variables de entorno
load_dotenv()

remitente = os.getenv('EMAIL_USER')
password = os.getenv('EMAIL_PASS')

print("EMAIL:", remitente)
print("PASS length:", len(password) if password else None)

# Almacenamiento temporal de tareas
tasks = []

@app.route('/')
def index():
    return send_from_directory('.', 'index.html')

@app.route('/api/register-task', methods=['POST'])
def register_task():
    task = request.json
    tasks.append(task)
    
    task_time = datetime.fromisoformat(task['time'].replace('Z', '+00:00'))
    reminder_time = task_time - timedelta(minutes=5)
    
    thread = threading.Thread(target=schedule_reminder, args=(task, reminder_time))
    thread.start()
    
    return jsonify({'status': 'success', 'task': task})

def schedule_reminder(task, reminder_time):
    now = datetime.now(reminder_time.tzinfo)
    wait_seconds = (reminder_time - now).total_seconds()
    
    if wait_seconds > 0:
        time.sleep(wait_seconds)
    
    send_email_reminder(task)

def send_email_reminder(task):
    try:
        html_content = f"""
        <html>
        <body style="font-family: Arial;">
            <h2>⏰ Recordatorio</h2>
            <p>Tienes una tarea en 5 minutos:</p>
            <ul>
                <li><b>Tarea:</b> {task['type']}</li>
                <li><b>Detalles:</b> {task['detail']}</li>
                <li><b>Hora:</b> {task['time']}</li>
            </ul>
        </body>
        </html>
        """
        
        msg = MIMEMultipart()
        msg['Subject'] = f" RECORDATORIO: {task['type']}"
        msg['From'] = remitente
        msg['To'] = task['email']
        msg.attach(MIMEText(html_content, 'html'))
        
        # conexión segura al servidor SMTP de Gmail
        server = smtplib.SMTP_SSL('smtp.gmail.com', 465)
        server.login(remitente, password)
        server.sendmail(remitente, task['email'], msg.as_string())
        server.quit()
        
        print(f" Correo enviado a {task['email']}")
        
    except Exception as e:
        print(f" Error enviando correo: {e}")

@app.route('/api/send-reminder', methods=['POST'])
def send_reminder():
    task = request.json
    send_email_reminder(task)
    return jsonify({'status': 'success'})

if __name__ == '__main__':
    app.run(debug=True, port=5000)