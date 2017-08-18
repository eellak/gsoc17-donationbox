#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import MySQLdb
import sys
from colorama import Fore, Style

class database():


    def __init__(self, host='localhost', user='root', password='123456789', database='wordpress'):

        self.connection = None

        try:
            self.connection = MySQLdb.connect(host, user, password, database)

        except MySQLdb.Warning as e:
            print( Fore.YELLOW + "Warning {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL )

        except MySQLdb.Error as e:
            print( Fore.RED + "Error {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL )

            if e.args[0] == 2003: # Can't connect to MySQL server on 'donationboxes.database' (113)
                print(e)
                print("OK, lets start a thread..")
            else:
                sys.exit(1)




    def close_connection(self):

        if self.connection:
            self.connection.close()


    def view_database_version(self):
        try:
            cursor = self.connection.cursor()

            cursor.execute("SELECT VERSION()")
            version = cursor.fetchone()

            print("Database version : {0} ".format(version))

            cursor.close()

        except MySQLdb.Error as e:
            print( Fore.RED + "Error {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL )

        finally:
            if cursor:
                cursor.close


    def insert_data(self, list_of_data=''):

      #.....




if __name__ == '__main__':
    # db = database()
    db = database('donationboxes.database', 'tassos', '1234567890', 'donationboxDatabase' )

    # db.insert_data()
    db.view_database_version()

    db.close_connection()



