build:
	docker-compose  --env-file .env -f docker-compose.yml up -d --build;
up:
	docker-compose  --env-file .env -f docker-compose.yml up -d;
down:
	docker-compose -f  docker-compose.yml down;
ps:
	docker-compose -f docker-compose.yml ps;
shell:
	docker exec -it app bash
shell-as-root:
	docker exec -u root -it app bash
test:
	docker exec -i app php artisan test
