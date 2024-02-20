# -*- coding: utf-8 -*-

"""This class is for encryt and decryt a string depending on the serial number of the Raspberry PI."""
"""e.g. E-Mail password"""

__author__ = "Claus Fischer"
__copyright__ = "Copyright 2019, The Pi-Ager Project"
__credits__ = ["Claus Fischer"]
__license__ = "GPL"
__version__ = "1.0.0"
__maintainer__ = "Claus Fischer"
__email__ = "DerBurgermeister@pi-ager.org"
__status__ = "Production"

import os
import base64
# import keyring.backend

from cryptography.fernet import Fernet
#from cryptography.hazmat.primitives import hashes
#from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2HMAC
#from cryptography.hazmat.backends import default_backend

from abc import ABC
import inspect
import traceback

from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger

     

class cl_help_crypt:

    def __init__(self, cx_error):
        """
        Constructor for the help_crypt class
        """ 
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if "get_instance" not in inspect.stack()[1][3]:
            raise cx_direct_call("Please use factory class")
        
        self.cx_error  = cx_error
        #self.backend = default_backend()
        #self.pi_serial = self.get_serial()
        self.get_key()
        cl_fact_logger.get_instance().debug('Sytemkey = ' + self.key.decode())
    """        
    def get_serial(self):
      # Extract serial from cpuinfo file
      cpuserial = "0000000000000000"
      try:
        f = open('/proc/cpuinfo','r')
        for line in f:
          if line[0:6]=='Serial':
            cpuserial = line[10:26]
        f.close()
      except:
        cpuserial = "ERROR000000000"
        cl_fact_logic_messenger().get_instance(cx_error).send()
        
      cl_fact_logger.get_instance().debug(cpuserial.encode('utf-8'))
      return (cpuserial.encode('utf-8'))
    """    
    def get_key(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        try:
            with open('/home/pi/system_key.bin', 'rb') as file_object:
                for line in file_object:
                    self.key = line
        except:
            self.generate_key()
            self.write_key()
        pass
    def write_key(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        with open('/home/pi/system_key.bin', 'wb') as file_object:  file_object.write(self.key)
        pass
    def generate_key(self):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        self.key = Fernet.generate_key()
        cl_fact_logger.get_instance().debug("Generated key: " + self.key.decode('utf-8'))
        return(self.key)

    def encrypt(self, password):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cipher_suite = Fernet(self.key)
        encrypted_secret = cipher_suite.encrypt(password.encode('utf-8'))
        #cl_fact_logger.get_instance().debug('Encrypted_secret = ' + encrypted_secret)
        

        
        return(encrypted_secret)
    def decrypt(self, encrypted_secret):
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        
        cipher_suite = Fernet(self.key)
        password = cipher_suite.decrypt(encrypted_secret.encode('utf-8'))
        #cl_fact_logger.get_instance().debug('Decrypted_secret = ' + password)

        return(password)
    
class th_help_crypt(cl_help_crypt):   
       
    def __init__(self):
        pass


class cl_fact_help_crypt(ABC):
    __o_instance = None
    
    @classmethod
    def set_instance(self, i_instance):
        """
        Factory method to set the helper crypt instance
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_help_crypt.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the helper crypt instance
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_help_crypt.__o_instance is not None:
            return(cl_fact_help_crypt.__o_instance)
        cl_fact_help_crypt.__o_instance = cl_help_crypt(Exception)
        return(cl_fact_help_crypt.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    