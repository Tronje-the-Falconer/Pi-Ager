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
from influxdb import InfluxDBClient
import pi_ager_names
import pi_ager_paths
#import pi_ager_logging
from main.pi_ager_cl_logger import cl_fact_logger


from main.pi_ager_cx_exception import *

# global logger
# logger = pi_ager_logging.create_logger(__name__)
class cl_ab_database_config(ABC):
    __o_dirty = True
    def __init__(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        data = self.read_data_from_db()
        pass
    @abstractmethod
    def build_select_statement(self):
        pass

    def get_data(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if self.is_dirty() is True:
            self.data = self.read_data_from_db()
            
        return(self.data)
    
    def read_data_from_db(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        """
        Read from db
        """
        database_config = cl_fact_database_config().get_instance()
        it_table = database_config.read_data_from_db(self.build_select_statement())
        cl_ab_database_config.__o_dirty = False
        
        return it_table

    def is_dirty(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        return(cl_ab_database_config.__o_dirty)
        pass
    
class cl_db_database_mysql:
    def read_data_from_db(self, i_select_statement):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        # Builds a dict of dicts it_table from mysql db
        it_table = {}
        
        return it_table
class cl_db_influxdb:
    def __init__(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        host='localhost'
        port=8086
        user = 'admin'
        password = 'raspberry'
        dbname = 'pi-ager'
        #protocol = 'line'
        try:
            self.client =  InfluxDBClient(host, port, user, password, dbname)
        except InfluxDBServerError as cx_error:
            logger.debug("Unable to connect to InfluxDB!")
            cl_fact_logic_messenger().get_instance().handle_exception(cx_error)
        cl_fact_logger.get_instance().debug("Create database: " + dbname)
        self.client.create_database(dbname)
        

    def read_data_from_db(self, i_select_statement):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        """
        Read from db
        """
        # Builds a dict of dicts it_table from sqlite db
        it_table = {}
        cl_fact_logger.get_instance().debug('Select statement = ' + i_select_statement)
        query = self.cursor.execute(i_select_statement)
        cl_fact_logger.get_instance().debug('Select query = ' + str(query))

        colname = [ d[0] for d in query.description ]
        it_table = [ dict(zip(colname, r)) for r in query.fetchall() ] 

        cl_fact_logger.get_instance().debug('it table =' + str(it_table))
        return it_table
 
    def write_data_to_db(self, i_insert_statment):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        self.client.write_points(i_insert_statment)
        
        pass  

class cl_db_database_sqlite:
    def __init__(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        connection = sqlite3.connect(pi_ager_paths.sqlite3_file, isolation_level=None, timeout = 10)
        # Need to allow write permissions by others
        connection.row_factory = sqlite3.Row
        self.cursor = connection.cursor()
    def read_data_from_db(self, i_select_statement):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        """
        Read from db
        """
        
        # Builds a dict of dicts it_table from sqlite db
        it_table = {}

        cl_fact_logger.get_instance().debug('Select statement = ' + i_select_statement)
        query = self.cursor.execute(i_select_statement)
        cl_fact_logger.get_instance().debug('Select query = ' + str(query))
        
        colname = [ d[0] for d in query.description ]
        it_table = [ dict(zip(colname, r)) for r in query.fetchall() ] 

        cl_fact_logger.get_instance().debug('it table =' + str(it_table))
        return it_table
 
    def write_data_to_db(self, i_insert_statment, values):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cursor.execute(i_insert_statment, values)
        pass  
class cl_fact_db_influxdb(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the database instance
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_db_influxdb.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the database instance
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_db_influxdb.__o_instance is not None:
            return(cl_fact_db_influxdb.__o_instance)
        cl_fact_db_influxdb.__o_instance = cl_db_influxdb()
        return(cl_fact_db_influxdb.__o_instance)

    def __init__(self):
        """
        Constructor email logic factory
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    
class cl_fact_database_config(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the database instance
        """
        #logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_database_config.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the database instance
        """
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_database_config.__o_instance is not None:
            return(cl_fact_database_config.__o_instance)
        cl_fact_database_config.__o_instance = cl_db_database_sqlite()
        return(cl_fact_database_config.__o_instance)

    def __init__(self):
        """
        Constructor email logic factory
        """
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    
