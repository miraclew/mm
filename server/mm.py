#!/usr/bin/env python
import tornado.web
import tornado.options
import os
import argparse
import logging
from handler.socket_handler import SocketHandler
from handler.users_handler import UsersHandler
from handler.friendship_handler import FriendshipHandler
from main.application import Application
from handler.session_handler import SessionHandler
from handler.mq_handler import MQHandler

logger = logging.getLogger('gateway')
args = None

def parse_args():
    global args
    static_path = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'static'))
    parser = argparse.ArgumentParser(description='Gateway server')

    parser.add_argument('-v', '--verbose', help='verbose logging', action='store_true')
    parser.add_argument('-s', '--static-path', help='path for static files [default: %(default)s]', default=static_path)
    parser.add_argument('-p', '--listen-port', help='port to listen on [default: %(default)s]', default=9001, type=int, metavar='PORT')
    parser.add_argument('-i', '--listen-interface', help='interface to listen on. [default: %(default)s]', default='0.0.0.0', metavar='IFACE')
    args = parser.parse_args()
    
def main():
    global logger
    #tornado.options.parse_command_line()
    parse_args()

    if args.verbose:
        tornado.options.enable_pretty_logging()
        logger = logging.getLogger()
        logger.setLevel(logging.INFO)

    settings = {'cookie_secret': True, 'debug': True,"xsrf_cookies": False ,
                'template_path': os.path.join(os.path.dirname(__file__), "templates"),
                'static_path': os.path.join(os.path.dirname(__file__), "static"), 
                }

    application = Application([
        (r"/ws/(.*)", SocketHandler),
        (r"/mq", MQHandler),
        (r"/users", UsersHandler),
        (r"/session", SessionHandler),
        (r"/friendship", FriendshipHandler),
        (r"/(.*)", tornado.web.StaticFileHandler, {"path": args.static_path, "default_filename":'index.html'}),
    ],'',None,False, **settings
    )


    print "Listening on %s:%s" % (args.listen_interface, args.listen_port)
    application.listen(args.listen_port, args.listen_interface)
    tornado.ioloop.IOLoop.instance().start()

if __name__ == "__main__":
    main()
    
    
