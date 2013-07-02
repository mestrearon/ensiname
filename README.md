# Install instructions

## 1. Configure the server

  a. Upload the code ([ZIP][1], [HTTPS][2] or [GIT][3]) to the destination folder on the server

  b. Point [the document root][4] to [the web directory][5]

  c. Adhere to [the requirements][6]

## 2. Configure the application

  a. Dump [the assets][7]

  b. Update [the vendors][8]

  c. Clear [the cache][9]

## 3. Configure the database

  a. Create and/or edit [app/config/parameters.yml][10] file

  b. Create and/or update [the database][11]

    ```
    # create
    $ php app/console doctrine:database:create

    # update
    $ php app/console doctrine:schema:update --force
    ```

## 4. Publish the application

  a. Execute the adminstrative tasks to publish the application and apply the desired domain, based on [the server specifications][http://bit.ly/11VQuQI]

## 5. Test the application

  a. Visit the published domain with your prefered device

For more information visit [the Symfony site][12]

[1]: https://github.com/willystadnick/ensiname/archive/master.zip
[2]: https://github.com/willystadnick/ensiname.git
[3]: https://github.com/willystadnick/ensiname
[4]: http://symfony.com/doc/current/cookbook/configuration/web_server_configuration.html
[5]: http://symfony.com/doc/current/book/page_creation.html#the-web-directory
[6]: http://symfony.com/doc/current/reference/requirements.html
[7]: http://symfony.com/doc/current/cookbook/deployment-tools.html#d-dump-your-assetic-assets
[8]: http://symfony.com/doc/current/cookbook/deployment-tools.html#b-update-your-vendors
[9]: http://symfony.com/doc/current/cookbook/deployment-tools.html#c-clear-your-symfony-cache
[10]: https://github.com/willystadnick/ensiname/blob/master/app/config/parameters.yml.dist
[11]: http://symfony.com/doc/current/book/doctrine.html#console-commands
[12]: http://symfony.com/