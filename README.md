<h1 align="center">
  ☕ Panos Café 🍪
</h1>
<h3 align="center">
   ☕🍪 A Fully Featured Order Management And Delivery System For A Local Snack And Coffee Chain 🥐🍩
</h3>

![](https://i.imgur.com/GdCGArx.png)

##### You can contact me [here](mailto:paradox.supr@gmail.com) for a demo 📫 or you can also watch this [video](https://www.youtube.com/watch?v=k3QzxADv-Bo) on Youtube (also take a look at the chapters in the video description).

[![IMAGE ALT TEXT HERE](https://img.youtube.com/vi/k3QzxADv-Bo/0.jpg)](https://www.youtube.com/watch?v=k3QzxADv-Bo)

##### Table of Contents
- [Features](#Features)
  * [System Administrator](#system-administrator)
  * [Customer](#customer)
  * [Store Manager](#store-manager)
  * [Delivery Driver](#delivery-driver)
  * [Order Processing](#order-processing)
- [Tech Used](#tech-used)
- [Instructions](#instructions)
- [Limitations](#limitations)
- [License](#license)

#### Features
##### System Administrator
- The system administrator can register the stores owned by the local snack and coffee chain owner in the
  database using `phpMyAdmin` web interface.

  For every store he can register its:
  - name
  - address
  - phone
  - contact telephone number
  - geolocation coordinates
  - manager who manages the store's stock

    - For every manager he can register his:
        - Username
        - Password
        - Name
        - Last Name
        - Tax Identification Number
        - Social Security Registration Number
        - International Bank Account Number (IBAN)
    - He can also register the same as the above for every delivery driver

- The system administrator can also register the products offered by the local snack and coffee chain and its
  price in the database using `phpMyAdmin` web interface.

  NOTE: For simplicity reasons we make the assumption that the local snack and coffee chain offers only 5 coffee
  drinks (Espresso, Cappuccino, Filter Coffee, Greek Coffee and Frappe Coffee) and 5 snacks (Cheese Pie,
  Greens Pie, Sesame Bagel,Ham And Cheese Sandwich, Pound Cake).
- Finally the system administrator has access to a script that he can use to generate the staff payroll in
  XML format. Specifically the script takes as arguments the payroll month and the year and it generates the
  XML file that can be sent to the local bank via e-Banking for the staff payment.

  For simplicity reasons we assume that the generated XML file has the following structure:
  ```xml
   <xml>
       <header>
           <transaction>
               <period month="11" year="2022"/>
           </transaction>
       </header>
       <body>
           <employees>
               <employee>
                       <firstName>∆ηµήτρης</firstName>
                       <lastName>Αντωνόπουλος</lastName>
                       <amka>29118523654</amka>
                       <afm>1234567891</afm>
                       <iban>GR4648923784034423478422984</iban>
                       <ammount>850.58</ammount>
               </employee>
               <employee>
                       <firstName>Κυριακή</firstName>
                       <lastName>Αγγελοπούλου</lastName>
                       <amka>12569323654</amka>
                       <afm>1238757892</afm>
                       <iban>GR4648923784034423478422942</iban>
               </employee>
           </employees>
       </body>
   </xml>
  ```
##### Customer
- Every customer can register a new account on the system in order to be able to submit a new order.
  - In order to register a new account he must fill out his email, his phone number and his desired password.
  - After the successful account registration he will then be able to submit a new order.
  - Once the customer selects the items and quantity he wants, he will need to enter the delivery address.
    - The delivery address can be selected by either:
      - using the auto-complete feature to fill in the delivery address.
      - or by moving the marker over the map in case the delivery address is not available using the auto
        complete feature.
  - NOTE: The customer interface is built using responsive web design so that the web pages can render well on
  both personal computers and smartphones.
##### Store Manager
- Every store manager can do the following:
  - He can log in to the system using his username/password pair provided to him by the system
  administrator.
  - He can update the store's supply for each of the 5 types of snacks.

    NOTE: For simplicity reasons we assume that the coffee supply is unlimited.
  - He can monitor the specific store's orders pending delivery.

    NOTE: The pending orders on this page are dynamically updated using AJAX. Therefore, when an order is
    delivered by the delivery driver to the customer, it should not appear in the list.
  - The manager receives 800 euros per month plus 2% of the store's turnover as a bonus.
  - This store manager subsystem is accessible only by personal computers.
##### Delivery Driver
- Delivery drivers do not belong to a specific store but they serve all the stores in the local snack and
  coffee chain.
- A delivery driver after logging into the system using his username/password pair provided to him by the
system administrator, must declare/state his status as "Active".
- A delivery driver at the beginning of his shift he must declare to the system his current location (using
  the auto-complete feature or by moving the marker over the map).
- When an order is automatically assigned to him for delivery by the system the delivery driver is informed
from which store he has to pick up the order (pick-up point) as well as for the location of the customer who
has placed the order (display the delivery point on a map as well as the customer details).
- As soon as the delivery driver delivers the order he updates the order status as "Delivered" and he is now
available for the next order.
- For the sake of simplicity we assume that after the delivery of each order, the delivery driver does not
move but he is just waiting at that point for any new delivery assignment.
- Every delivery driver at the end of his shift should update his status as "Not available", so as not to
accept new order deliveries.
- Every delivery driver can be informed about the total amount of money he deserves, the deliveries number he
  has completed per day as well as the number of kilometers he has traveled per day.
- The delivery driver remuneration for each working day is calculated as follows:

  `Delivery Driver Remuneration` = `Number Of Hours Worked (Start/end Of Shift)` * `Hourly Allowance` + `Kilometers Traveled` * `Mileage Allowance`
- The delivery driver interface is designed only for smartphone usage.

##### Order Processing
When an order is placed by a customer, the system automatically sends it for processing to the nearest store
which has in stock the products requested by the customer while also reducing the nearest store's stock
accordingly. At the same time it assigns the order for delivery to the nearest active and available (that is
not currently delivering another order) delivery driver.


#### Tech Used

Made with ❤ using the following technologies :

- HTML5/CSS
- Bootstrap CSS Framework
- Font Awesome
- Google Fonts
- vanilla/plain PHP
   - MySQLi Extension
      - Note: Prepared Statements were used in order to provide protection against SQL Injections
- Javascript
- AJAX
- JQuery
- Regular Expressions
- Google Maps Distance Matrix API and JavaScript API
- MySQL/MariaDB
- XML

#### Instructions

- Navigate to  [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/) and create a new database called
  `coffeeshop`.
- Then in the `Import` tab click on the `browse...` button and select the `coffeeshop.sql` file included in
  this repo.
- Go to `coffeeshop` > `orders` > `dist_km_store` and set the default value to `NULL`
- Go to `coffeeshop` > `delivery_order` > `order_id` and set the default value to `NULL`
- You must also set a Google Maps API key in `footer.php`.

#### Limitations
- The website content is written only in Greek

#### License

All files are licensed under the AGPL-3.0 License except `css/bootstrap.min.css`, `css/fontawesome-all.css`,
`img/wallpaper.jpg`, `img/logo.png`, `js/bootstrap.min.js` and all the files under the `webfonts` folder.

Copyright © <a href="https://github.com/PARVD0XSVPR3ME">Panagiotis Charalampopoulos</a>
