init: init-ci

init-ci: docker-down-clear \
	api-clear \
	docker-pull \
	docker-build \
	docker-up \
	api-init

up: docker-up
down: docker-down
restart: down up
check: lint analyze validate-schema test test-e2e
lint: api-lint frontend-lint cucumber-lint
analyze: api-analyze
validate-schema: api-validate-schema
test: api-test api-fixtures frontend-test
test-unit: api-test-unit
test-functional: api-test-functional api-fixtures

update-deps: api-composer-update frontend-yarn-upgrade cucumber-yarn-upgrade restart

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build --pull

api-clear:
	docker run --rm -v ${PWD}:/dapp -w /dapp alpine sh -c 'rm -rf storage/logs/* storage/framework/sessions/*'

api-init: api-composer-install api-permissions api-wait-db api-key-generate

api-init-full: api-composer-install api-permissions api-wait-db api-migrate

api-permissions:
	docker run --rm -v ${PWD}:/dapp -w /dapp alpine chmod -R 777 storage/logs storage/framework storage/framework/sessions

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-composer-update:
	docker-compose run --rm api-php-cli composer update

api-wait-db:
	docker-compose run --rm api-php-cli wait-for-it api-postgres:5432 -t 30

api-key-generate:
	docker-compose run --rm api-php-cli php artisan key:generate

api-migrate:
	docker-compose run --rm api-php-cli php artisan migrate

api-migration-status:
	docker-compose run --rm api-php-cli php artisan migrate:status

api-migration-create:
	docker-compose run --rm api-php-cli php artisan make:migration create_roles_table

api-routes:
	docker-compose run --rm api-php-cli php artisan route:list -v

api-check: api-lint api-analyze api-test

api-lint:
	docker-compose run --rm api-php-cli composer lint
	docker-compose run --rm api-php-cli composer php-cs-fixer fix -- --dry-run --diff

api-cs-fix:
	docker-compose run --rm api-php-cli composer php-cs-fixer fix

api-analyze:
	docker-compose run --rm api-php-cli composer psalm -- --no-diff

api-analyze-diff:
	docker-compose run --rm api-php-cli composer psalm

api-test:
	docker-compose run --rm api-php-cli composer test

api-test-coverage:
	docker-compose run --rm api-php-cli composer test-coverage

api-test-unit:
	docker-compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-unit-coverage:
	docker-compose run --rm api-php-cli composer test-coverage -- --testsuite=unit

api-test-functional:
	docker-compose run --rm api-php-cli composer test -- --testsuite=functional

api-test-functional-coverage:
	docker-compose run --rm api-php-cli composer test-coverage -- --testsuite=functional

build: build-frontend build-api

build-frontend:
	docker --log-level=debug build --pull --file=frontend/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-frontend:${IMAGE_TAG} frontend

build-api:
	docker --log-level=debug build --pull --file=api/docker/production/nginx/Dockerfile --tag=${REGISTRY}/auction-api:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/auction-api-php-cli:${IMAGE_TAG} api
	docker --log-level=debug build --pull --file=api/docker/common/postgres-backup/Dockerfile --tag=${REGISTRY}/auction-api-postgres-backup:${IMAGE_TAG} api/docker/common

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

push: push-frontend push-api

push-frontend:
	docker push ${REGISTRY}/auction-frontend:${IMAGE_TAG}

push-api:
	docker push ${REGISTRY}/auction-api:${IMAGE_TAG}
	docker push ${REGISTRY}/auction-api-php-fpm:${IMAGE_TAG}
	docker push ${REGISTRY}/auction-api-php-cli:${IMAGE_TAG}
	docker push ${REGISTRY}/auction-api-postgres-backup:${IMAGE_TAG}

testing-build: testing-build-testing-api-php-cli testing-build-cucumber

testing-build-testing-api-php-cli:
	docker --log-level=debug build --pull --file=api/docker/testing/php-cli/Dockerfile --tag=${REGISTRY}/auction-testing-api-php-cli:${IMAGE_TAG} api

testing-build-cucumber:
	docker --log-level=debug build --pull --file=cucumber/docker/testing/node/Dockerfile --tag=${REGISTRY}/auction-cucumber-node-cli:${IMAGE_TAG} cucumber

testing-init:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml up -d
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm api-php-cli wait-for-it api-postgres:5432 -t 60
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm api-php-cli php bin/app.php migrations:migrate --no-interaction
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm testing-api-php-cli php bin/app.php fixtures:load --no-interaction
	sleep 15

testing-smoke:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm cucumber-node-cli yarn smoke-ci

testing-e2e:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml run --rm cucumber-node-cli yarn e2e-ci

testing-down-clear:
	COMPOSE_PROJECT_NAME=testing docker-compose -f docker-compose-testing.yml down -v --remove-orphans

try-testing: try-build try-testing-build try-testing-init try-testing-smoke try-testing-e2e try-testing-down-clear

try-testing-build:
	REGISTRY=localhost IMAGE_TAG=0 make testing-build

try-testing-init:
	REGISTRY=localhost IMAGE_TAG=0 make testing-init

try-testing-smoke:
	REGISTRY=localhost IMAGE_TAG=0 make testing-smoke

try-testing-e2e:
	REGISTRY=localhost IMAGE_TAG=0 make testing-e2e

try-testing-down-clear:
	REGISTRY=localhost IMAGE_TAG=0 make testing-down-clear

validate-jenkins:
	curl --user ${USER} -X POST -F "jenkinsfile=<Jenkinsfile" ${HOST}/pipeline-model-converter/validate

deploy:
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'docker network create --driver=overlay traefik-public || true'
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'rm -rf site_${BUILD_NUMBER} && mkdir site_${BUILD_NUMBER}'

	envsubst < docker-compose-production.yml > docker-compose-production-env.yml
	scp -o StrictHostKeyChecking=no -P ${PORT} docker-compose-production-env.yml deploy@${HOST}:site_${BUILD_NUMBER}/docker-compose.yml
	rm -f docker-compose-production-env.yml

	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'mkdir site_${BUILD_NUMBER}/secrets'
	scp -o StrictHostKeyChecking=no -P ${PORT} ${API_DB_PASSWORD_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/api_db_password
	scp -o StrictHostKeyChecking=no -P ${PORT} ${API_MAILER_PASSWORD_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/api_mailer_password
	scp -o StrictHostKeyChecking=no -P ${PORT} ${SENTRY_DSN_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/sentry_dsn
	scp -o StrictHostKeyChecking=no -P ${PORT} ${JWT_ENCRYPTION_KEY_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/jwt_encryption_key
	scp -o StrictHostKeyChecking=no -P ${PORT} ${JWT_PUBLIC_KEY} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/jwt_public_key
	scp -o StrictHostKeyChecking=no -P ${PORT} ${JWT_PRIVATE_KEY} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/jwt_private_key
	scp -o StrictHostKeyChecking=no -P ${PORT} ${BACKUP_AWS_SECRET_ACCESS_KEY_FILE} deploy@${HOST}:site_${BUILD_NUMBER}/secrets/backup_aws_secret_access_key

	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker stack deploy --compose-file docker-compose.yml auction --with-registry-auth --prune'

deploy-clean:
	rm -f docker-compose-production-env.yml

rollback:
	ssh -o StrictHostKeyChecking=no deploy@${HOST} -p ${PORT} 'cd site_${BUILD_NUMBER} && docker stack deploy --compose-file docker-compose.yml auction --with-registry-auth --prune'
