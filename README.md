# TodoList App

**Description:**
This project is a Symfony-based web application that utilizes modern JavaScript and CSS tools, including Webpack Encore and the Symfony Stimulus bundle. It is designed to run within a Dockerized environment with services for `nginx` and `php-fpm`. The project supports live development features like file watching and hot module replacement (HMR) via Webpack.

---

## Features

- Symfony 7 application architecture.
- Integration with Webpack Encore for modern asset management.
- Stimulus.js for enhanced interactivity.
- Dockerized setup for easy development and deployment.
- Live reloading and file watching using Webpack.
- Customizable domain configuration via `/etc/hosts`.

---

## Project Structure

```
.
├── app/                   # Symfony application source code
│   ├── assets/            # Frontend assets (JavaScript, CSS)
├── docker/                # Docker configuration
│   ├── nginx/             # Nginx configuration files
│   ├── php/               # PHP-FPM configuration files
│   ├── php.${APP_ENV}.ini # Custom PHP settings
├── docker-compose.yml     # Docker Compose configuration
├── var/                   # Logs and cache
```

---

## Prerequisites

Before running this project, ensure you have the following installed:

- **Docker** (20.10+)
- **Docker Compose** (v2.0+)

---

## Installation

1. **Clone the Repository:**

   ```bash
   git clone git@github.com:salmabenhadjali/todolist-app.git
   cd todolist-app
   ```

2. **Add the DOMAIN Name to Hosts File:**

   ```bash
   echo "127.0.0.1 app.todolist.local" | sudo tee -a /etc/hosts
   ```

3. **Start Docker:**

   ```bash
   docker compose up -d --build
   ```

4. **Verify PHP Version:**
   ```bash
   docker compose exec app bash php --version
   ```

The environment is up, and the application is ready on:

```
http://app.todolist.local:90
```

5. **Install PHP and JS Dependencies:**
   ```bash
   docker compose exec app bash composer install
   docker compose exec app bash npm install
   ```

<!-- 6. **Run Webpack Watcher:**
   Inside the `app` container:
   ```bash
   docker-compose exec app npm run watch
   ``` -->

6. **Access the Application:**
   Open your browser and navigate to:
   ```
   http://app.todolist.local:90
   ```
