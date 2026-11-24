.PHONY: up down logs shell health lint test test-mysql migrate

export CONFIRM_DB_HOST CONFIRM_DB_PORT CONFIRM_DB_USERNAME

up:
	docker compose up --build -d

down:
	docker compose down

logs:
	docker compose logs -f

shell:
	docker compose exec app sh

health:
	docker compose ps
	docker compose exec -T redis redis-cli ping
	docker compose exec -T app php artisan db:show

lint:
	composer lint:check
	composer types:check

test:
	php artisan test

# RefreshDatabase migrará el esquema de testing. Requiere autorización explícita.
test-mysql:
	@test "$(ALLOW_TEST_MIGRATIONS)" = "1" || (echo "Definir ALLOW_TEST_MIGRATIONS=1 tras autorizar migraciones en orderlab_test"; exit 1)
	php scripts/check-test-target.php
	php vendor/bin/pest --configuration=phpunit.mysql.xml

migrate:
	@test "$(CONFIRM_DB_DATABASE)" = "orderlab_dev" || (echo "Definir CONFIRM_DB_DATABASE=orderlab_dev tras autorizar la migración"; exit 1)
	@test -n "$(CONFIRM_DB_HOST)" && test -n "$(CONFIRM_DB_PORT)" && test -n "$(CONFIRM_DB_USERNAME)" || (echo "Confirmar host, puerto y usuario efectivos"; exit 1)
	docker compose exec -T -e CONFIRM_DB_HOST -e CONFIRM_DB_PORT -e CONFIRM_DB_USERNAME app php scripts/check-migrate-target.php
	docker compose exec -T app php artisan migrate --force
