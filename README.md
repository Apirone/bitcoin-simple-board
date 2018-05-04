# Bitcoin Simple Advertisement Board
This repository contains simple examples of how you can use the apirone.com receive payments API to process bitcoin payments.

Example show that generated addresses related to supplied identifier. These addresses are reusable. We will endlessly monitor it regardless of payments because the Store can sell the same goods repeatedly. Or you can use this address to top-up user's account balance in a project, for example, the game balance or advertisement balance.
We show paid advertisement on the top of page and last five unpaid messages at the bottom. Paid advertisement sorted descently by total peid amount.

Further documentation & explanation can be found at: https://apirone.com/docs/bitcoin-forwarding-api

This code is intended as educational reference material and is not written for production use.

# Instructions
* Clone the git repository into the ROOT of your web server.
* cd /www/
* chmod 755 ./
* Change URL address, bitcoin wallet and database settings at setup.php
* Navigate to setup.php in your browser
* http://your_site/setup.php
* Now the database is initialized open the demo
* http://your_site/index.php
