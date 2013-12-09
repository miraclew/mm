'''
Created on May 27, 2013

@author: Wan Wei
'''
from handler.api_handler import ApiHandler

class UsersHandler(ApiHandler):
    
    def get(self):
        self.write('hello get')

    def post(self):
        self.write('hello post')
    
    