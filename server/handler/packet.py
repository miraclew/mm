import json

class Packet(object):
    C_SESSION_CREATE        = 'session.create'
    C_SESSION_DELETE        = 'session.delete'
    C_CHAT_CREATE           = 'chat.create'
    C_CHAT_MESSAGE_CREATE   = 'chat.message.create'
    C_CHAT_MEMBER_CREATE    = 'chat.memeber.create'
    C_CHAT_MEMBER_DELETE    = 'chat.members.delete'
    
    def __init__(self, _type, data):
        self.type = _type
        self.data = data
    
    @classmethod
    def load(cls, jsonstr):
        msg = json.loads(jsonstr)
        return Packet(msg['type'], msg['data'])
    
    def dump(self):
        return json.dumps({'type':self.type, 'data':self.data})
