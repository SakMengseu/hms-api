# Healthcare System API

This is a Laravel-based project that provides a comprehensive API for managing various aspects of a healthcare system. The project includes features such as user authentication, doctor and patient management, appointment scheduling, consultation management, lab test management, and prescription management.

## Features

- **User Authentication**: Secure endpoints for user authentication and authorization.
- **Doctor Management**: Create, read, update, and delete doctor records.
- **Patient Management**: Create, read, update, and delete patient records.
- **Appointment Management**: Schedule, view, update, and cancel appointments.
- **Consultation Management**: Manage consultation records, including creation, viewing, updating, and deletion.
- **Lab Test Management**: Handle lab test records, including creation, viewing, updating, and deletion.
- **Prescription Management**: Manage prescription records, including creation, viewing, updating, and deletion.

## API Endpoints

The API endpoints are organized into the following categories and are accessible under the `/api/v1` prefix:

### Authentication
- Endpoints for user login, registration, and authorization.

### Doctor Management
- `GET /api/v1/doctors`: Retrieve a list of all doctors.
- Additional endpoints for creating, updating, and deleting doctor records.

### Patient Management
- `GET /api/v1/patients`: Retrieve a list of all patients.
- Additional endpoints for creating, updating, and deleting patient records.

### Appointment Management
- `GET /api/v1/appointments`: Retrieve a list of all appointments.
- Additional endpoints for scheduling, updating, and canceling appointments.

### Consultation Management
- `GET /api/v1/consultations`: Retrieve a list of all consultations.
- Additional endpoints for creating, updating, and deleting consultation records.

### Lab Test Management
- `GET /api/v1/lab-tests`: Retrieve a list of all lab tests.
- Additional endpoints for creating, updating, and deleting lab test records.

### Prescription Management
- `GET /api/v1/prescriptions`: Retrieve a list of all prescriptions.
- Additional endpoints for creating, updating, and deleting prescription records.

## Database Schema

The project uses a MySQL database with the following tables:

- **users**: Stores user information (username, password, role).
- **doctors**: Stores doctor information (name, specialty, contact details).
- **patients**: Stores patient information (name, date of birth, contact details).
- **appointments**: Stores appointment information (date, time, doctor/patient details).
- **consultations**: Stores consultation information (date, time, doctor/patient details).
- **lab_tests**: Stores lab test information (test type, result, patient details).
- **prescriptions**: Stores prescription information (medication, dosage, patient details).

## Installation

To set up the project locally, follow these steps:

1. Clone the repository to your local machine:
   ```bash
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```bash
   cd <project-directory>
   ```
3. Install the required dependencies:
   ```bash
   composer install
   ```
4. Copy the `.env.example` file to `.env` and configure your environment variables (e.g., database connection):
   ```bash
   cp .env.example .env
   ```
5. Generate an application key:
   ```bash
   php artisan key:generate
   ```
6. Run database migrations to create the schema:
   ```bash
   php artisan migrate
   ```
7. (Optional) Populate the database with sample data:
   ```bash
   php artisan db:seed
   ```
8. Start the development server:
   ```bash
   php artisan serve
   ```

The API will be accessible at `http://localhost:8000`.

## Usage

To interact with the API, send HTTP requests to the relevant endpoints. For example:

- To retrieve a list of all doctors:
  ```bash
  curl http://localhost:8000/api/v1/doctors
  ```

Ensure you include appropriate authentication headers or tokens for protected endpoints.

## Contributing

Contributions are welcome! To contribute to the project:

1. Fork the repository to your GitHub account.
2. Create a new branch for your changes:
   ```bash
   git checkout -b feature/your-feature-name
   ```
3. Make your changes and commit them:
   ```bash
   git commit -m "Add your commit message"
   ```
4. Push your changes to your fork:
   ```bash
   git push origin feature/your-feature-name
   ```
5. Submit a pull request to the main repository.

## License
