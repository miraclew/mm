#coding=utf-8
from torndb import Connection
from settings import *
import inflect

class DB(object):
    @classmethod
    def conn(cls):
        return Connection(host=DB_HOST, database=DB_DATABASE, user=DB_USER, password=DB_PASSWORD)
    

class Model(object):
    pk = 'id'
#    def __init__(self):
#        print self.__class__.__name__
    @classmethod
    def find_all(cls, conditions='1=1', *parameters):
        db = DB.conn()
        table = inflect.engine().plural(cls.__name__).lower()
        rows = db.query("select * from "+table+" where " + conditions, *parameters)
        
        return [cls.load(row) for row in rows]
        
    @classmethod
    def find(cls, _id):
        db = DB.conn()
        table = inflect.engine().plural(cls.__name__).lower()
        row = db.get("select * from "+table+" where "+cls.pk+"=%s" , _id)
        if row == None:
            raise DBException()
        
        return cls.load(row)        
        
    @classmethod
    def load(cls, row):
        obj = cls()
        for (k,v) in  row.items():
            setattr(obj, k, v)
        
        return obj
    
    def save(self):
        pass


class DBException(Exception):
    '''DB Exception class'''
    NOT_EXIST,DUPLICATE = range(2)
    Errors = {}
    def __init__(self):
        Exception.__init__(self)
        
        