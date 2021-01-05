import sys
from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger
from messenger.pi_ager_cl_email_server import cl_fact_db_email_server
from messenger.pi_ager_cl_crypt import cl_fact_help_crypt
from main.pi_ager_cl_logger import cl_fact_logger


cl_fact_logger.get_instance().debug(cl_fact_logger.get_instance().me())
cl_fact_logger.get_instance().debug('Encrypt password called.)

password = sys.argv[1]
print("Password             = " + password)
crypt = cl_fact_help_crypt.get_instance()
print("Encrypt")
encrypted_secret = crypt.encrypt(password).decode("utf-8") 
cl_fact_logger.get_instance().debug('Encrypted secret is ' + encrypted_secret )
cl_fact_db_email_server().get_instance().update_password(encrypted_secret)



print("Secret = " + encrypted_secret)
print("Decrypt")

decrypted_secret = crypt.decrypt(encrypted_secret)
print(decrypted_secret)

