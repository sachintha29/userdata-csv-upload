# Laravel Excel Uploader with Queue Processing

A high-performance Laravel application for processing large CSV/Excel files (up to 100MB with 50,000+ records) using queue workers and efficient database storage.

## 🌟 Key Features

- **File Upload Validation**
  - Type validation (.xlsx, .xls, .csv)
  - Size limit (100MB)
  - Client-side and server-side validation

- **Queue Processing**
  - Background job processing
  - Chunked data insertion

- **Data Management**
  - Paginated display (25 records/page)
  - Realistic sample data generator

- **Error Handling**
  - Detailed logging
  - Failed job monitoring
  - User-friendly error messages

## 🚀 Quick Start

### Prerequisites
- PHP 8.2
- Composer
- MySQL 5.7+
- Node.js (for frontend assets)

### Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/sachintha29/userdata-csv-upload.git
   cd userdata-csv-upload

2. Install dependencies
   ```bash
    composer install
    npm install && npm run build

3. Configure environment
    ```bash
    cp .env.example .env
    php artisan key:generate

4. Set up database
    ```bash
    php artisan migrate
5. Queue Setup  ( Make sure before run application need to run queue)
   Configure in .env:
    QUEUE_CONNECTION=database

6. Run queue on triminal
    ```bash
    php artisan queue:work --timeout=3600 

7. PHP Configuration (php.ini)
    ```bash
    upload_max_filesize = 100M
    post_max_size = 100M
    memory_limit = 512M
    max_execution_time = 300
    max_input_time = 300
8. Create Storage Link
    ```bash
    php artisan storage:link
    chmod -R 775 storage
    chmod -R 775 public/storage
9. Generate Sample Data
    ```bash
    php artisan csv:generate 50000
9. Run development server 
    php artisan serve




