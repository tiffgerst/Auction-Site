#!/usr/bin/env python3
import pymysql
import pymysql.cursors
import smtplib, ssl
from email.mime.multipart import MIMEMultipart
from email.mime.application import MIMEApplication
from email.mime.text import MIMEText

def main(b):
    #database connection
    sender = 'sillygoosecoursework@gmail.com'
    password = '&C6QLPbNS&6iBdRQ'
    port = 587    
    smtp_server = "smtp.gmail.com"

    connection = pymysql.connect(host="localhost",user="root",passwd="root",database="auction",port=8889)
    cursor = connection.cursor()
    query = f"SELECT `buyerEmail` FROM `bids` WHERE `auctionID`={b};"
    cursor.execute(query)
    result = cursor.fetchall()
    connection.close()
    
    
    
    result = (('tiffgerst@gmail.com'), ('leonardpaturel@gmail.com'))
    for row in result:
        print(row)
        receiver = f'{row}'
        message = f"""\
    Subject: Hi there

    This message is sent from Python. you won!"""
    
        context = ssl.create_default_context()
        with smtplib.SMTP(smtp_server, port) as server:
            server.starttls(context=context)
            server.ehlo()
            server.login(sender, password)
            server.ehlo()
            server.sendmail(sender, receiver, message)


if __name__ == '__main__':
    main(1)