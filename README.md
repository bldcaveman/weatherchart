# weatherchart

Fill the conf.php with the correct API keys (supplied via email).

The JSON folder and files need to writeable but other than that you just run using the index.php as you would a standard site.  I've used JSON for portability.  

To avoid the maximum request limit I've staggered the data collection with Javascript (the real work is still done with PHP though).  

In the real world I would use Cron and MySQL, or allow the script to run for longer but this would appear as though nothing was happening and this way the weather changes with time.

I wanted to add the cost of flights inline but could not find a suitable API with immediate and generous enough access.



To run the basic test, you'd need to run composer install, then phpunit  ChartTest.php
