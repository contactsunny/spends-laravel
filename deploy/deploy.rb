# config valid only for current version of Capistrano
lock "3.9.0"

set :application, "spends-laravel"
set :repo_url, "git@gitlab.com:contactsunny/spends-laravel.git"

# Default branch is :master
ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :airbrussh.
# set :format, :airbrussh

# You can configure the Airbrussh format using :format_options.
# These are the defaults.
# set :format_options, command_output: true, log_file: "log/capistrano.log", color: :auto, truncate: :auto

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# append :linked_files, "config/database.yml", "config/secrets.yml"
set :linked_files, %w{.env}

# Default value for linked_dirs is []
# append :linked_dirs, "log", "tmp/pids", "tmp/cache", "tmp/sockets", "public/system"
set :linked_dirs, fetch(:linked_dirs, []).push('vendor', 'storage')

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }
# Default value for default_env is {}
set :default_env, {
  'NODE_ENV' => "production"
}

# Default value for keep_releases is 5
set :keep_releases, 5

namespace :deploy do

    desc 'Get stuff ready prior to symlinking'
    task :compile_assets do
    end

    after :updated, :compile_assets

end

namespace :composer do

  desc "Running Composer Self-Update"
  task :update do
    on roles(:web), in: :sequence, wait: 5 do
      if test "[", "-e", "/usr/local/bin/composer", "]"
        execute :sudo, :composer, "self-update"
      end
    end
  end

  desc "Running Composer Install"
  task :install do
    on roles(:web), in: :sequence, wait: 5 do
      within release_path do
        execute :composer, "install"
      end
    end
  end

  desc "Running Composer Dump Autoload"
  task :install do
    on roles(:web), in: :sequence, wait: 5 do
      within release_path do
        execute :composer, "dump-autoload"
      end
    end
  end

end

namespace :laravel do

    desc "Setup Laravel folder permissions"
    task :permissions do
        on roles(:web), in: :sequence, wait: 5 do
            within release_path do
              unless test("[ -f storage/framework ]")
                execute :mkdir, "-p storage/framework"
                execute :chmod, "777 storage/framework"
              end

              unless test("[ -f storage/framework/cache ]")
                execute :mkdir, "-p storage/framework/cache"
                execute :chmod, "777 storage/framework/cache"
              end

              unless test("[ -f storage/logs ]")
                execute :mkdir, "-p storage/logs"
                execute :chmod, "777 storage/logs"
              end

              unless test("[ -f storage/app ]")
                execute :mkdir, "-p storage/app"
                execute :chmod, "777 storage/app"
              end

              unless test("[ -f storage/framework/sessions ]")
                execute :mkdir, "-p storage/framework/sessions"
                execute :chmod, "777 storage/framework/sessions"
              end

              unless test("[ -f storage/framework/views ]")
                execute :mkdir, "-p  storage/framework/views"
                execute :chmod, "777 storage/framework/views"
              end
            end
        end
    end

    desc "Create image folder"
    task :public_images do
      on roles(:web), in: :sequence, wait: 5 do
        within release_path do
            execute :chmod, "u+x artisan"
            execute :mkdir, "-p  public/photos"
            execute :chmod, "-R 777 public/photos"
        end
      end
    end

    desc "Optimize Laravel Class Loader"
    task :optimize do
        on roles(:web), in: :sequence, wait: 5 do
            within release_path do
                execute :php, "artisan clear-compiled"
                execute :php, "artisan optimize"
            end
        end
    end

    desc "Run Laravel Artisan migrate task."
    task :migrate do
        on roles(:web), in: :sequence, wait: 5 do
        within release_path do
            execute :php, "artisan migrate --force"
            end
        end
    end

end

namespace :db_access do
  desc 'Copy production database.php from local workstation'
  task :copy_production => :production do
    on roles(:web), in: :sequence, wait: 5 do
      upload! 'config/database-production.php', "#{release_path}/config/database.php"
    end
  end

  task :copy_staging => :staging do
    on roles(:web), in: :sequence, wait: 5 do
      upload! 'config/database-staging.php', "#{release_path}/config/database.php"
    end
  end
end

namespace :fpm do
  desc 'Restart the PHP7.0 FPM'
  task :restart do
    on roles(:web) do
      execute :sudo, '/usr/sbin/service php7.0-fpm restart'
    end
  end
end

namespace :deploy do

    after :published, "laravel:permissions"
    after :published, "composer:update"
    after :published, "composer:install"
    # after :published, "laravel:public_images"
    after :published, "laravel:optimize"
    after :published, "laravel:migrate"
    after :published, "fpm:restart"

end