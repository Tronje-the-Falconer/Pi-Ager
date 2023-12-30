# -*- coding: utf-8 -*-
 
"""This class is for handling the different databases."""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"
from abc import ABC, abstractmethod
import inspect
import sqlite3
import pi_ager_names
import pi_ager_paths
from main.pi_ager_cl_logger import cl_fact_logger

from main.pi_ager_cx_exception import *
# from ilock import ILock
import globals

# global logger
# logger = pi_ager_logging.create_logger(__name__)
class cl_ab_database_config(ABC):
    __o_dirty = True
    def __init__(self):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        # data = self.read_data_from_db()
        pass
        
    @abstractmethod
    def build_select_statement(self):
        pass

    def get_data(self):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        # if self.is_dirty() is True:
        self.data = self.read_data_from_db()
            
        return(self.data)
    
    def read_data_from_db(self):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        """
        Read from db
        """
        database_config = cl_fact_database_config().get_instance()
        it_table = database_config.read_data_from_db(self.build_select_statement())
        # cl_ab_database_config.__o_dirty = False
        
        return it_table
    
    def write_data_to_db(self, it_table):
        # values = tuple(self.data.values())  # should be it_table
        # database_config = cl_fact_database_config().get_instance()
        # it_table = database_config.write_data_to_db(self.build_insert_statement(), values)  # does not return anything
        # cl_db_email_recipient.__o_dirty = False
        pass
        
    def is_dirty(self):
#       cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
       return(cl_ab_database_config.__o_dirty)
#       pass

class cl_db_database_sqlite:
    def __init__(self):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass
        
        
    def connect(self):
        # cl_fact_logger.get_instance().debug('After lock.acquire')
        
        #Enable shared chache
        sqlite3.enable_shared_cache(True)
        self.connection = sqlite3.connect(pi_ager_paths.sqlite3_file, isolation_level=None, timeout = 10)
        # Set journal mode to WAL (Write-Ahead Log)
        self.connection.execute('PRAGMA journal_mode = wal')
        self.connection.execute('PRAGMA synchronous = OFF')
        self.connection.execute('PRAGMA read_uncommitted = True')
        # Need to allow write permissions by others
        self.connection.row_factory = sqlite3.Row
        self.cursor = self.connection.cursor()    
    
    def commit(self):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self.connection.commit() 
        
    def read_data_from_db(self, i_select_statement):
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        """
        Read from db
        """
        it_table = {}
        with globals.lock:
            self.connect()
            # Builds a dict of dicts it_table from sqlite db

            # cl_fact_logger.get_instance().debug('Select statement = ' + i_select_statement)
            query = self.cursor.execute(i_select_statement)
            # cl_fact_logger.get_instance().debug('Select query = ' + str(query))
        
            colname = [ d[0] for d in query.description ]
            it_table = [ dict(zip(colname, r)) for r in query.fetchall() ] 
            self.connection.close()

        # cl_fact_logger.get_instance().debug('read_data_from_db : after lock.release')
        #cl_fact_logger.get_instance().debug('it table =' + str(it_table))
        return it_table
 
    def write_data_to_db(self, i_insert_statment):
        with globals.lock:
            self.connect()
            # cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
            self.cursor.execute(i_insert_statment)
            self.commit()
            self.connection.close()

        # cl_fact_logger.get_instance().debug('write_data_to_db : after lock.release')
        pass
        

class cl_fact_database_config(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the database instance
        """
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_database_config.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the database instance
        """
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_database_config.__o_instance is not None:
            return(cl_fact_database_config.__o_instance)
        cl_fact_database_config.__o_instance = cl_db_database_sqlite()
        return(cl_fact_database_config.__o_instance)

    def __init__(self):
        """
        Constructor email logic factory
        """
#        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    
