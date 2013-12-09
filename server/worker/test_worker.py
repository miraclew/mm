import nsq

def task1(message):
    print message
    return True

def task2(message):
    print message
    return True

all_tasks = {"task1": task1, "task2": task2}
r = nsq.Reader(all_tasks, lookupd_http_addresses=['http://127.0.0.1:4161'], 
        topic="mytopic", channel="channel1")
nsq.run()
