1. Copy .env.example as .env and put variables. for arrays use decimeter `|` fro example `value1|value2`
   You need to have `GH_CLIENT_ID`, `GH_CLIENT_SECRET` and `GH_REDIRECT_URI` from your github application
and  This host and `GH_REDIRECT_URI`  has to  match your local url application.
2. Run `composer install` in the root directory.
3. Go to public directory and run application as `php -S localhost:8000 `. Remember your redirect uri has to match local url 
4. Open the URL in browser and authorise on GitHub
5. You can use board
* 6 For run test you need to run command `./vendor/bin/phpunit --bootstrap bootstrap.php test/`