# DestinyReddit
This is what powers the /r/DestinyTheGame subreddits site.


## 1. Create a Bungie Application here https://www.bungie.net/en/Application

    a. Fill in "Application Name".
    
    b. Fill in "Redirect URL" as https://domainname/admin/login-apis.php.

    c. Choose all the check boxes.

    d. Hit "Create New App".

    e. Keep the application windows open.

    f. Copy "API Key" from the Bungie Application page into $BUNGIE_API_X on config.php.

    g. Copy "Authorization URL" from the Bungie Application page into $BUNGIE_AUTH_URL on config.php.


## 2. Create Postgres database tables
    
    a. Create db tables. Find all create scripts in Admin/scripts.sql

    b. Add a Postgres connection string to $POSTGRES_DB_STR on config.php.
