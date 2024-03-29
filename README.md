# Laravel microservice platform
[![Build Status](https://cloud.drone.io/api/badges/beam-australia/platform-php/status.svg)](https://cloud.drone.io/beam-australia/platform-php)
[![Coverage Status](https://coveralls.io/repos/github/beam-australia/platform-php/badge.svg?branch=master)](https://coveralls.io/github/beam-australia/platform-php?branch=master)

## Testing

There is a docker helper binary located in the `bin` directory.

```bash
$> bin/phpunit
```

## Integration testing

For integration testing with external services such as Elasticsearch or Redis, use Docker Compose. There are helpers located in the `bin` directory:

```bash
$> bin/ssh
```

Will start `docker-compose.yml` and ssh into the container. Be sure to wait until the services are ready.



