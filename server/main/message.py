from datetime import datetime
from main.db import Model

class Message(Model):    
    
    def __init__(self, chatid, uid):
        self.chatid = chatid
        self.uid = uid
        self.created_at = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        self.text = ''
        self.media_type = 'text/plain'
        self.media_url = ''
        
    def attributes(self):
        return self.__dict__


