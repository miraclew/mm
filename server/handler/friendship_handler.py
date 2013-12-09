'''
Created on May 27, 2013

@author: Wan Wei
'''
import redis
from handler.api_handler import ApiHandler
from main.friendship import Friendship
from main.user import User
from tornado.web import HTTPError

class FriendshipHandler(ApiHandler):
    
    def get(self):
        current_user = self.get_current_user()
        rows = Friendship.find_all('uid1=%s', current_user.id)
        friends = []
        for f in rows:
            u = User.find(f.uid2)
            friends.append({ 'uid': u.id, 'name':u.name, 'avatar': u.avatar })
            
        self.rs({'items': friends})

    def post(self):
        current_user = self.get_current_user()
        if current_user == None:
            raise HTTPError(401)
        
        a = self.get_argument('a', 'create', True)
        uid = self.get_argument('uid')
        
        r = redis.StrictRedis()
        k = "friendship:%s" % current_user.id
        if a == 'create':            
            r.sadd(k, uid)
            self.rs(None)
        elif a == 'delete':
            r.srem(k, uid)
            self.rs(None)
        