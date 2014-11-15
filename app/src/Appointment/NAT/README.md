## Next Available Timeslots (NAT)

The idea is to prebuild the working calendar of all employees of all business
users. While building, take default working time of the shop, default working
time of employee, free time and custom time into account. When getting NATs,
just need to provide business user ID and the current time (in timestamp).

## Data structure

Working calendar (or NAT calendar) of a business user is stored in
[a sorted set](http://redis.io/commands#sorted_set) in the name of `user:$userId:nat`.
Members of sorted set have timestamp as score. Member key is named as
`$employeeId:$timestamp` since we cannot set multiple scores for the same
member.

For example,
```
user:165:nat
    |---------------------------------------------------------------------------
    |             Member              Score
    |---------------------------------------------------------------------------
    |         306:1416214800        1416214800
    |         306:1416215700        1416215700
    |         306:1416216600        1416216600
    |         306:1416217500        1416217500
    |         306:1416218400        1416218400
    |         306:1416219300        1416219300
    |         306:1416220200        1416220200
    |         306:1416221100        1416221100
    |         306:1416222000        1416222000
    |         306:1416222900        1416222900
    |         306:1416223800        1416223800
    |         306:1416224700        1416224700
    |         306:1416225600        1416225600
    |         306:1416226500        1416226500
    |         306:1416227400        1416227400
    |         306:1416228300        1416228300
    |         306:1416229200        1416229200
    |         306:1416230100        1416230100
    |         306:1416231000        1416231000
    |         306:1416231900        1416231900
    |         306:1416232800        1416232800
    |         306:1416233700        1416233700
    |         306:1416234600        1416234600
    |         306:1416235500        1416235500
    |         306:1416236400        1416236400
    |         306:1416237300        1416237300
    |         306:1416238200        1416238200
    |         306:1416239100        1416239100
    |         306:1416240000        1416240000
    |
    v
  Time
```

## To run the command to build NAT manually

`php artisan varaa:build-nat`

This command is scheduled to run every 96 hours (4 days).

## How to deploy?

Just run this into the terminal.
`nohup php /path/to/artisan queue:work --queue=varaa:nat --daemon > /dev/null 2>&1 &`


## Factors that could change NAT calendar:

By default, the NAT calendar is built based on employee's working time of a date
and existing bookings of that date. There are some events that can affect the
NAT calendar.

### Change parts of the NAT calendar

* Booking status changes to CONFIRM
* Booking status changes to CANCELLED
* Bookings are deleted
* Employees has a free time
* Free time of an employee is removed

**Solution:** To remove related members in NAT calendar, or to restore.

### Change the whole NAT calendar

* Business changes default working time
* Business changes workshift planning
* Change default time of an employee

**Solution:** Trigger to build the whole NAT calendar of that user.
