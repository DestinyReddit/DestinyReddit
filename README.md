# DestinyReddit
This is what powers the /r/DestinyTheGame subreddits site.

## 1. Create a Bungie Application here https://www.bungie.net/en/Application.

    a. Fill in "Application Name"
    
    b. Fill in "Redirect URL" as https://domainname/admin/login-apis.php

    c. Choose all the check boxes

    d. Hit "Create New App".

    c. Keep the application windows open.

## 2. Fill in values in admin/config.php

    a. Copy "API Key" from the Bungie Application page into $BUNGIE_API_X

    b. Copy "Authorization URL" from the Bungie Application page into $BUNGIE_AUTH_URL
