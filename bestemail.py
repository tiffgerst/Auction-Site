#!C:\Users\leona\AppData\Local\Programs\Python\Python310\python.exe 


import smtplib, ssl
from email.mime.multipart import MIMEMultipart
from email.mime.application import MIMEApplication
from email.mime.text import MIMEText
import sys


sender = 'sillygoosecoursework@gmail.com'
password = '&C6QLPbNS&6iBdRQ'


receiver = 'leonardpaturel@gmail.com'


port = 587    
smtp_server = "smtp.gmail.com"

message = f"""\
Subject: Hi there

This message is sent from Python. {sys.argv[1]}"""

context = ssl.create_default_context()
with smtplib.SMTP(smtp_server, port) as server:
    server.starttls(context=context)
    server.ehlo()
    server.login(sender, password)
    server.ehlo()
    server.sendmail(sender, receiver, message)


