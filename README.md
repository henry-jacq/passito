# Passito

**Passito** is a hostel gatepass management system designed to streamline the process of issuing gate passes for students. This system allows for easy management of student entries and exits, ensuring a secure and efficient way to monitor hostel activities.

## Table of Contents

- [Features](#features)
- [Dependencies](#dependencies)
- [Installation](#installation)
- [Docker Setup](#docker-setup)
- [Configuration](#configuration)
- [Usage](#usage)
- [Development](#development)
- [Contributing](#contributing)
- [License](#license)

## Features

- User-friendly interface for students and administrators.
- Ability to issue and manage gate passes.
- View history of entries and exits.
- Notifications for upcoming passes.
- Role-based access control.

## Dependencies

To run Passito, ensure you have the following installed:

- **Sass**: For compiling stylesheets.
- **NPM**: Package manager for JavaScript.
- **Apache**: Web server to host the application.
- **PHP**: Version 8.0 or above.

## Installation

Follow these steps to set up Passito on your local machine:

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/henry-jacq/passito.git
   cd passito
   ```

2. **Install NPM Packages**:
   ```bash
   npm install
   ```

3. **Install Composer Dependencies** (if you haven't already):
   ```bash
   composer install
   ```

4. **Configure Environment Variables**:
   Create a `.env` file in the root of the project and configure your database and application settings. You can copy from the `.env.example` provided in the repository:
   ```bash
   cp .env.example .env
   ```

5. **Set Up Database**:
   - Create a new MySQL database for Passito.
   - Update your `.env` file with the database connection details.

6. **Run Migrations** (if applicable):
   ```bash
   php passito.php migrations:migrate
   ```

7. **Start the Development Server**:
   - Make sure Apache is running and configured to serve from the `public` directory.
   - Alternatively, you can use Vite for the front-end development:
     ```bash
     npm run dev
     ```

## Docker Setup

To set up Passito using Docker, follow these steps:

1. **Install Docker**: Ensure you have [Docker](https://www.docker.com/get-started) and [Docker Compose](https://docs.docker.com/compose/install/) installed on your machine.

2. **Build and Start the Containers**:
   From the root of your project, run:
   ```bash
   docker-compose up --build
   ```
   This will build the containers defined in your `docker-compose.yml` file.

3. **Access the Application**:
   Open your web browser and go to `http://localhost:8000` (or the port you have configured in the `docker-compose.yml`).

4. **Access Adminer** (optional):
   If you want to manage your database using Adminer, you can access it at `http://localhost:8080`.

5. **Running Vite**:
   If you want to run the Vite development server for frontend resources, execute the following command in the Docker container:
   ```bash
   npm run dev
   ```
   Ensure to adjust the configuration in `vite.config.js` to allow external access if necessary.

## Configuration

Make sure to configure the following settings in your `.env` file:

- `DB_CONNECTION`: Database type (e.g., `pdo_mysql`).
- `DB_HOST`: Database host (e.g., `localhost`).
- `DB_PORT`: Database port (e.g., `3306`).
- `DB_DATABASE`: Name of your database.
- `DB_USERNAME`: Database username.
- `DB_PASSWORD`: Database password.

## Usage

1. **Access the Application**:
   Open your web browser and go to `http://localhost:8000` (or the port you have configured in Apache).

2. **Login**:
   Use the admin credentials to log in or register as a new user.

3. **Manage Gate Passes**:
   - Issue new gate passes.
   - View and manage existing passes.
   - Check the history of entries and exits.

## Development

To contribute to the development of Passito:

1. **Create a new branch**:
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Make your changes**.

3. **Commit your changes**:
   ```bash
   git commit -m "Add your commit message"
   ```

4. **Push to the branch**:
   ```bash
   git push origin feature/your-feature-name
   ```

5. **Create a Pull Request** on GitHub.

## Contributing

Contributions are welcome! Please feel free to submit issues or pull requests to improve the project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
