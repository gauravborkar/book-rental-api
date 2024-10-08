# Laravel Book Rental API

This is a **Book Rental API** built with Laravel and Docker.

## Features

- User Authentication (JWT-based)
- Book Search and Rental Management
- Rental History
- Book Popularity Stats
- Auto-mark overdue rentals
- Swagger API documentation
- Dockerized environment with Nginx, PHP-FPM, and MySQL

## Setup Instructions

### Step 1: Clone the Repository

```bash
git clone https://github.com/your-repo/book-rental-api.git
cd book-rental-api
```

###  Step 2: Configure Environment Variables

1. Copy the example environment file to .env:
```bash
cp .env.example .env
cp .env .env.testing
```

2. Update the .env file with appropriate values if necessary. Here are the most important settings:
```bash
DB_CONNECTION=mysql
DB_HOST=db           # MySQL container name
DB_PORT=3306
DB_DATABASE=book_rental
DB_USERNAME=root
DB_PASSWORD=root

JWT_SECRET=your-jwt-secret

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 3: Build and Run the Containers
1.	Migrate the tables in test db:
```bash
php artisan migrate --env=testing
```

2. Run the tests
```bash
php artisan test
```

3.	Start the application by building the Docker containers:
```bash
docker-compose up --build -d
```

This will:
	•	Build and start the Laravel app in the PHP-FPM container.
	•	Set up Nginx to serve the application.
	•	Start a MySQL database with the database book_rental.

### Step 4: API Documentation (Swagger)
```bash
http://localhost:8000/api/documentation
```

### Step 5: Running Tests
```bash
docker exec -it book_rental_app php artisan test
```

### Step 6: Mark Overdue Rental
```bash
docker exec -it book_rental_app php artisan rentals:mark-overdue
```

OR schedule a cron job

```bash
crontab -e
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```