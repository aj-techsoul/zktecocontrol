from datetime import datetime, date
from pyzkaccess import ZKAccess
from pyzkaccess.tables import User

# Create a datetime object for start_time and end_time
start_time = datetime(2024, 2, 19, 0, 0, 0)  # Replace with the desired start time
end_time = datetime(2024, 2, 20, 0, 0, 0)    # Replace with the desired end time

# Convert datetime objects to timestamp
start_time_timestamp = int(start_time.timestamp())
end_time_timestamp = int(end_time.timestamp())

# Create ZKAccess instance
zk = ZKAccess('protocol=TCP,ipaddress=192.168.1.201,port=4370,timeout=12000,passwd=')

# Convert timestamps to datetime.date objects
start_time_date = date.fromtimestamp(start_time_timestamp)
end_time_date = date.fromtimestamp(end_time_timestamp)

# Create User instance with start_time and end_time as datetime.date objects
my_user = User(
    card='14232221',
    group='1',
    pin='1234',
    password='1234',
    super_authorize=True,
    start_time=start_time_date,
    end_time=end_time_date
).with_zk(zk)

# Save the User
my_user.save()
