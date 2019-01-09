 

class Error(Exception):
    """Base class for other exceptions"""
    pass

class cx_Sensor_not_defined(Error):
    pass

class cx_direct_call(Error):
    def __init__(self, message, errors):

        # Call the base class constructor with the parameters it needs
        super().__init__(message)

        # Now for your custom code...
        self.errors = errors

