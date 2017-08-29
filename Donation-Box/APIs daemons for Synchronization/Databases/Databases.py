#!/usr/bin/env python3
# -*- coding: utf-8 -*-

from abc import ABCMeta, abstractmethod
from colorama import Fore, Style
import configparser


__author__      = 'Tas-sos'
__email__       = 'tas-sos@g-lts.info'
__version__     = '0.6'
__copyright__   = "GNU General Public License v3.0"




class Databases(metaclass=ABCMeta):

    def __init__(self):
        self.connection = None


    def close_connection(self):
        if self.connection:
            self.connection.close()


    @staticmethod
    def get_donation_box_id(configuration_file='donationbox.conf'):

        config = configparser.ConfigParser()
        file = config.read(configuration_file)

        if file:
            return config['DONATION_BOX']['donation_box_id']
        else:
            print(Fore.RED + "Configuration file \""+configuration_file+"\" doesn't exists!" + Style.RESET_ALL)
            exit(1)


    @staticmethod
    def get_hostname_of_remote_database(configuration_file='donationbox.conf'):

        config = configparser.ConfigParser()
        file = config.read(configuration_file)

        if file:
            return config['DONATION_BOX']['remote_database_hostname']
        else:
            print(Fore.RED + "Configuration file \""+configuration_file+"\" doesn't exists!" + Style.RESET_ALL)
            exit(1)


    @staticmethod
    def get_sqlite_database_path(configuration_file='donationbox.conf'):

        config = configparser.ConfigParser()
        file = config.read(configuration_file)

        if file:
            return config['DONATION_BOX']['sqlite_database_path']
        else:
            print(Fore.RED + "Configuration file \""+configuration_file+"\" doesn't exists!", end="")
            print( ""+ Style.RESET_ALL)
            exit(1)


    @staticmethod
    @abstractmethod
    def get_database_version():
        pass


    @abstractmethod
    def get_last_modified_datetime(self):
        pass


    @abstractmethod
    def get_last_modified_datetime_of_projects(self):
        pass


    @abstractmethod
    def view_all_records(self):
        pass
