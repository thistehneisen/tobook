## Virtual Calendar

This command is used to prebuild the possible bookings timeslot for all businesses. This timeslots list is used to guess if the business has any `last minute discount` or not. Because we need to check the current time with the bookable time to know if there is any discount for X next hours.

## Data structure

```
LRANGE user_62_16092015 0 -1
```

## To run the command to build virtual calendar manually

`php artisan varaa:build-vc`

This command is scheduled to run daily

## How to deploy?

Install Redis server

```
sudo add-apt-repository ppa:chris-lea/redis-server
sudo apt-get update
sudo apt-get install redis-server
```

Run this in the terminal.
`nohup php /path/to/artisan queue:work --queue=varaa:vc --daemon > /dev/null 2>&1 &`

If you encounter `Call to undefined method Redis::connection()` error message, make sure the PHP Redis extension is not installed or enabled.

