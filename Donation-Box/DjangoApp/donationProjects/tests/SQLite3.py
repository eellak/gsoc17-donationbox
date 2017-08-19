#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import sqlite3
from colorama import Fore, Style
import os

from Databases import Databases


class SQLite3(Databases):


    def __init__(self, database_file='db.sqlite3'):

        super().__init__()

        try:
            if os.path.isfile(database_file):
                self.connection = sqlite3.connect(database_file)
            else:
                raise Exception(Fore.RED + "Database file doesn't exist" + Style.RESET_ALL)

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)





    def get_database_version(self):
        try:
            cursor = self.connection.cursor()
            version = cursor.execute("SELECT sqlite_version()").fetchone()

            print("Database version : {0} ".format(version))

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)

        finally:
            if cursor:
                cursor.close()




    def insert_data(self, list_of_data=''):
        try:

            cursor = self.connection.cursor()

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)

        finally:
            if cursor:
                cursor.close()


    def get_last_modified_datetime(self):
        try:
            cursor = self.connection.cursor()
            last_modified = cursor.execute("SELECT last_updated_datetime FROM presentation_donation_project ORDER BY last_updated_datetime DESC LIMIT 1;").fetchone()

            print(last_modified)

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)

        finally:
            if cursor:
                cursor.close()


if __name__=='__main__':
    db = SQLite3()
    db.get_database_version()
    db.get_last_modified_datetime()
    db.close_connection()



