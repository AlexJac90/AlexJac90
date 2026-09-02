FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        php-cli \
        php-mysql \
        mariadb-server \
        sudo \
    && rm -rf /var/lib/apt/lists/*

# Cursor runs commands as the `ubuntu` user; give it passwordless sudo so the
# start script can manage the MariaDB daemon.
RUN if ! id ubuntu >/dev/null 2>&1; then useradd -m -s /bin/bash ubuntu; fi \
    && echo "ubuntu ALL=(ALL) NOPASSWD:ALL" > /etc/sudoers.d/ubuntu \
    && chmod 0440 /etc/sudoers.d/ubuntu

USER ubuntu
