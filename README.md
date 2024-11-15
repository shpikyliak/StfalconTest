
# Currency Rate Notifier

This application is a Symfony-based project designed to monitor currency exchange rates from MonoBank and PrivatBank APIs. It compares current rates with user-defined thresholds and sends email notifications if the changes exceed the specified threshold. The app utilizes a MySQL database and runs with Docker for streamlined setup and deployment.

---

## Quick Start: How to Run

### Prerequisites
- PHP 8.2+
- Composer
- Docker and Docker Compose
- Git

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd <repository-folder>
   ```

2. **Install PHP Dependencies**
   ```bash
   composer install
   ```

3. **Set Up Environment Variables**
    - Duplicate the `.env` file as `.env.local`:
      ```bash
      cp .env .env.local
      ```
    - Update the database credentials and other configuration as needed in `.env.local`.  
      Example for Docker:
      ```
      DATABASE_URL=mysql://user:password@db:3306/database_name
      ```

4. **Build and Start Docker Containers**
   ```bash
   docker-compose build
   docker-compose up -d
   ```

5. **Run Database Migrations**
   ```bash
   docker-compose exec php bin/console doctrine:migrations:migrate
   ```
---

## Application Overview

This application provides two main functionalities, implemented as Symfony console commands:

1. **Command to Set Up Thresholds**  
   Users can specify a threshold for currency exchange rates along with their email address using a console command.
    - **Command:**
      ```bash
      bin/console app:add-threshold <email> <currency> <threshold>
      ```  
        - `email`: The email address to send notifications to.
        - `currency`: The currency code (e.g., `USD`, `EUR`).
        - `threshold`: The rate change threshold to trigger notifications.

2. **Cron Job for Rate Monitoring**  
   Periodically checks if the exchange rates have changed beyond user-defined thresholds. Sends email notifications to users and removes the processed entries from the database.
    - **Command:**
      ```bash
      bin/console app:check-currency-rates
      ```

---

## How It Works

1. **Add Thresholds:**
    - A user specifies a currency, a threshold, and an email address.
    - The application saves this data in the database for monitoring.

2. **Monitor Currency Rates:**
    - The cron job fetches the latest rates for all currencies with active thresholds.
    - It compares the rates from MonoBank and PrivatBank APIs with the stored thresholds.
    - If the threshold is exceeded, an email notification is sent to the user, and the record is removed from the database.

---

## Configuration Notes

### Email Configuration
Update the SMTP settings in `.env.local` to configure email notifications. Example:
```dotenv
MAILER_DSN=smtp://username:password@smtp.mailtrap.io:2525
```

### Docker Commands

- **Stop containers:**
  ```bash
  docker-compose down
  ```

- **Restart containers:**
  ```bash
  docker-compose restart
  ```

- **Check logs:**
  ```bash
  docker-compose logs -f
  ```

---

## Summary

This application simplifies monitoring exchange rates by allowing users to set up automated notifications when significant changes occur. Using Symfony's console commands and a robust service-based architecture, it provides flexibility and reliability for managing currency rate thresholds and notifications.
