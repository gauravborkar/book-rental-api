# Laravel Book Rental API

This is a **Book Rental API** built with Laravel and Docker, following best practices such as the **Service and Repository pattern**, **JWT authentication**, and **Dockerization** for easy setup and deployment.

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
```

### Step 3: Build and Run the Containers
1.	Start the application by building the Docker containers:
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