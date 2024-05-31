Markdown

## Setup Process

1. **Prerequisites:**
    * PHP (version 8.2 or higher)
2. **Clone the Repository:**
    ```bash
    git clone [https://github.com/mohamedElshamy00000/movies.git]
    ```
3. **Install Dependencies:**
    ```bash
    composer install
    docker-compose up -d
    ```
4. **Database Setup:**
    * Create a database for your project.
    * Update the `.env` file with your database credentials.
    * Run the database migrations:
        ```bash
        php artisan migrate
        
5. **Access:**
    ```bash
    http://localhost:8080/

## Features

- Import a movies from excel file
- add, edit, delete, show movie
- use api to get and save movies data from IMDB

* **API Documentation:** 
    https://documenter.getpostman.com/view/25676865/2sA3QwaUaB#eb4ada2c-f18b-4429-af79-55c2f2892023

* **Models Documentation:** 

    ** movie **
    * Use the `Movie` model to manage movie data
    * Create new movies, retrieve movie information, update movie details, and manage movie relationships with other models (`User`, `UserMovie`, `MovieGenra`).

    ** User **
    * Use the `User` model to interact with user data in your application.
    * Create new users, and manage user relationships with other models (`UserMovie`).

    ** UserMovie **
    * This document describes the `UserMovie` model in the Laravel project.

* **Deployment Instructions:** If your project has a specific deployment process, document it in a separate markdown file.
```bash
    eb init
    eb create
    eb deploy
    eb ssh
    cd /var/app/current
    php artisan migrate --force
    sudo chown -R webapp:webapp storage
    sudo chmod -R 775 storage
