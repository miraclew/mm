'''
Created on May 28, 2013

@author: Wan Wei
'''
from handler.api_handler import ApiHandler
from main.user import User

class SessionHandler(ApiHandler):
    
    def get(self):
        self.rs_user(self.get_current_user())
    
    def post(self):
        a = self.get_argument('a', 'login')
        if a == 'login':
            name = self.get_argument('name')
            password = self.get_argument('password')
            user = User.login(name, password)
            if user == None:
                self.rf(-1, {})
            else:
                self.session['user'] = user                
                self.rs_user(user)
                
        else:
            current_uesr = self.get_current_user()
            if current_uesr != None:
                self.session.clear()
            self.rs(None)
    
    def rs_user(self, user):
        if user != None:
            data = {'id': user.id, 'avatar': user.avatar, 'name': user.name }
            self.rs(data)
        else:
            self.rs(None)