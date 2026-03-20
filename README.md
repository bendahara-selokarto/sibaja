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

## Running Tests on Windows

If `php` is not available on your `PATH`, use the repository wrapper below. It will locate a local PHP install, enable the minimum extensions needed for PHPUnit, and run the suite:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\test.ps1
```

To run Laravel's `artisan test` entrypoint instead:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\test.ps1 -Artisan
```

If your PHP binary lives somewhere else, set `SIBAJA_PHP` first:

```powershell
$env:SIBAJA_PHP='C:\path\to\php.exe'
```

## Auditing `pemberitahuan` to `penyedia` sync

To audit whether the legacy `pemberitahuans.penyedia` column matches the normalized `pemberitahuan_penyedia` pivot, run:

```powershell
php artisan audit:pemberitahuan-penyedia-sync
```

For machine-readable output:

```powershell
php artisan audit:pemberitahuan-penyedia-sync --json
```

You can limit the mismatch sample size with `--limit=25`. The command is read-only and does not modify data.

On Windows, you can use the wrapper below to auto-detect PHP and optionally write a timestamped JSON report:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\audit-pemberitahuan-penyedia-sync.ps1 -Json
```

To save a timestamped report automatically without typing a filename:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\audit-pemberitahuan-penyedia-sync.ps1 -SaveReport
```

To save the audit output to a file:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\audit-pemberitahuan-penyedia-sync.ps1 -OutputPath .\storage\app\audits\pemberitahuan-penyedia-sync.json
```

You can also reduce the mismatch sample size:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\audit-pemberitahuan-penyedia-sync.ps1 -Limit 25 -OutputPath .\storage\app\audits\pemberitahuan-penyedia-sync.json
```

Operational runbook:
[docs/ops/AUDIT_PEMBERITAHUAN_PENYEDIA_SYNC.md](/d:/2026/sibaja/docs/ops/AUDIT_PEMBERITAHUAN_PENYEDIA_SYNC.md)
