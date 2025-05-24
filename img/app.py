from flask import Flask, render_template, request, redirect, url_for
from flask_mysqldb import MySQL
import hashlib

app = Flask(__name__)

# Database configuration
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'  # default user for XAMPP
app.config['MYSQL_PASSWORD'] = ''  # default password for XAMPP
app.config['MYSQL_DB'] = 'adventure'
mysql = MySQL(app)

class AdventureApp:
    def __init__(self):
        self.app = app
        self.conn = mysql.connection
        self.cursor = self.conn.cursor()

    def register_user(self, name, email, password, country):
        password_hash = hashlib.sha256(password.encode()).hexdigest()
        self.cursor.execute("INSERT INTO users (name, email, password, country) VALUES (%s, %s, %s, %s)", 
                            (name, email, password_hash, country))
        self.conn.commit()

    def book_event(self, user_id, event_name):
        self.cursor.execute("INSERT INTO bookings (user_id, event_name) VALUES (%s, %s)", (user_id, event_name))
        self.conn.commit()

    def save_contact(self, name, email, country, remarks):
        self.cursor.execute("INSERT INTO contact_us (name, email, country, remarks) VALUES (%s, %s, %s, %s)",
                            (name, email, country, remarks))
        self.conn.commit()

    def get_all_events(self):
        self.cursor.execute("SELECT * FROM events")  # You might need to create an 'events' table
        return self.cursor.fetchall()

@app.route('/')
def home():
    return render_template('index.html')

# Route for the signup page
@app.route('/signup', methods=['GET', 'POST'])
def signup():
    if request.method == 'POST':
        name = request.form['name']
        email = request.form['email']
        password = request.form['password']
        country = request.form['country']
        adventure_app = AdventureApp()
        adventure_app.register_user(name, email, password, country)
        return redirect(url_for('home'))
    return render_template('signup.html')

# Route for the booking page
@app.route('/book', methods=['POST'])
def book():
    if request.method == 'POST':
        user_id = request.form['user_id']
        event_name = request.form['event_name']
        adventure_app = AdventureApp()
        adventure_app.book_event(user_id, event_name)
        return redirect(url_for('home'))
    return render_template('book.html')

# Route for contact us form
@app.route('/contact', methods=['GET', 'POST'])
def contact():
    if request.method == 'POST':
        name = request.form['name']
        email = request.form['email']
        country = request.form['country']
        remarks = request.form['remarks']
        adventure_app = AdventureApp()
        adventure_app.save_contact(name, email, country, remarks)
        return redirect(url_for('home'))
    return render_template('contact.html')

if __name__ == '__main__':
    app.run(debug=True)
