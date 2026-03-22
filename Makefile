.PHONY: setup up down restart build shell artisan migrate migrate-fresh seed test test-unit test-integration test-feature lint composer logs

# ─── Ambiente ────────────────────────────────────────────────────────────────

setup: build up composer-install migrate seed
	@echo "✅ MyFinance pronto em http://localhost:8080"

up:
	docker compose up -d

down:
	docker compose down

restart: down up

build:
	docker compose build --no-cache

logs:
	docker compose logs -f

# ─── Shell ───────────────────────────────────────────────────────────────────

shell:
	docker compose exec app sh

shell-db:
	docker compose exec postgres psql -U myfinance -d myfinance

# ─── Laravel ─────────────────────────────────────────────────────────────────

artisan:
	docker compose exec app php artisan $(cmd)

composer-install:
	docker compose exec app composer install

composer:
	docker compose exec app composer $(cmd)

migrate:
	docker compose exec app php artisan migrate

migrate-fresh:
	docker compose exec app php artisan migrate:fresh --seed

seed:
	docker compose exec app php artisan db:seed

# ─── Testes ──────────────────────────────────────────────────────────────────

test:
	docker compose exec app php artisan test

test-unit:
	docker compose exec app php artisan test --testsuite=Unit

test-integration:
	docker compose exec app php artisan test --testsuite=Integration

test-feature:
	docker compose exec app php artisan test --testsuite=Feature

test-coverage:
	docker compose exec app php artisan test --coverage --min=85

# ─── Qualidade ───────────────────────────────────────────────────────────────

lint:
	docker compose exec app ./vendor/bin/pint

lint-check:
	docker compose exec app ./vendor/bin/pint --test

mutation:
	docker compose exec app ./vendor/bin/infection --threads=4
