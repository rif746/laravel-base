{ pkgs, lib, config, inputs, ... }:

let
  laravelPort = "8000";
  dbName = "laravel";
  dbUser = "root";
  dbPassword = "";
  dbPort = 3307;
in
{
  dotenv.enable = true;

  languages.php = {
    enable = true;
    package = pkgs.php84.buildEnv {
      extensions = { all, enabled }: with all; enabled ++ [
        gd
        zip
        mbstring
        pdo_sqlite
        sqlite3
        pdo_mysql
        bcmath
        curl
        openssl
        tokenizer
        fileinfo
        redis
      ];
      extraConfig = ''
        memory_limit = 512M
        upload_max_filesize = 64M
        post_max_size = 64M
      '';
    };
  };

  languages.javascript = {
    enable = true;
    package = pkgs.nodejs_22;
    npm.enable = true;
  };

  packages = with pkgs; [
    php84Packages.composer
    sqlite
    mariadb
    git
    gnused
    lsof
  ];

  services.redis = {
    enable = true;
    port = 6379;
  };

  services.mysql = {
    enable = true;
    package = pkgs.mariadb;
    initialDatabases = [
      { name = dbName; }
    ];
    settings = {
      mysqld = {
        port = dbPort;
      };
    };
  };

  services.mailpit = {
    enable = true;
  };

  processes.serve.exec = "sync-env && php artisan serve --port=${laravelPort}";

  scripts.artisan.exec = ''
    php artisan "$@"
  '';

  scripts.sync-env.exec = ''
    if [ ! -f .env ]; then
      if [ -f .env.example ]; then
        cp .env.example .env
      else
        touch .env
      fi
    fi

    set_env() {
      local key=$1
      local value=$2
      if grep -q "^$key=" .env; then
        sed -i "s|^$key=.*|$key=$value|" .env
      else
        echo "$key=$value" >> .env
      fi
    }

    MYSQL_SOCKET="$PWD/.devenv/state/mysql/mysql.sock"

    set_env "REDIS_HOST" "127.0.0.1"
    set_env "REDIS_PORT" "${toString config.services.redis.port}"
    set_env "REDIS_CLIENT" "phpredis"

    set_env "DB_CONNECTION" "mysql"
    set_env "DB_HOST" "127.0.0.1"
    set_env "DB_PORT" "${toString dbPort}"
    set_env "DB_DATABASE" "${dbName}"
    set_env "DB_USERNAME" "${dbUser}"
    set_env "DB_PASSWORD" "${dbPassword}"
    set_env "DB_SOCKET" "$MYSQL_SOCKET"

    set_env "MAIL_MAILER" "smtp"
    set_env "MAIL_HOST" "127.0.0.1"
    set_env "MAIL_PORT" "1025"
    set_env "MAIL_USERNAME" "null"
    set_env "MAIL_PASSWORD" "null"
    set_env "MAIL_ENCRYPTION" "null"
    set_env "MAIL_FROM_ADDRESS" "hello@example.com"

    if ! grep -q "^APP_KEY=base64:" .env; then
      php artisan key:generate --ansi
    fi
  '';

  # 4. Enter Shell
  enterShell = ''
    export PATH="$PWD/vendor/bin:$PATH"

    # Synchronize .env variables
    sync-env

    MYSQL_SOCKET="$PWD/.devenv/state/mysql/mysql.sock"

    export MYSQL_TCP_PORT="${toString dbPort}"
    export MYSQL_UNIX_PORT="$MYSQL_SOCKET"

    IS_SERVICES_UP=false
    if [ -S "$MYSQL_SOCKET" ] || [ -f .devenv/state/redis/redis.pid ]; then
      IS_SERVICES_UP=true
    fi

    echo ""
    echo "========================================================="
    echo "⚡ LARAVEL DEVENV ENVIRONMENT (NIX)"
    echo "========================================================="
    echo "🐘 PHP Executable : $(which php)"
    echo "🐘 PHP Version    : $(php -r 'echo PHP_VERSION;')"
    echo "📦 Node.js        : $(node -v)"
    echo "🎼 Composer       : $(composer --version | cut -d' ' -f3)"
    echo "---------------------------------------------------------"

    if [ "$IS_SERVICES_UP" = true ]; then
      echo "🟢 SERVICES STATUS: RUNNING"
      echo "---------------------------------------------------------"
      echo "🔴 Redis Service  : 127.0.0.1:${toString config.services.redis.port}"
      echo "🐬 MariaDB/MySQL  : 127.0.0.1:${toString dbPort} (DB: ${dbName})"
      echo "📧 Mailpit Service: http://127.0.0.1:8025 (SMTP: 1025)"
      echo "🚀 App Server     : http://127.0.0.1:${laravelPort}"
    else
      echo "🔴 SERVICES STATUS: STOPPED"
      echo "---------------------------------------------------------"
      echo "💡 How to start background services:"
      echo "   1. Open a new terminal tab/window in this directory."
      echo "   2. Run the following command:"
      echo "      $ devenv up"
      echo "   (This will start Redis, MariaDB, Mailpit, & Artisan Serve)"
    fi

    echo "---------------------------------------------------------"
    echo "💡 Direct Commands:"
    echo "   • You can run artisan directly: 'artisan migrate'"
    echo "========================================================="
    echo ""
  '';
}
