#!/usr/bin/env bash
# Per-boot startup for the Shopping List app environment.
# - Starts the MariaDB server (systemd is not available in the Cloud Agent VM).
# - Idempotently creates the application database, table, and user.
# Safe to run multiple times.
set -euo pipefail

DB_NAME="${DB_NAME:-ShoppingListApp}"
DB_USER="${DB_USER:-shopping}"
DB_PASSWORD="${DB_PASSWORD:-shopping}"

echo "[start] Ensuring MariaDB is running..."
if ! sudo mariadb-admin ping >/dev/null 2>&1; then
    sudo mkdir -p /var/run/mysqld
    sudo chown mysql:mysql /var/run/mysqld
    # Launch the daemon detached; it keeps running for the life of the VM.
    sudo -b mariadbd-safe --datadir=/var/lib/mysql >/tmp/mariadb.log 2>&1

    echo "[start] Waiting for MariaDB to accept connections..."
    for _ in $(seq 1 60); do
        if sudo mariadb-admin ping >/dev/null 2>&1; then
            break
        fi
        sleep 1
    done
fi

if ! sudo mariadb-admin ping >/dev/null 2>&1; then
    echo "[start] ERROR: MariaDB did not become ready in time." >&2
    exit 1
fi

echo "[start] Initializing database and user (idempotent)..."
sudo mariadb <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
CREATE USER IF NOT EXISTS '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'127.0.0.1';
FLUSH PRIVILEGES;
USE \`${DB_NAME}\`;
CREATE TABLE IF NOT EXISTS ShoppingList (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    purchased TINYINT(1) NOT NULL DEFAULT 0
);
SQL

echo "[start] MariaDB is ready; database '${DB_NAME}' initialized."
