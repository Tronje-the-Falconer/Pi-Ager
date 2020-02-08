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
import keyring.backend

from cryptography.hazmat.primitives import hashes
from cryptography.hazmat.primitives.kdf.pbkdf2 import PBKDF2HMAC
from cryptography.hazmat.backends import default_backend

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
        
        self.pi_serial = self.get_serial()
        self.kdf = PBKDF2HMAC(
            algorithm=hashes.SHA256(),
            length=32,
            salt=self.pi_serial,
            iterations=100000,
            backend=backend
        )
        
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
    

    def encrypt(self, password):
        

        encrypted_secret = self.kdf.derive(password)

        
        return(encrypted_secret)

    def decrypt(self, encrypted_secret):


        # Generate the key from the user's password
        password = base64.b64encode(kdf.derive(self.pi_serial))

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
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        cl_fact_help_crypt.__o_instance = i_instance
        
    @classmethod        
    def get_instance(self):
        """
        Factory method to get the helper crypt instance
        """        
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        if cl_fact_help_crypt.__o_instance is not None:
            return(cl_fact_help_crypt.__o_instance)
        cl_fact_help_crypt.__o_instance = cl_help_crypt(Exception)
        return(cl_fact_help_crypt.__o_instance)

    def __init__(self):
        """
        Constructor logic messenger factory
        """        
        # logger.debug(pi_ager_logging.me())
        cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
        pass    
    