import pymysql
import pymysql.cursors
import smtplib, ssl
from email.mime.multipart import MIMEMultipart
from email.mime.application import MIMEApplication
from email.mime.text import MIMEText
import sys


#database connection
connection = pymysql.connect(host="localhost",user="root",passwd="",database="auction", cursorclass=pymysql.cursors.DictCursor)
cursor = connection.cursor()
query = "INSERT INTO `users` (`email`, `password`, `accountType`, `country`, `addressLine`, `city`, `postcode`) VALUES('ben100@thread.com', '$2y$10$aR0n47yMHpE.H9b7MLUC4.UeTbX8CXm2tpQ8BqxjfPU.5XiIzgcui', 'buyer', 'Jamaica', '34 Richest Street', 'Richest City', 'RI3H M3')"
cursor.execute(query)
connection.commit()
connection.close()





# sender = 'sillygoosecoursework@gmail.com'
# password = '&C6QLPbNS&6iBdRQ'


# receiver = 'leonardpaturel@gmail.com'


# port = 587    
# smtp_server = "smtp.gmail.com"

# message = f"""\
# Subject: Hi there

# This message is sent from Python. {sys.argv[1]}"""

# context = ssl.create_default_context()
# with smtplib.SMTP(smtp_server, port) as server:
#     server.starttls(context=context)
#     server.ehlo()
#     server.login(sender, password)
#     server.ehlo()
#     server.sendmail(sender, receiver, message)


