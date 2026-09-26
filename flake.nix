{
  description = "Laravel Development Environment";

  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs/nixos-unstable";
  };

  outputs = { self, nixpkgs }:
    let
      system = "x86_64-linux";
      pkgs = import nixpkgs { inherit system; };

      # Configure PHP 8.5 with standard Laravel extensions
      php = pkgs.php85.buildEnv {
        extensions = ({ enabled, all }: enabled ++ (with all; [
          bcmath
          curl
          dom
          fileinfo
          filter
          mbstring
          openssl
          pdo
          pdo_mysql
          pdo_sqlite
          session
          tokenizer
          xml
          redis
          zip
        ]));
        extraConfig = ''
          memory_limit = 512M
          post_max_size = 64M
          upload_max_filesize = 64M
        '';
      };
    in
    {
      devShells.${system}.default = pkgs.mkShell {
        buildInputs = with pkgs; [
          php
          php85Packages.composer
          nodejs
          redis
          corepack
          sqlite
        ];

        shellHook = ''
            # Create a local runtime directory for Redis
            REDIS_DIR="$PWD/.nix-redis"
            mkdir -p "$REDIS_DIR"

            # Start Redis in the background using local dir
            if ! redis-cli ping >/dev/null 2>&1; then
            redis-server --dir "$REDIS_DIR" --port 6379 --daemonize yes
            echo "⚡ Redis server started on 127.0.0.1:6379"
            else
            echo "⚡ Redis server is already running"
            fi

            echo "🚀 Laravel Development Environment Loaded"
            echo "PHP Version: $(php -v | head -n 1)"

            alias pa="php artisan"
        '';
      };
    };
}
