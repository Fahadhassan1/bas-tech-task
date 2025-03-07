<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Running This Project

To get started with this project, follow these steps:

- PHP 8.1
- Laravel 9.52.20
- Node 17
- MySQL 8.0

1. **Clone the repository:**
    ```bash
    git clone https://github.com/Fahadhassan1/bas-tech-task.git
    cd banking-system-tech
    ```

2. **Install dependencies:**
    ```bash
    composer install
    ```

3. **Set up environment variables:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. **Configure your `.env` file:**
    Update your `.env` file with your database and other configurations.

5. **Run database migrations:**
    ```bash
    php artisan migrate
    ```

6. **Start the development server:**
    ```bash
    php artisan serve
    ```

7. **Build and run npm packages for the frontend:**
    ```bash
    npm install
    ```

### Running Tasks

**1-Generate Payroll:**
```bash
php artisan payroll:generate 2025
```
You can change the year as needed. If you leave it empty, it will automatically detect the current year. The CSV will be store in storage folder.

**2-Store Secret Message:**
Send a POST request to:
```
http://127.0.0.1:8000/api/message/store
```
with the following form body parameters:
- `recipient`
- `message`
- `read_once` (optional)

You will receive an output with:
- `identifier`
- `decryption_key`

**Store API:**

![alt text](<Screenshot 2025-03-07 at 02.14.59.png>)

**Retrieve Decrypted Message:**
Send a GET request to:
```
http://127.0.0.1:8000/api/message/show?identifier=ff9ca759-19d8-44ba-98ba-7e5888f913aa&decryption_key=ZmY5Y2E3NTktMTlkOC00NGJhLTk4YmEtN2U1ODg4ZjkxM2Fh
```
with the following query parameters:
- `identifier`
- `decryption_key`

In response, you will get the decrypted message if all validations pass.

**GET API Decrypted Message:**

![alt text](<Screenshot 2025-03-07 at 02.15.15.png>)

## Running Tests

To run the tests, follow these steps:

1. **Set up the testing environment:**
    ```bash
    php artisan key:generate --env=testing
    ```

2. **Run database migrations for testing:**
    ```bash
    php artisan migrate --env=testing
    ```

3. **Run the tests:**
    ```bash
    php artisan test
    ```