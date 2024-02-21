import sys
import json
from datetime import datetime, date
from pyzkaccess import ZKAccess
from pyzkaccess.tables import User

def main():
    # Read JSON input from PHP
    input_data = json.loads(sys.stdin.read())

    # Extract values from JSON input
    card = input_data.get('card', '')
    group = input_data.get('group', '')
    pin = input_data.get('pin', '')
    password = input_data.get('password', '')
    super_authorize = input_data.get('super_authorize', False)
    start_time_timestamp = input_data.get('start_time', 0)
    end_time_timestamp = input_data.get('end_time', 0)

    # Convert timestamps to datetime.date objects
    start_time_date = date.fromtimestamp(start_time_timestamp)
    end_time_date = date.fromtimestamp(end_time_timestamp)

    # Create ZKAccess instance
    zk = ZKAccess('protocol=TCP,ipaddress=192.168.1.201,port=4370,timeout=12000,passwd=')

    # Create User instance with start_time and end_time as datetime.date objects
    my_user = User(
        card=card,
        group=group,
        pin=pin,
        password=password,
        super_authorize=super_authorize,
        start_time=start_time_date,
        end_time=end_time_date
    ).with_zk(zk)

    # Save the User
    my_user.save()

if __name__ == "__main__":
    main()
