from pyzkaccess import ZKAccess
from pyzkaccess.tables import User

zk = ZKAccess('protocol=TCP,ipaddress=192.168.1.201,port=4370,timeout=4000,passwd=')
zk.table('User').where(card='14232221').delete_all()
