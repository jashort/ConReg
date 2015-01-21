#!/usr/bin/env python3

# Generate test registration data to import in to the ConReg database. Fakes the
# attendee data exported from the website (people who have pre-registered online)

from faker import name, internet, address, phone_number
import datetime
import random


def get_badge_name():
    n = internet.user_name()[0:15]
    n = n.replace('-', ' ')
    n = n.replace('.', ' ')
    n = n.replace('_', ' ')
    return n

fields = ['First Name', 'Last Name', 'Badge Name', 'Badge Number', 'Zipcode', 'Country',
          'Phone Number', 'Email Address', 'Birthdate', 'Emergency Contact Name',
          'Emergency Contact Phone', 'Parent is Emergency Contact', 'Parent Name', 'Parent Phone',
          'Paid', 'Amount', 'Pass Type', 'Order ID']

print('\t'.join(fields))
order_id = 1

for i in range(1, 10001):
    b_year = random.randint(1940, 2014)
    b_month = random.randint(1, 12)
    b_day = random.randint(1, 28)
    age = datetime.datetime.now() - datetime.datetime(b_year, b_month, b_day)
    age = int(age.days / 365.25)

    data = []
    data.append(name.first_name())
    data.append(name.last_name())
    if random.randint(1, 100) < 60:         # Random chance of having a badge name
        data.append(get_badge_name())
    else:
        data.append("")
    data.append("ONL{0:05d}".format(i))
    data.append(address.zip_code())
    data.append("United States of America")

    data.append(phone_number.phone_number())
    data.append(internet.email())
    data.append("{0:4d}-{1:02d}-{2:02d}".format(b_year, b_month, b_day))
    data.append(name.find_name())

    data.append(phone_number.phone_number())
    if age <= 17:
        data.append('Y')
        data.append(data[9])
        data.append(data[10])
    else:
        data.append('N')
        data.append("")
        data.append("")
    data.append("Y")
    if age >= 13:
        data.append(str(random.choice([45, 50, 55, 57])))    # 13+ full price
    elif 6 >= age < 13:
        data.append("45")     # Youth
    else:
        data.append("0")      # Children are free

    if random.randint(1, 1000) < 2: # Random chance of VIP membership
        data.append('VIP')
        data[15] = "300"
    else:
        data.append('Weekend')
    data.append(str(order_id))

    print('\t'.join(data))

    if random.randint(1, 100) < 80:
        order_id += 1
