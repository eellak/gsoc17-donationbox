#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import MySQLdb
import sys
from colorama import Fore, Style

from Databases import Databases



class MySQL(Databases):

    def __init__(self, host='localhost', user='root', password='123456789', database='wordpress'):

        super().__init__()

        try:
            self.connection = MySQLdb.connect(host, user, password, database)

        except MySQLdb.Warning as e:
            print( Fore.YELLOW + "Warning {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL )

        except MySQLdb.Error as e:
            print( Fore.RED + "Error {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL )

            if e.args[0] == 2003: # Can't connect to MySQL server on 'donationboxes.database' (113)
                print(e)
                print(Fore.GREEN + "\nOK, lets start the thread..\n" + Style.RESET_ALL)
            else:
                sys.exit(1)




    def get_database_version(self):
        try:
            cursor = self.connection.cursor()

            cursor.execute("SELECT VERSION()")
            version = cursor.fetchone()

            print("Database version : {0} ".format(version))

        except MySQLdb.Warning as e:
                print(Fore.YELLOW + "Warning {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

        except MySQLdb.Error as e:
            print(Fore.RED + "Error {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

            if e.args[0] == 2003:  # Can't connect to MySQL server on 'donationboxes.database' (113)
                print(e)
                print(Fore.GREEN + "\nOK, lets start the thread..\n" + Style.RESET_ALL)
            else:
                sys.exit(1)

        finally:
            if cursor:
                cursor.close




    def insert_data(self, list_of_data=''):

        try:
            cursor = self.connection.cursor()

#			.....

        except MySQLdb.Warning as e:
            print(Fore.YELLOW + "Warning {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

        except MySQLdb.Error as e:
            print(Fore.RED + "Error {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

            if e.args[0] == 2003:  # Can't connect to MySQL server on 'donationboxes.database' (113)
                print(e)
                print(Fore.GREEN + "\nOK, lets start the thread..\n" + Style.RESET_ALL)
            else:
                sys.exit(1)

        finally:
            if cursor:
                cursor.close




    def get_last_modified_datetime(self):

        last_modified = None

        try:
            cursor = self.connection.cursor()

            cursor.execute("""
                SELECT post_modified FROM wordpress.wp_posts
                where post_type = 'donationboxes' and post_status = 'publish'
                ORDER BY post_modified DESC
                LIMIT 1;
                """)

            last_modified = cursor.fetchone()

            print( last_modified )
            print( "Number of rows inserted: {0}".format(cursor.rowcount) )

        except MySQLdb.Warning as e:
            print(Fore.YELLOW + "Warning {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

        except MySQLdb.Error as e:
            print(Fore.RED + "Error {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

            if e.args[0] == 2003:  # Can't connect to MySQL server on 'donationboxes.database' (113)
                print(e)
                print(Fore.GREEN + "\nOK, lets start the thread..\n" + Style.RESET_ALL)
            else:
                sys.exit(1)

        finally:
            if cursor:
                cursor.close




if __name__ == '__main__':
   db = MySQL()
    # db = database('donationboxes.database', 'tassos', '1234567890', 'donatioboxdatabase' ) # mysql -u tassos -h donationboxes.database -p

    # db.insert_data()
   db.get_database_version()
   db.get_last_modified_datetime()

   db.close_connection()




