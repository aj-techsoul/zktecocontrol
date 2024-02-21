# zktecocontrol
PHP Class to work ZkTeco C3-100/C3-200/C3-400 with pyzkaccess python plugins. 

Steps:

1. First install Python in the System (python >= 3.5) 
2. Install PyzkAccess  // Documentation: https://bdragon300.github.io/pyzkaccess/
   ```pip install pyzkaccess```
4. Use the given PHP class file.

Helping Files:
1. Incase your Python is not global installed then a batch file added, you can use that to make it global

Examples:

$zk = new zktecocontrol;

echo $zk->run("User",true);

echo $zk->run('pyzkaccess connect 192.168.1.201 table User');

echo $zk->run("--version");

echo $zk->pyrun("test_add.py");

echo $zk->pyrun("test.py");

echo $zk->run('pyzkaccess connect 192.168.1.201 relays switch_on');

echo $zk->pyzcontrol('opendoor');

echo $zk->pyzuser("User");

echo $zk->pycmd('python pyacc/adduser.py', '{"card": "14322222", "group": "1", "pin": "103", "password": "1234", "super_authorize": true, "start_time": '.strtotime("2024-02-21 
00:00").', "end_time": '.strtotime("2024-02-21 23:59").'}');

echo $zk->pycmd('python pyacc/deluser.py', '{"card": "14322222"}');
