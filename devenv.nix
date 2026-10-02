{ pkgs, lib, config, inputs, ... }:

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
    git
    gnused
  ];

  services.redis = {
    enable = true;
    port = 6379;
  };

  enterShell = ''
    echo "⚡ Devenv Laravel Siap!"
    echo "📍 PHP Executable: $(which php)"
    echo "🐘 PHP Version: $(php -v | head -n 1)"

    # 1. Pastikan berkas .env ada
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

    # 2. Sinkronisasi Redis
    set_env "REDIS_HOST" "127.0.0.1"
    set_env "REDIS_PORT" "${toString config.services.redis.port}"
    set_env "REDIS_CLIENT" "phpredis"

    # 3. Sinkronisasi SQLite
    if [ ! -f database/database.sqlite ]; then
      mkdir -p database
      touch database/database.sqlite
    fi
    set_env "DB_CONNECTION" "sqlite"
    set_env "DB_DATABASE" "$PWD/database/database.sqlite"

    # 4. Generate App Key jika belum ada
    if ! grep -q "^APP_KEY=base64:" .env; then
      php artisan key:generate --ansi
    fi

    # 5. Prioritaskan vendor/bin
    export PATH="$PWD/vendor/bin:$PATH"
  '';
}
