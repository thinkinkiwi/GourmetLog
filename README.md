#### Sail経由でのDockerコンテナ起動から行うこと
```
./vendor/bin/sail up -d
./vendor/bin/sail composer dump
./vendor/bin/sail artisan migrate:fresh --seed
```

