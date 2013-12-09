'''
Created on May 28, 2013

@author: Wan Wei
'''
import json
import datetime,time
from main.application import BaseHandler

class ApiHandler(BaseHandler):
    
    def __init__(self, application, request, **kwargs):
        BaseHandler.__init__(self,application, request, **kwargs)
        self.set_header('Content-Type', 'application/json; charset=utf-8')
        
#        if self.get_current_user() == None:
#            self.rf(-1, {})

    def rs(self, data=None):
        
        response = json.dumps({'code':0, 'data': self._data(data) }, cls = MyEncoder)
        self.write(response)
        
    def rf(self, code, data=None):
        response = json.dumps({'code':code, 'data': self._data(data) }, cls = MyEncoder)
        self.write(response)
    
    def _data(self, data):
        if isinstance(data, object) and hasattr(data, '__dict__'):
            return data.__dict__
        else:
            return data
        
class MyEncoder(json.JSONEncoder):

    def default(self, obj):
        if isinstance(obj, datetime.datetime):
            return int(time.mktime(obj.timetuple()))

        return json.JSONEncoder.default(self, obj)