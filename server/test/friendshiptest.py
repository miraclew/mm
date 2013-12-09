'''
Created on May 28, 2013

@author: aaaa
'''
import unittest
import sys,os

rootpath = os.path.abspath(os.path.join(os.path.dirname(os.path.abspath(__file__)), '../'))
sys.path.insert(0, os.path.abspath(rootpath))

from main.friendship import Friendship


class Test(unittest.TestCase):


    def testAll(self):
        fs = Friendship.find_all()
        self.assertEqual(2, len(fs), 'length is not 2')
        for x in fs:
            print str(x.uid1) + '=>' + str(x.uid2)
        


if __name__ == "__main__":
    #import sys;sys.argv = ['', 'Test.testAll']
    unittest.main()