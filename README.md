# DA Tech Test
This project is a calculator module that generates costs for a courier service.
It calculates the total distance and total cost in a delivery run
## Requirements
| System Requirements          |
|------------------------------|
| x86 2GHz Duel Core Processor |
| 2GB RAM                      |

This Laravel 12 Project requires the following (Well it's what I built this API module on):

| Software Requirements |
|-----------------------|
| LAMP Stack            |
| Ubuntu 20.04.6        |
| Apache 2              |
| MySQL 8               |
| PHP 8.3               |
| composer 2.8.3        |

## Install Instructions
Clone the repository using the following command.
```
git clone git@github.com:UchuuJ/da-techtest.git
```

Change directory into `da-techtest` and run.
```
composer install
```

Once composer has finished installing the required dependencies, rename the example `.env.example` file and rename it to `.env`.
```
mv .env.example .env
```
Open `.env` in the editor of your choice and set up your database connection information.
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=da_techtest
DB_USERNAME=root
DB_PASSWORD=examplePass
```

Once done, save the file and open up mysql in your favourite editor. But for this guide we're going to use the commandline client.
```
mysql -uroot -pexamplePass
#Upon successful login 
CREATE DATABASE da_techtest;
\q
```

Once the database is set up, we should be close to launch. So now we need to run the migratiosn
```php
php artisan migrate
```
When the migration is finished we're going to launch artisan's laravel php server
```
php artisan serve
```

Voila we have installed and set up the application.

## Usage Guide
In order to use the calculator we recommend you use [Postman](https://postman.com)
I have included two postman collection requests for testing. 
These requests can be found in the root of the repository `'da tech test.postman_collection.json'`

## API Guide

### api/login
This is mainly to login and get the authentication token.
The migration has created a dummy user. 
```
email: billy@da.test
password: billy
```

| api/login        | | POST   |    |                                                                   |
|------------------|-|--------|----------|-------------------------------------------------------------------|
| **Parameters**   ||        |    |                                                                   |
| ||        |   |                                                                   |
| email            | | string | required | User Email address                                                |
| password         || string | requried | User Password                                                     |
|  ||        ||                                                                   |
| **Response**     || 200    ||                                                                   |
| token            || string || Users auth token. Needed inorder to access the `api/courier-cost` |
|  ||        ||                                                                   |


### api/courier-cost
This is the api route that does the actual maths. 
Basically it works out the total distance and total cost. 
Total distance is `Sum(distance_between_locations)` and total price is `cost_per_mile *  Sum(distance_between_locations)` 

## WARNING
in order to se this route you'll need to make sure you have the following headers set:
```
Accept: application/json
Authorization: Bearer <`token` from `api/login`>
```

| api/courier-cost            | | POST         |          |                                                                                                                                                                               |
|-----------------------------|-|--------------|----------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Parameters**              ||              |          |                                                                                                                                                                               |
|                             ||              |          |                                                                                                                                                                               |
| cost_per_mile               | | float        | required | The cost per mile in GBP                                                                                                                                                      |
| no_of_drop_off              || int          | requried | The number of delivery drop offs                                                                                                                                              |
| distance_between_locations  || array(float) | required | The distance between Locations so if there is 3 drop offs. The array will have [4,4,5] representing the distance's of the drop offs. so A is 4 mile, B is 4 mile, C is 5 mile |
| extra_person_count          || int          |          | The number of extra people                                                                                                                                                    |
| extra_person_price_override || float        |          | The price per extra person in GBP overrides the default price which at the moment is £15                                                                                      |
|                             ||              |          |                                                                                                                                                                               |
| **Response**                || 200          |          |                                                                                                                                                                               |
| user_id                     || int          |          | User who created the calculation (IRL this would be quote_id or something simiular and not the user directly)                                                                 |
| number_of_drop_offs         || int          |          | The number of delivery drop offs                                                                                                                                              |
| total_distance              || float        |          | The sum of all distances so in the case of the example above `(4+4+5) = 13 Miles`                                                                                             |
| cost_per_mile               || float        |          | The cost per mile in GBP                                                                                                                                                      |
| total_price                 || float        |          | The total Price of all distances which is worked out by (distance * cost_per_mile)                                                                                            |
| extra_person_price          || float        |          | The price per extra person in GBP                                                                                                                                             |
| extra_person_count          || int          |          | The number of extra people                                                                                                                                                    |
| calculation_created_at      || datetime      |          | The datetime this calculation was made at                                                                                                                                     |

# Ways This API Module Could be Improved Upon
We could improve this API by doing the following:
* Adding unit tests.
* Have the output not display `extra_person_price`, `extra_person_count` when `extra_person_count > 0 OR !extra_person_count`
* Use Laravel Collection Data Structure instead of model for returning data.
