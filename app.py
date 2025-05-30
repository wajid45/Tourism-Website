from flask import Flask, render_template, request, redirect, url_for
from flask_mysqldb import MySQL

app = Flask(__name__)

# Database configuration for XAMPP
app.config['MYSQL_HOST'] = 'localhost'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = ''
app.config['MYSQL_DB'] = 'adventure'
mysql = MySQL(app)

class AdventureApp:
    def __init__(self):
        self.conn = mysql.connection

    def save_contact(self, name, email, country, remarks):
        with self.conn.cursor() as cursor:
            cursor.execute(
                "INSERT INTO contact_us (name, email, country, remarks) VALUES (%s, %s, %s, %s)",
                (name, email, country, remarks)
            )
            self.conn.commit()


# Global instance
adventure_app = AdventureApp()

@app.route('/')
def home():
    return render_template('index.html')

@app.route('/contact', methods=['GET', 'POST'])
def contact():
    if request.method == 'POST':
        name = request.form['name']
        email = request.form['email']
        country = request.form['country']
        remarks = request.form['remarks']

        if not name or not email:
            return "Name and email are required!", 400

        try:
            adventure_app.save_contact(name, email, country, remarks)
            return redirect(url_for('home'))
        except Exception as e:
            return f"Error: {str(e)}"

    return render_template('contact.html')

if __name__ == '__main__':
    app.run(debug=True)
