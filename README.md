# URLs
- Frontend (Vue.js): `http://localhost:5173`
- PHP: `http://localhost:8080`
- Python: `http://localhost:8000`
- Mailpit: `http://localhost:8025/`
- Swagger Doc: `http://localhost:8000/docs`
- Redoc: `http://localhost:8000/redoc`

# Configure the project
1. **Docker configurations:**
   - Make sure you have Docker installed on your machine. If not, download and install it from the [official Docker website](https://www.docker.com/).

2. **Build and Start the Containers:**
   - In the terminal, navigate to the project directory and run the command:
     ```bash
     docker-compose up --build -d
     ```
   - This command will build the images and start the containers defined in the `docker-compose.yml`.

3. **Install Dependencies:**
   - Inside the container, install the PHP dependencies using Composer:
     ```bash
     docker compose exec php composer install
     ```

4. **Database Configuration:**
   - Run the migrations to create the tables in the database:
     ```bash
     docker compose exec php  php bin/console doctrine:migrations:migrate
     ```
     If your table is not created yet, run this command before:
     ```bash
     docker compose exec php  php bin/console doctrine:database:create
     ```


5. **Environment Configuration:**
   - Create a `.env` file at the root of the project if it doesn't exist, and configure the necessary environment variables, such as database credentials and JWT keys.
     ```bash
     cp .env.dev .env
     ```

6. **JWT Configuration:**
   - Generate the key pair for JWT:
     ```bash
     docker compose exec php php bin/console lexik:jwt:generate-keypair --overwrite
     ```

7. **Access the Application:**
    - Make sure frontend dependencies are installed
    ```bash
     docker compose exec frontend npm install
    ```

8. **Access the Application:**
   - Open the browser and go to `http://localhost:5173` to check if the application is running correctly.

These steps should help configure and start the project. Make sure to adjust the commands and configurations according to the specific needs of your project.

Tests:
php bin/console doctrine:database:create --env=test
php bin/console doctrine:migrations:migrate --env=test
