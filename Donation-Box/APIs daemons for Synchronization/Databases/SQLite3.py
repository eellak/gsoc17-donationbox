#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import sqlite3
from colorama import Fore, Style
import os
import datetime

from .Databases import Databases


__author__      = 'Tas-sos'
__email__       = 'tas-sos@g-lts.info'
__version__     = '1'
__copyright__   = "GNU General Public License v3.0"




class SQLite3(Databases):


    def __init__(self, database_file):

        super().__init__()

        try:
            if os.path.isfile(database_file):
                self.connection = sqlite3.connect(database_file)
            else:
                print(Fore.RED + "Database file doesn't exist, check the configuration file!" + Style.RESET_ALL)
                exit(1)

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)
            exit(1)




    def get_database_version(self):
        """
        Method that returns the database version.

        :return: The version of local SQLite3 database, if it can connect with her, or else return False.
        """
        try:
            cursor = self.connection.cursor()
            version = cursor.execute("SELECT sqlite_version()").fetchone()

            print("Database version : {0} ".format(version))

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)
            exit(1)

        finally:
            if cursor:
                cursor.close()




    def get_last_modified_datetime(self):
        """
        Method that returns from the "Donation_Project" ( actually "presentation_donation_project") table the most
        recent modification date-time stamp of all local donation projects where exist in this.

        :return: The most recent modification date of all donation projects.
        """

        query = "SELECT last_modified_datetime FROM presentation_donation_project ORDER BY last_modified_datetime DESC LIMIT 1;"

        try:
            cursor = self.connection.cursor()
            last_modified = cursor.execute(query).fetchone()

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)
            exit(1)

        finally:
            if cursor:
                cursor.close()

        last_modified = datetime.datetime.strptime(last_modified[0], "%Y-%m-%d %H:%M:%S")

        return last_modified




    def get_last_modified_datetime_of_projects(self):
        """
        Method that returns from all local projects the last daytime stamp of their modification.

        This method get the data from the local database,
        from "Donation_Project" ( actually "presentation_donation_project") table.

        :return: A list of tuples, where each tuple contains the identifier id of project and
                 the datetime of the last modification of this.
        """

        query = "SELECT id, last_modified_datetime FROM presentation_donation_project;"

        try:
            cursor = self.connection.cursor()
            cursor.execute(query).fetchone()

            temp = cursor.fetchall()

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)
            exit(1)

        finally:
            if cursor:
                cursor.close()

        projects = list()

        for i in range(0, len(temp)):
            projects.append( (temp[i][0], datetime.datetime.strptime(temp[i][1], "%Y-%m-%d %H:%M:%S")) )

        return projects



    def view_all_records(self, project_id=None):
        """
        Show all fields of a donation project or from all donation projects.

        This method displays all fields from all donation projects ( with parameter project_id=None ) or
        from a specific donation project (i.e with parameter project_id=41 )

        :param project_id: (Optional) The id of a donation project.
        :return:
        """

        select_all_query = "SELECT id, title, text, current_amount, target_amount, image_URL, video_URL, " \
                           "stylesheet_URL, start_date, end_date, last_modified_datetime FROM presentation_donation_project;"

        select_all_from_specific_project = "SELECT id, title, text, current_amount, target_amount, image_URL, " \
                                           "video_URL, stylesheet_URL, start_date, end_date, last_modified_datetime " \
                                           "FROM presentation_donation_project WHERE id = " + str(project_id) + ";"

        fields = ("id", "title", "text", "current_amount", "target_amount", "image_URL",
                  "video_URL", "stylesheet_URL", "start_date", "end_date", "last_modified_datetime")

        try:
            cursor = self.connection.cursor()

            if project_id:
                record = cursor.execute(select_all_from_specific_project).fetchone()

                if not record:
                    print( Fore.RED + "No record found with id = " + str(project_id) + "." + Style.RESET_ALL)
                    return False


                for field, record_field in zip(fields, record):
                    print("{0}\t:\t{1}".format(field, record_field))

            else:
                records = cursor.execute(select_all_query).fetchall()

                for record in records:
                    for field, record_field in zip(fields, record):
                        print( "{0}\t:\t{1}".format(field , record_field) )
                    print("-------------------------------------------------------------------------------------------")

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)

        finally:
            if cursor:
                cursor.close()




    def update(self,
               project_id,
               project_title,
               project_content,
               project_current_amount,
               project_target_amount,
               project_image_url,
               project_video_url,
               project_stylesheet_url,
               project_start_date,
               project_end_date,
               project_last_modified
               ):
        """
        This method update the local SQLite database with data it receives in the parameters.

        This method is intended to be used to update the local database with the data of the remote database.

        :param project_id: The id from the project intend for update.
        :param project_title: The new title to be assigned.
        :param project_content: The content title to be assigned.
        :param project_current_amount: The new current_amount to be assigned.
        :param project_target_amount: The new target_amount to be assigned.
        :param project_image_url: The new image_url to be assigned.
        :param project_video_url: (Optional)  The new video_url to be assigned.
        :param project_stylesheet_url: (Optional)  The new stylesheet_url to be assigned.
        :param project_start_date: The new start_date to be assigned.
        :param project_end_date: The new end_date to be assigned.
        :param project_last_modified: The new last_modified to be assigned.
        :return:
        """

        query = "UPDATE presentation_donation_project SET " \
                "title = \""                    + str(project_title)            + "\", "    \
                "text = \""                     + str(project_content)          + "\", "    \
                "current_amount = "             + str(project_current_amount)   + ", "      \
                "target_amount = "              + str(project_target_amount)    + ", "      \
                "image_URL = \""                + str(project_image_url)        + "\", "    \
                "start_date = \""               + str(project_start_date)       + "\", "    \
                "end_date = \""                 + str(project_end_date)         + "\", "    \
                "last_modified_datetime = \""   + str(project_last_modified)    + "\"";

        if project_video_url:
            query += ", video_URL = \"" + str(project_video_url) + "\" ";

        if project_stylesheet_url:
            query += ", stylesheet_URL = \"" + str(project_stylesheet_url) + "\" ";

        query += "WHERE id = " + str(int(project_id)) + ";"

        try:
            cursor = self.connection.cursor()
            status = cursor.execute(query).rowcount

            self.connection.commit()

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            raise Exception(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)

        finally:
            if cursor:
                cursor.close()

        if status:
            print(Fore.GREEN + "The project with id = " + str(project_id) + ", was successfully updated." + Style.RESET_ALL)
            return True
        else:
            print(Fore.RED + "The update for the project with id = " + str(project_id) + ", was failed!" + Style.RESET_ALL)
            return False




    def insert(self,
                project_id,
                project_title,
                project_content,
                project_current_amount,
                project_target_amount,
                project_image_url,
                project_video_url,
                project_stylesheet_url,
                project_start_date,
                project_end_date,
                project_last_modified
                ):
        """
        This method update the local SQLite database with data it receives in the parameters.

        This method is intended to insert new data the remote database in the local database.
        However, it may attempt to be inserted (as a new donation project) an already existing donation project.
        In this case, the existing donation project is updated.

        :param project_id: The id from the project intend for update.
        :param project_title: The new title to be assigned.
        :param project_content: The content title to be assigned.
        :param project_current_amount: The new current_amount to be assigned.
        :param project_target_amount: The new target_amount to be assigned.
        :param project_image_url: The new image_url to be assigned.
        :param project_video_url: (Optional) The new video_url to be assigned.
        :param project_stylesheet_url: (Optional) The new stylesheet_url to be assigned.
        :param project_start_date: The new start_date to be assigned.
        :param project_end_date: The new end_date to be assigned.
        :param project_last_modified: The new last_modified to be assigned.
        :return:
        """

        query = "INSERT INTO presentation_donation_project "

        if project_video_url and not project_stylesheet_url:
            query += "(id,title,text,current_amount,target_amount,image_URL,video_URL,start_date,end_date,last_modified_datetime) " \
                "VALUES (" \
                + str(int(project_id))              + ", "      \
                "\"" + str(project_title)           + "\", "    \
                "\"" + str(project_content)         + "\", "    \
                + str(project_current_amount)       + ", "      \
                + str(project_target_amount)        + ", "      \
                "\"" + str(project_image_url)       + "\", "    \
                "\"" + str(project_video_url)       + "\", "    \
                "\"" + str(project_start_date)      + "\", "    \
                "\"" + str(project_end_date)        + "\", "    \
                "\"" + str(project_last_modified)   + "\" );"

        elif not project_video_url and project_stylesheet_url:
            query += "(id,title,text,current_amount,target_amount,image_URL,stylesheet_URL,start_date,end_date,last_modified_datetime) " \
                "VALUES (" \
                + str(int(project_id))              + ", "      \
                "\"" + str(project_title)           + "\", "    \
                "\"" + str(project_content)         + "\", "    \
                + str(project_current_amount)       + ", "      \
                + str(project_target_amount)        + ", "      \
                "\"" + str(project_image_url)       + "\", "    \
                "\"" + str(project_stylesheet_url)  + "\", "    \
                "\"" + str(project_start_date)      + "\", "    \
                "\"" + str(project_end_date)        + "\", "    \
                "\"" + str(project_last_modified)   + "\" );"

        elif not project_stylesheet_url and not project_video_url:
            query += "(id,title,text,current_amount,target_amount,image_URL,start_date,end_date,last_modified_datetime) " \
                "VALUES (" \
                + str(int(project_id))              + ", "      \
                "\"" + str(project_title)           + "\", "    \
                "\"" + str(project_content)         + "\", "    \
                + str(project_current_amount)       + ", "      \
                + str(project_target_amount)        + ", "      \
                "\"" + str(project_image_url)       + "\", "    \
                "\"" + str(project_start_date)      + "\", "    \
                "\"" + str(project_end_date)        + "\", "    \
                "\"" + str(project_last_modified)   + "\" );"

        else:
            query += "(id,title,text,current_amount,target_amount,image_URL,video_URL,stylesheet_URL,start_date,end_date,last_modified_datetime) " \
                "VALUES (" \
                + str(int(project_id))              + ", "      \
                "\"" + str(project_title)           + "\", "    \
                "\"" + str(project_content)         + "\", "    \
                + str(project_current_amount)       + ", "      \
                + str(project_target_amount)        + ", "      \
                "\"" + str(project_image_url)       + "\", "    \
                "\"" + str(project_video_url)       + "\", "    \
                "\"" + str(project_stylesheet_url)  + "\", "    \
                "\"" + str(project_start_date)      + "\", "    \
                "\"" + str(project_end_date)        + "\", "    \
                "\"" + str(project_last_modified)   + "\" );"

        try:
            cursor = self.connection.cursor()
            status = cursor.execute(query).rowcount

            self.connection.commit()

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:

            if e.args[0] == 'UNIQUE constraint failed: presentation_donation_project.id':
                if cursor:
                    cursor.close()
                try:
                    db.update(project_id,
                              project_title,
                              project_content,
                              project_current_amount,
                              project_target_amount,
                              project_image_url,
                              project_video_url,
                              project_stylesheet_url,
                              project_start_date,
                              project_end_date,
                              project_last_modified)
                except Exception as e:
                    print(e)
                    exit(1)
                return True
            else:
                raise Exception(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)


        finally:
            if cursor:
                cursor.close()


        if status:
            print(Fore.GREEN + "The project with id = " + str(project_id) + ", was successfully inserted." + Style.RESET_ALL)
            return True
        else:
            print(Fore.RED + "The insert of the project with id = " + str(project_id) + ", was failed!" + Style.RESET_ALL)
            return False




    def delete(self, project_id):
        """
        Delete a donation project from the local database.

        Attention! The project is deleted from the "Donation_Project" ( actually "presentation_donation_project") table.

        As referenced here : https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box
        This table have the donation projects that also exist and the central database. So, request to delete a donation
        project from this table, means that the project has also been deleted from the remote database.


        :param project_id: The id of a donation project.
        :return: The local database of all donation projects without donation project with id = project_id.
        """

        query = "DELETE FROM presentation_donation_project WHERE id = " + str(int(project_id)) + ";"

        try:
            cursor = self.connection.cursor()
            status = cursor.execute(query).rowcount

            self.connection.commit()

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            raise Exception(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)

        finally:
            if cursor:
                cursor.close()

        if status:
            print(Fore.GREEN + "The project with id = " + str(project_id) + ", was successfully deleted." + Style.RESET_ALL)
            return True
        else:
            print(Fore.RED + "The deletion for the project with id = " + str(project_id) + ", was failed!" + Style.RESET_ALL)
            return False




    def get_new_donations(self):
        """"
        Method that returns all new donations that have been done in the box.

        As referenced here : https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box
        All donations that has been donated on the donation box, saved in the "temporary_Donation_Project"
        ( actually "presentation_temporary_donation_project") table.

                                                      id, amount,   last_updated_datetime,          project_id_id
        :return: A list of tuples with this format : (1,    2,      '2017-08-23 14:39:27.878353',   425)
        """
        query = "SELECT id, amount, last_updated_datetime, project_id_id FROM presentation_temporary_donation_project;"

        try:
            cursor = self.connection.cursor()
            new_donations = cursor.execute(query).fetchall()

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)
            exit(1)

        finally:
            if cursor:
                cursor.close()

        return new_donations



    def clear_local_new_donations(self):
        """
        A method that eliminates all the temporary data of the new donations made on the box.

        As referenced here : https://github.com/eellak/gsoc17-donationbox/tree/master/Donation-Box
        All donations that has been donated on the donation box, saved in the "temporary_Donation_Project"
        ( actually "presentation_temporary_donation_project") table.

        :return: Empty the "presentation_temporary_donation_project" table.
        """
        query = "DELETE FROM presentation_temporary_donation_project;"

        try:
            cursor = self.connection.cursor()

            status = cursor.execute(query).rowcount

            self.connection.commit()

        except sqlite3.Warning as e:
            print(Fore.YELLOW + "Warning: [ {0} ]".format(e.args[0]) + Style.RESET_ALL)

        except (sqlite3.Error, sqlite3.DatabaseError, sqlite3.IntegrityError, sqlite3.ProgrammingError) as e:
            print(Fore.RED + "Error: [ {0} ] ".format(e.args[0]) + Style.RESET_ALL)
            exit(1)

        finally:
            if cursor:
                cursor.close()

        if status:
            print(Fore.GREEN + "Local new donations deleted." + Style.RESET_ALL)
            return True
        else:
            print(Fore.RED + "The deletion of local new donations was failed!" + Style.RESET_ALL)
            return False





