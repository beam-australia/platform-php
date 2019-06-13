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




