import sys
import json
from pyzkaccess import ZKAccess
from pyzkaccess.tables import User

def delete_user(card_number):
    zk = ZKAccess('protocol=TCP,ipaddress=192.168.1.201,port=4370,timeout=4000,passwd=')
    zk.table('User').where(card=card_number).delete_all()

def main():
    try:
        # Read JSON data from standard input
        input_data = json.loads(sys.stdin.read())

        # Extract card number from input data
        card_number = input_data.get('card')

        # Check if card number is provided
        if card_number is not None:
            delete_user(card_number)
            print('User deleted successfully')
        else:
            print('Card number not provided. No action taken.')

    except json.JSONDecodeError:
        print('Error decoding JSON input.')

if __name__ == "__main__":
    main()
