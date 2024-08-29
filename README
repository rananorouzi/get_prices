## Description

- This project, fetch a price list from a site, parse the data and store in to the database. it updates daily by cronJob.
In front-end, data is listed and able to modify using Ajax.

- The PHP version is 7.1.

- For reading the Excel file, I'm using the PhpSpreadsheet library and added it by the composer.

- For getting data daily, I have a `get_price_data.php`which should run in the terminal because of the amount of data and its progress. It should be run as a cronJob.
- I wrote a cron command in `cron\cron`. It should be set up on the server.
    ```
     */0 10 * * * /usr/bin/php -q /home/user/get_prices/cron/get_price_data.php > /dev/null 2>&1
    ```
- I run it on Windows. So I added a bat file with the PHP run command to the Task Scheduler of Windows and set it to run once a day.
- The bat file is `\cron\cron_bat`.

- Database and table structure is in `files\prices.sql`
