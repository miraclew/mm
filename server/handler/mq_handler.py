'''
Created on May 31, 2013

@author: Wan Wei
'''
from handler.api_handler import ApiHandler
import json
from main.chat import Chat

class MQHandler(ApiHandler):

    def post(self):        
        msg = self.get_argument('msg')
        print msg
        message = json.loads(msg);
        
        chat = Chat.get(message['chatid']);
        chat.send_message(message)
        
        self.rs()
        