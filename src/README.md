# IMPORTANT, without this the site will not work

install [composer](https://getcomposer.org/).

run the following command in a terminal located in the current directory (src).

```
composer install
```

## sql

create a database with the name `forum`
 - you might want to change the db name in `model/dbConnect.php` to give your db a custom name
 - the tables will automaticaly be made (if nessecairy)

I'll include a db dump with some dummy data
assuming [xampp](https://www.apachefriends.org/download.html)
 - extract the 7z, move the folder to:

 windows:

 ```
 C:\xampp\mysql\data\
 ```

 arch linux:

 ```
 /opt/lampp/var/mysql/
 ```

  look in the database for the usernames of users already created. the password for all users is:

    Passw0rd!
