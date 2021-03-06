Laravel-4-Nexmo
===============

This is a Laravel4 package that helps you integrate Nexmo sms services into your site.  
This is a work in progress so right now these are the function available:

1. Get Account Balance
2. Get Prices and Carriers
3. Send SMS

## Laravel 4.2 and below
You can install this package through Composer. Edit your composer.json file and add `christian-giupponi/nexmo` to the required:

    "require" : {
        "christian-giupponi/nexmo": "dev-master"
    }

ext, update Composer from the Terminal:

    composer update
    
Now you have to add the Service Provider so edit your app/config/app.php file and in the providers array add:

    'ChristianGiupponi\Nexmo\NexmoServiceProvider'
    
Now add the Facade in aliases array:

        'Nexmo'    => 'ChristianGiupponi\Nexmo\Facades\Nexmo',
        
You also need to pusblish the config file to add your own api and secret key that you can find on your Nexmo's dashboard:

    php artisan config:publish christian-giupponi/nexmo

And you're done. Now you can call nexmo package using 

    Nexmo::getBalance();
    Nexmo::prices();
    Nexmo::sendSMS($from, $to, $text, $options);
    
## Results
All the function of this package will return a json result. It is made with 3 fiedls:

    1. code
    2. reason
    3. body
    
### 1. Code
This fields will return an http code status that is generated by the http request and represent the result of the api call, is not the code returned from Nexmo.  
It is generated by the Guzzle client and you can check if the request is correct.

### 2. Reason
Like the above it is generated by Gruzzle and contains the result of the api request, not the response from Nexmo.  
In this fild you can check if there are any errors, like malformed request.

### 3. Body
This is a Json result sent by Nexmo after we call its api

## Issue
If you find any issue please post it here on GitHub, fell free to fork and add any new api call that you need and then please make a pull request to keep this project updated.
