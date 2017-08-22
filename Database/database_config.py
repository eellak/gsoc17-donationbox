#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
Utility software to make installing and configuring the database more user friendly.
We created this script to make your life and database installation easier.


** Run this code script with administrative privileges! **

i.e.:
    sudo python3 database_config.py

If you run it and it goes well, you will have your database ready in just a few moments.
We hope to help you. :)
"""

import subprocess
import getpass
import sys
from colorama import Fore, Style
from time import sleep

__author__      = 'Tas-sos'
__email__       = 'tas-sos@g-lts.info'
__version__     = '1'
__copyright__   = "GNU General Public License v3.0"





def check_if_database_server_already_exists():
    """
    Function that checks if already installed the MariaDB database.
    
    It checks if the MariaDB database is installed, if it is not, then installing it.
    :return: Installed (if it has internet) the MariaDB database or notify the user that is already installed.
    """""
    try:
        check = "dpkg -l | grep mariadb-common | awk '{  if ($1) exit 1 }'"
        subprocess.check_call(check, shell=True)
        print(Fore.RED + "The database MariaDB is not installed." + Style.RESET_ALL)
        print("Installation effort...\n")
        print(Fore.RED + "Important: Pay close attention when prompted to provide a password" , end="")
        print("for the root user of MariaDB server.\n" + Style.RESET_ALL)
        sleep(2)

        try:
            install = "apt-get install mariadb-common mariadb-client mariadb-server -y"
            subprocess.check_call(install, shell=True)
        except subprocess.CalledProcessError:
            print("Please take care the above message and run again the script with root privileges.")
            print("Possible causes of failure:")
            print("\t1. You did not run the script with root privileges.")
            print("\t2. You do not have an internet connection.")
            sys.exit(1)

    except subprocess.CalledProcessError:
        print(Fore.GREEN + "The database is installed." + Style.RESET_ALL)




def load_database():
    """
    Create the database.

    This function run the "create_database.sql" SQL script file and creates the database, tables and their relationships.
    Be careful, the SQL script file "create_database.sql", should be in the same directory as this python script.

    :return: Ready database or error message.
    """

    password = getpass.getpass("Give the password of MariaDB root user : ")

    try:
        create_database = "mysql -u root -p"+password+" < create_database.sql"
        print(Fore.RED)
        subprocess.check_call(create_database, shell=True)

    except subprocess.CalledProcessError:
        print(Style.RESET_ALL)
        print("Please take care the above message and run again the script with root privileges.")
        print("Possible causes of failure:")
        print("\t1. You did not run the script with root privileges.")
        print("\t2. You have not entered the correct password of the root user of MariaDB server.")
        print("\t3. The SQL script file \"create_database.sql\" is not in the same directory as the script")
        print("\t4. There is a syntax error in the SQL script file (\"create_database.sql\").\n")
        sys.exit(1)

    print(Fore.GREEN + "Your database, tables and their relationships, were successfully created!\n" + Style.RESET_ALL)




if __name__ == '__main__':
    check_if_database_server_already_exists()
    load_database()
