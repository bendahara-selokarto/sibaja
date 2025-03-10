# Required Software

Before you begin, ensure you have the following software installed on your system:

- **Git**: [Download and install Git](https://git-scm.com/downloads)
- **Composer**: [Download and install Composer](https://getcomposer.org/download/)
- **Node.js and npm**: [Download and install Node.js](https://nodejs.org/)

# Cloning the Repository and Running the Application

Follow these steps to clone the repository from GitHub and run the Laravel application using `artisan serve`:

1. **Clone the Repository:**
    Open your terminal and run the following command to clone the repository:
    ```sh
    git clone https://github.com/bendahara-selokarto/sibaja.git
    ```

2. **Navigate to the Project Directory:**
    Change your current directory to the project directory:
    ```sh
    cd sibaja
    ```

3. **Install Dependencies:**
    Install the required dependencies using Composer:
    ```sh
    composer install
    ```

4. **Install Node Dependencies:**
    Install the required Node.js dependencies:
    ```sh
    npm install
    ```

5. **Build Assets:**
    Build the frontend assets:
    ```sh
    npm run build
    ```

6. **Copy the Environment File:**
    Copy the `.env.example` file to `.env`:
    ```sh
    cp .env.example .env
    ```

7. **Generate Application Key:**
    Generate the application key:
    ```sh
    php artisan key:generate
    ```

8. **Run Migrations:**
    Run the database migrations:
    ```sh
    php artisan migrate
    ```

9. **Start the Development Server:**
    Start the Laravel development server:
    ```sh
    php artisan serve
    ```