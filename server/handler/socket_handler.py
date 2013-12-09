import tornado.websocket
from main.user import User    
 
class SocketHandler(tornado.websocket.WebSocketHandler):
    def __init__(self, application, request, **kwargs):
        tornado.websocket.WebSocketHandler.__init__(self,application, request, **kwargs)
        self.user = None
        
    def open(self, uid):
        self.uid = int(uid);
        u = User.get(self.uid)
        u.add_connection(self)
        self.user = u
        print "%s open connection, total=%d" % (self.uid, len(u.connections()))

    def on_message(self, msg):
        pass
#        packet = Packet.load(msg)
        
    def on_close(self):
        print "%d close connection" % self.user.uid
        self.user.remove_connection(self)
        self.user = None
        