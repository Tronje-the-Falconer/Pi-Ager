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

from cryptography.fernet import Fernet
from cryptography.hazmat.primitives.hashes import SHA256
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
        self.pi_serial = self.getserial() + self.getserial()
        
        #self.cipher_suite = Fernet(self.pi_serial)
        key = Fernet.generate_key()
        self.cipher_suite = Fernet(key)
        
    def getserial(self):
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
      return cpuserial
    

    def encrypt(self, secret):
        """
        # Generate a salt for use in the PBKDF2 hash
        salt = base64.b64encode(os.urandom(12))  # Recommended method from cryptography.io
        # Set up the hashing algo
        kdf = PBKDF2HMAC(
            algorithm=SHA256(),
            length=32,
            salt=str(salt),
            iterations=100000,  # This stretches the hash against brute forcing
            backend=default_backend(),  # Typically this is OpenSSL
        #)
        # Derive a binary hash and encode it with base 64 encoding
        hashed_pwd = base64.b64encode(kdf.derive(self.pi_serial))
        """
        #hashed_passwd = bcrypt.hashpw(passwd, self.pi_serial)
        return(self.cipher_suite.encrypt(secret))
        """
        # Set up AES in CBC mode using the hash as the key
        f = Fernet(hashed_pwd)
        encrypted_secret = f.encrypt(secret)
        
        return(encrypted_secret)
        """
    def decrypt(self, encrypted_secret):
        """
        # Set up the Key Derivation Formula (PBKDF2)
        kdf = PBKDF2HMAC(
            algorithm=SHA256(),
            length=32,
            salt=str(salt),
            #iterations=int(iterations),
            iterations=100000,
            backend=default_backend(),
        )
        # Generate the key from the user's password
        key = base64.b64encode(kdf.derive(pi_serial))
        
        # Set up the AES encryption again, using the key
        f = Fernet(key)
        
        # Decrypt the secret!
        secret = f.decrypt(encrypted_secret)
        return(secret)
        """
        retrun(self.cipher_suite.decrypt(encrypted_secret))
        
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
    