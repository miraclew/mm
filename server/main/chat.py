#coding=utf-8
import redis
from main.user import User

class Chat:
    all_chats = {}
    
    @classmethod
    def get(cls, _id):
        if cls.all_chats.has_key(_id):
            return cls.all_chats[_id]
        else:
            chat = Chat(_id)
            cls.all_chats[_id] = chat
            return chat 
        
    def __init__(self, _id):
        self.id = _id
        #load members
        r = redis.StrictRedis()
        self._members = r.smembers("chat:%d" % self.id)
#        print self._members            
        
    def join(self, uid):
        self._members.add(uid)
        
    def leave(self,uid):
        self._members.remove(uid)
        
    def send_message(self, msg):
        print "send msg to %d members" % len(self._members) 
        # deliver message
        for c in self._members:
            u = User.get(c)
            u.push_message(msg)
