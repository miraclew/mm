'''
Created on May 27, 2013

@author: aaaa
'''
import unittest
import sys,os

rootpath = os.path.abspath(os.path.join(os.path.dirname(os.path.abspath(__file__)), '../'))
sys.path.insert(0, os.path.abspath(rootpath))

from main.user import User

class Test(unittest.TestCase):

    def testFindAll(self):
        users = User.find_all('name=%s', 'wanwei')
        self.assertEqual(1, len(users), 'msg')

    def testDB(self):
        user = User.find(1)
        print 'name:'+user.name
        print 'avatar:'+user.avatar
        self.assertEqual(user.id, 1, 'id not present')
        
    def testAll(self):
        u1 = User.get(1)
        u2 = User.get(2)
        
        self.assertEqual(False, u1.is_online, 'u1 is online')
        u3 = User.login('miraclew', '123')
        self.assertEqual(u1.id, u3.id, 'id not equals')
        self.assertEqual(True, u1.is_online, 'u1 should online')
        self.assertEqual(False, u2.is_online, 'u2 should not online')
        self.assertEqual(2, len(User.all_users), 'all users')

if __name__ == "__main__":
    #import sys;sys.argv = ['', 'Test.testAll']
    unittest.main()