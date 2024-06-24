# WP Dev Kit

WP Dev Kit is a simple frame for WordPress to make local development for themes and plugins easier. All necessary
components run inside Docker containers, using a single `docker-compose.yml` file.

## Running the dev environment

To run the dev environment, go to the root folder of this repository in the terminal and execute `docker compose up` (
given you have [Docker](https://www.docker.com/) installed). All the necessary containers will be downloaded and
started. Using these URLs, you can reach the applications:

- <http://localhost:8000>: the actual WordPress site.
- <http://localhost:8025>: MailPit (a simple application for testing email functionality locally)
- <http://localhost:8001>: phpMyAdmin (DB admin tool, by default log in with `root` and `root`)

You can also connect with the MySQL DB with your favourite DB tooling, by connecting with host `localhost` and
port `3306`.