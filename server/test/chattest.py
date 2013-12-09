'''
Created on May 27, 2013

@author: aaaa
'''
import unittest
import sys,os

rootpath = os.path.abspath(os.path.join(os.path.dirname(os.path.abspath(__file__)), '../'))
sys.path.insert(0, os.path.abspath(rootpath))

from handler.socket_handler import SocketHandler
from main.chat import Chat
from main.user import User

class Test(unittest.TestCase):

    def testAll(self):
        u1 = User.get(100006)
        
        u2 = User.get(100006)
        
        u3 = User.get(100007)
        
        self.assertEqual(u1, u2, 'msg1')
        self.assertNotEqual(u1, u3, 'msg 2')

        u1.add_connection(SocketHandler())
        self.assertEqual(1, len(u1.connections()), 'xxx')

if __name__ == "__main__":
    #import sys;sys.argv = ['', 'Test.testAll']
    unittest.main()