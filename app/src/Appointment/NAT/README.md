## To run the command to build NAT manually

`php artisan varaa:build-nat`

This command is scheduled to run every 4 days.

## How to deploy?

`php artisan queue:work --queue=varaa:nat --daemon`


## Factors that could change NAT calendar:
### Change parts of the NAT calendar

* Booking status changes to CONFIRM
* Booking status changes to CANCELLED
* Bookings are deleted
* Employees has a free time
* Free time of an employee is removed

### Change the whole NAT calendar

* Business changes default working time
* Business changes workshift planning
* Change default time of an employee
