from bs4 import BeautifulSoup
import mysql.connector

cnx = mysql.connector.connect(host='localhost', user='al', passwd='111', database='hyip')
cursor = cnx.cursor()

hit = [0]
query = "SELECT monitor FROM monitor WHERE hit = %s LIMIT 1"
cursor.execute(query, hit)

monitor = None

for (mon) in cursor:
    monitor = mon[0]

hit = 1
update_monitor = "UPDATE monitor SET hit = %s WHERE monitor = %s"
data_monitor = (hit, monitor)
cursor.execute(update_monitor, data_monitor)
cnx.commit()
cursor.close()
cnx.close()
