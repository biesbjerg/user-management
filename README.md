# User management
Built with Slim Micro Framework 4 in an ADR-ish pattern (Action-Domain-Responder).

#### Requirements:
- PHP 7.4+
- MySQL 5.5+

# Setup

#### 1. Clone repository
```
$ cd /var/www/public_html
$ git clone https://github.com/biesbjerg/user-management.git ./
```

#### 2. Install dependencies using composer
```
$ composer install
```

#### 3. Make a copy of the environment file
```
$ cp config/.env.example config/.env
```

#### 4. Modify environment file to reflect your database settings
```
$ nano config/.env
```
```
DATABASE_HOST=localhost
DATABASE=users
DATABASE_USER=users
DATABASE_PASSWORD=your_password
```

#### 5. Run database migrations
```
$ vendor/bin/phinx migrate
...
$ vendor/bin/phinx seed:run
```
