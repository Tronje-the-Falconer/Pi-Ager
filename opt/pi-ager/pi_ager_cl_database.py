from abc import ABC
import inspect
import sqlite3
import pi_ager_names
import pi_ager_paths
import pi_ager_logging



from pi_ager_cx_exception import *

global logger
logger = pi_ager_logging.create_logger(__name__)

class cl_db_database_mysql:
    def read_data_from_db(self, i_select_statement):
        # Builds a dict of dicts it_table from mysql db
        it_table = {}
        
        return it_table

class cl_db_database_sqlite:
    
    def read_data_from_db(self, i_select_statement):
        """
        Read from db
        """
        
        
        # Builds a dict of dicts it_table from sqlite db
        it_table = {}
        #conn = sqlite3.connect(self.garden_db_path)
        connection = sqlite3.connect(pi_ager_paths.sqlite3_file, isolation_level=None, timeout = 10)
        # Need to allow write permissions by others
        connection.row_factory = sqlite3.Row
        cursor = connection.cursor()
        logger.debug('Select statement = ' + i_select_statement)
        query = cursor.execute(i_select_statement)
        logger.debug('Select query = ' + str(query))
        #db_table = cursor.fetchall()
        #logger.debug('DB table =' + str(db_table))
        #connection.close()
        # Building dict from table rows
         
        colname = [ d[0] for d in query.description ]
        it_table = [ dict(zip(colname, r)) for r in query.fetchall() ] 

        logger.debug('it table =' + str(it_table))
        return it_table
 

class cl_fact_database_config(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the database instance
        """
        logger.debug(pi_ager_logging.me())
        cl_fact_database_config.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the database instance
        """
        logger.debug(pi_ager_logging.me())
        if cl_fact_database_config.__o_instance is not None:
            return(cl_fact_database_config.__o_instance)
        cl_fact_database_config.__o_instance = cl_db_database_sqlite()
        return(cl_fact_database_config.__o_instance)

    def __init__(self):
        """
        Constructor email logic factory
        """
        logger.debug(pi_ager_logging.me())
        pass    
    
