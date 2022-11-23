# Set up

1. `` composer install ``
2.  `` php artisan migrate ``
3.  `` php artisan db:seed ``
4. `` php artisan test ``

User stories

1. [x] As a user I would like to book an appointment
2. [x] As a user I would like to select the date and see all available slots for this day
3. [x] As a user I want to open the scheduling page and schedule for multiple people at once (think of booking a haircut for yourself and your two kids)
4. [x] As a business, I want to create different schedulings that have separate configuration for all user stories below
5. [x] As a business I want to have a configurable break break between appointments
6. [x] As a business owner I want to offer timeslots for a configurable amount of days (for example starting from today always 7 days)
7. [x] As a business owner I want to configure breaks
8. [x] As a business owner I want that a configurable amount (1 or more) clients can book one time slot
9. [x] As a business owner I want to not get invalid bookings: for booked out slots, out of range, disabled time validation should fail
10. [x] As a business owner I want to set opening hours which can differ from day to day (for example I want to have different opening hours on weekends and monday)
11. [x] As a business owner I want create one or multiple breaks (lunch, cleaning, ...), for example a lunch break from 12:00-13:00 and a cleaning break from 15:00-16:00
12.  As a business owner I would like to specify times when I dont work, for example on public holidays
13. [x] As a business owner I want to create multiple different scheduling events with totally different parameters (hair cut, hair coloring, whatever)
14. As a business owner I want those different events to be totally separate
15. As another developer I want peace of mind and just run the automated test suite and know that I did not break anything


Acceptance criteria

1. [x] A time scheduling JSON based Rest API should be created
2. [x] 1 GET api which provides all data an SPA might need to display a calendar and a time selection
3. [x] 1 POST api which creates a booking for 1 or more people
4. [x] Implement automated testing that ensures the functionality of your code
5. [x] For the tests purpose, A booking is done with only an E-Mail, first name and last name
6. [x] Important: dont trust the frontend, validate the data so that the API returns an exception in case something does not fit into the schema or is already booked out - For a men haircut - booking should not be possible at 7am because its before the shop opens - booking at 8:02 should not be possible because its not fitting in any slot - booking at 12:15 should not be possible as its lunch break - ...
7. [x] Seed your database with the following scheduling - Men Haircut - slots for the next 7 days, sunday off - from 08:00-20:00 monday to friday - from 10:00-22:00 saturday - lunch break at 12:00-13:00 - cleaning break at 15:00-16:00 - max 3 clients per slot - slots every 10 minutes - 5 minutes cleanup break between slots - the third day starting from now is a public holiday - Woman Haircut - slots for the next 7 days, sunday off - lunch break at 12:00-13:00 - from 08:00-20:00 monday to friday - from 10:00-22:00 saturday - cleaning break at 15:00-16:00 - slots every 1 hour - 10 minutes cleanup break  - max 3 clients per slot - the third day starting from now is a public holiday