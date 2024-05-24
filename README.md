
Clone the project :

    git clone https://github.com/Nambinina7/symfony7.git

Installing Components :

    composer install

Configure database:

    php bin/console d:d:c --if-not-exists

    php bin/console make migration:migrate

    php bin/console doctrine:migrations:migrate

Generate the SSL keys:

    php bin/console lexik:jwt:generate-keypair


Install & Build Assets:

    npm install
    npm run dev

Launching the Symfony server:

    symfony run serve

Add fixtures :

    php bin/console d:f:l

Use php-cs :

    mkdir -p tools/php-cs-fixer

    composer require --working-dir=tools/php-cs-fixer friendsofphp/php-cs-fixer

    tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src/

 

