import sys
from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger

from messenger.pi_ager_cl_crypt import cl_fact_help_crypt

password = sys.argv[1]
print("Password             = " + password)
crypt = cl_fact_help_crypt.get_instance()
print("Encrypt")
encrypted_secret = crypt.encrypt(password).decode("utf-8") 
print("Secret = " + encrypted_secret)
print("Decrypt")

decrypted_secret = crypt.decrypt(encrypted_secret)
print(decrypted_secret)

