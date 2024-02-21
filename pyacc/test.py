from pyzkaccess import ZKAccess
from pyzkaccess.tables import User

zk = ZKAccess(connstr='protocol=TCP,ipaddress=192.168.1.201,port=4370,timeout=4000,passwd=')


# Print superusers
superusers = zk.table('User').where(super_authorize=True)
print('Superusers are:')
for i in superusers:
    print('Card:', i.card, '; Group:', i.group, '; From/to:', i.start_time, '/', i.end_time)

# Print cards from first 3 unread transactions for a given type and door
txns = zk.table('Transaction').where(event_type=0, door=1).unread()[:3]
cards = ', '.join(txn.card for txn in txns)
print('First card numbers:', cards)

# Print the first transaction
qs = zk.table('Transaction')
if qs.count() > 0:
    print('The first transaction:', qs[0])
else:
    print('Transaction table is empty!')


# pyzkaccess connect 192.168.1.201 table User