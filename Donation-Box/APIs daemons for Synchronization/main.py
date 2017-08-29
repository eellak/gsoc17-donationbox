#!/usr/bin/env python3
# -*- coding: utf-8 -*-

from Databases.MariaDB import MariaDB
from Databases.SQLite3 import SQLite3


__author__      = 'Tas-sos'
__email__       = 'tas-sos@g-lts.info'
__version__     = '0.5'
__copyright__   = "GNU General Public License v3.0"




if __name__ == '__main__':

    mariadb = MariaDB()

    sqlite = SQLite3( SQLite3.get_sqlite_database_path("/sync_databases/donationbox.conf") )

    local = sqlite.get_last_modified_datetime()
    remote = mariadb.get_last_modified_datetime()

    print("Local : ", local)
    print("Remote :", remote)

    if remote > local:
        print("The local database is out of date.")
    elif remote < local:
        print("It's time to update the remote database. :-)")
    else:
        print("No update needed - The databases is synchronized.")


