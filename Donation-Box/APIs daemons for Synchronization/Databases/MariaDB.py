#!/usr/bin/env python3
# -*- coding: utf-8 -*-

"""
Implement an Application Programming Interface (API) for the remote (MariaDB) database.

The implementation of this interface contains the basic features that are needed for the operations required
from database synchronization. To successfully synchronize the local database with the remote required from both sides
some actions. Here implemented the necessary actions needed from the remote database.
"""

import MySQLdb
import sys
from colorama import Fore, Style

from .Databases import Databases


__author__      = 'Tas-sos'
__email__       = 'tas-sos@g-lts.info'
__version__     = '0.5'
__copyright__   = "GNU General Public License v3.0"




class MariaDB(Databases):

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
                return False
            else:
                sys.exit(1)




    def get_database_version(self):
        """
        Method that returns the database version.

        :return: The version of remote database, if it can connect with her, or else return False.
        """
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
                return False
            else:
                sys.exit(1)

        finally:
            if cursor:
                cursor.close

        return True



    def get_last_modified_datetime(self):
        """
        Method that returns the last daytime stamp from all donation projects.

        Gets from the remote database to the most recent of all dates that last modified a donation project.

        ** IMPORTANT - ATTENTION! ** :
        In this implementation, i get from the local WordPress database, the last modification date-time stamp of each
        donation project, because there are problems with the integration of our core  central 'donationbox_network'
        remote database. Once the central database is ready, just change a little the query!!

        :return: The most recent last modified date time of all donation projects, else return False if it cannot
                 connect to the database.
        """

        try:
            cursor = self.connection.cursor()

            cursor.execute("""
                SELECT post_modified FROM wordpress.wp_posts
                WHERE post_type = 'donationboxes' AND post_status = 'publish'
                ORDER BY post_modified DESC
                LIMIT 1;
                """)

            last_modified = cursor.fetchone()

        except MySQLdb.Warning as e:
            print(Fore.YELLOW + "Warning {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

        except MySQLdb.Error as e:
            print(Fore.RED + "Error {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

            if e.args[0] == 2003:  # Can't connect to MySQL server on 'donationboxes.database' (113)
                print(e)
                return False
            else:
                sys.exit(1)

        finally:
            if cursor:
                cursor.close

        return last_modified[0]




    def get_last_modified_datetime_of_projects(self):
        """
        Method that returns for all projects the last daytime stamp of their modification.

        Gets from the remote database to the last modification date-time stamp of each donation project,
        in order to check this date with the date-time stamp of the local database.

        ** IMPORTANT - ATTENTION! ** :
        In this implementation, i get from the local WordPress database, the last modification date-time stamp of each
        donation project, because there are problems with the integration of our core  central 'donationbox_network'
        remote database. Once the central database is ready, just change a little the query!!

        :return: If successful, a list of tuples, where each tuple contains the identifier id of project and
                 the datetime of the last modification of this, else return False if it cannot connect to the database.
        """

        try:
            cursor = self.connection.cursor()

            cursor.execute("""
                SELECT ID, post_modified FROM wordpress.wp_posts
                WHERE post_type = 'donationboxes' AND post_status = 'publish';
                """)

            projects = cursor.fetchall()

        except MySQLdb.Warning as e:
            print(Fore.YELLOW + "Warning {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

        except MySQLdb.Error as e:
            print(Fore.RED + "Error {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

            if e.args[0] == 2003:  # Can't connect to MySQL server on 'donationboxes.database' (113)
                print(e)
                return False
            else:
                sys.exit(1)

        finally:
            if cursor:
                cursor.close

        return projects




    def update(self, donation_box_id, donation_project_id, local_amount, local_donation_time):
        """
        This Method updates/adds a new donate record to the remote database for a specific project.

        Note : In the current implementation of the database, all donations are saved in the "donation" table
        in the remote database. Therefore, to find the total amount that has been collected so far for a donation
        project, it's enough to go to this table and sum up the donations that have been made for a
        specific donation project (idProject).

        :param donation_box_id: The identifier id of local donation box (as defined in the local configuration file)
        :param donation_project_id: The id of the donation project that needs to change its total amount.
        :param local_amount: The temporary local amount that collected in this box until the box is synchronized to the remote database.
        :param local_donation_time: The local time that this donation took place.

        :return: If successful, adds a new record to the remote database and return True, else return False if it
                 cannot connect to the database.
        """

        query = "INSERT INTO Project (idBox, DonationTime, Amount, idProject) VALUES ("
        query += str(donation_box_id) + ", \'" + str(local_donation_time) + "\', " + str(local_amount) + ", " + str(donation_project_id)
        query += ");"

        print(query)

        try:
            cursor = self.connection.cursor()

            cursor.execute(query)

            self.connection.commit()

        except MySQLdb.Warning as e:
            print(Fore.YELLOW + "Warning {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

        except MySQLdb.Error as e:
            print(Fore.RED + "Error {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

            if e.args[0] == 2003: # Can't connect to MySQL server on 'donationboxes.database' (113)
                print(e)
                return False
            else:
                sys.exit(1)

        finally:
            if cursor:
                cursor.close

        return True



    def get_total_current_amount(self, project_id):
        """
        Method that finds the total amount collected for a project.

        Note : In the current implementation of the database, all donations are saved in the "donation" table
        in the remote database. Therefore, to find the total amount that has been collected so far for a donation
        project, it's enough to go to this table and sum up the donations that have been made for a
        specific donation project (idProject).

        :param project_id: For which project to find the total amount that has been collected.
        :return: The total amount collected for a project, else return False if it cannot connect to the database.
        """

        query = "SELECT SUM(Amount) FROM donation, Project WHERE idproject = " + str(project_id)
        query += " and donation.idproject = Project.idProject;"

        try:
            cursor = self.connection.cursor()

            cursor.execute(query)

            return cursor.fetchone()

        except MySQLdb.Warning as e:
            print(Fore.YELLOW + "Warning {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

        except MySQLdb.Error as e:
            print(Fore.RED + "Error {0}: {1}".format(e.args[0], e.args[1]) + Style.RESET_ALL)

            if e.args[0] == 2003: # Can't connect to MySQL server on 'donationboxes.database' (113)
                print(e)
                return False
            else:
                sys.exit(1)

        finally:
            if cursor:
                cursor.close




    def view_all_records(self):
        pass




if __name__ == '__main__':
    db = MariaDB()

    # mysql -u tassos -h donationboxes.database -p
    # db = MariaDB(MariaDB.get_hostname_of_remote_database(), 'tassos', '1234567890', 'donationbox_network' )

    # db.get_database_version()
    # print ( db.get_last_modified_datetime() )
    # print ( db.get_last_modified_datetime_of_projects() )

    # No working at this time :
    # db.update( MariaDB.get_donation_box_id(), 406, 10, '25-5-2016 18:04:44' )
    # db.get_total_current_amount(406)

    db.close_connection()


