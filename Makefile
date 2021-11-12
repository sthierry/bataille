help:
	@echo 'up -> start containers'
	@echo 'down -> stop containers'
	@echo 'bash -> access php container\''s bash'
	@echo 'launch -> launch the bataille app'
	@echo 'test -> launch tests'

up:
	docker-compose up -d

down:
	docker-compose down

bash:
	docker exec -ti bataille_php /bin/sh

launch:
	docker exec -ti bataille_php php index.php

test:
	docker exec -ti bataille_php php test.php