if __name__ == '__main__':

    db = SQLite3( Databases.get_sqlite_database_path() )
    db.get_database_version()
    # print( db.get_last_modified_datetime() )
    # print ( db.get_last_modified_datetime_of_projects() )

    # db.view_all_records()

    # try:
    #     db.update(425,
    #               'Update from Python Script',
    #               'These content was changed from python script!',
    #               10,
    #               400,
    #               'http://localhost:8010/wp-content/uploads/2017/07/sampleVideo.mp4',
    #               'http://localhost:8100/wp-content/uploads/2017/07/sample.png',
    #               'http://localhost:8010/wp-content/uploads/2017/07/mainStyle.css',
    #               '2016-11-01',
    #               '2017-8-31',
    #               '2017-8-28 11:31:44')
    # except Exception as e:
    #     print(e)

    # try:
    #     db.insert(334,
    #               'Insert/Update Date',
    #               'A insert query',
    #               0,
    #               100,
    #               'test1.jpg',
    #               None,
    #               None,
    #               '2010-11-01',
    #               '2010-8-31',
    #               '2017-8-28 00:31:44')
    # except Exception as e:
    #     print(e)


    # db.view_all_records()
    # db.delete(334)
    # db.view_all_records()


    # print( db.get_new_donations() )
    #
    # db.clear_local_new_donations()
    #
    # print( db.get_new_donations() )


    db.close_connection()
