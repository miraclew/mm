import tornado.web
from main.session import Session, RedisSessionStore
import redis

class Application(tornado.web.Application):
    def __init__(self, handlers=None, default_host="", transforms=None, wsgi=False, **settings):
        tornado.web.Application.__init__(self, handlers, default_host, transforms, wsgi, **settings)
#        self.db_session = db_session
        self.redis = redis.StrictRedis()
        self.session_store = RedisSessionStore(self.redis)



class BaseHandler(tornado.web.RequestHandler):

    def get_current_user(self):
        return self.session['user'] if self.session and 'user' in self.session else None

    @property
    def session(self):
        sessionid = self.get_cookie('sid')
        session = Session(self.application.session_store, sessionid)
        if sessionid == None:
            self.set_cookie('sid', session.sessionid)
            
        return session
