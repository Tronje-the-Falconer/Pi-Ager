import sys
from main.pi_ager_cx_exception import *
from main.pi_ager_cl_logger import cl_fact_logger
from messenger.pi_ager_cl_email_server import cl_fact_db_email_server
from messenger.pi_ager_cl_crypt import cl_fact_help_crypt
from main.pi_ager_cl_logger import cl_fact_logger
#from Demos.security.sspi.validate_password import password
 

cl_fact_logger.get_instance().debug('Encrypt password called.')

smtp_password = sys.argv[1]
print("Password             = " + smtp_password)
cl_fact_logger.get_instance().debug('Password = ' + smtp_password)
crypt = cl_fact_help_crypt.get_instance()
print("Encrypt")
encrypted_secret = crypt.encrypt(smtp_password).decode("utf-8") 
cl_fact_logger.get_instance().debug('Encrypted secret is ' + encrypted_secret )
cl_fact_db_email_server().get_instance().update_password(encrypted_secret)



print("Secret = " + encrypted_secret)
print("Decrypt")

decrypted_secret = crypt.decrypt(encrypted_secret)
print(decrypted_secret)

