#!/usr/bin/env python3
# -*- coding: utf-8 -*-

from abc import ABCMeta, abstractmethod


class Databases(metaclass=ABCMeta):

    def __init__(self):
        self.connection = None



    def close_connection(self):
        if self.connection:
            self.connection.close()


    @staticmethod
    @abstractmethod
    def get_database_version(self):
        pass


    @abstractmethod
    def get_last_modified_datetime(self):
        pass

