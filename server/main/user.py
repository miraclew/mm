#coding=utf-8
from main.db import Model
import json

class User(Model):
    all_users = {}
    pk = 'uid'
    def __init__(self):
        self.is_online = False
        self._connections = []

    @classmethod
    def get(cls, _id):
        uid = int(_id)
        if cls.all_users.has_key(uid):
            user = cls.all_users[uid]            
        else:
            user = cls.find(uid)
            cls.all_users[uid] = user
#        print uid, user
        return user

    def connections(self):
        return self._connections
    
    def add_connection(self, conn):
        self._connections.append(conn)
        self.is_online = True
    
    def remove_connection(self, conn):
        self._connections.remove(conn)
        if len(self._connections) <= 0:
            self.is_online = False
    
    def push_message(self, msg):
        if int(msg['uid']) == int(self.uid):
            return
        
        print "push message %d => %d, connections=%d" % (msg['uid'], self.uid, len(self._connections))
        if not self.is_online:
            return
        
        for c in self._connections:
            c.write_message(json.dumps(msg))
            
            