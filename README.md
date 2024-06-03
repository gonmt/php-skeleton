<h1 align="center">
    CompanyName
</h1>

## Environment Setup

### Needed tools

1. [Install Docker](https://www.docker.com/get-started)
2. [Install Docker compose](https://docs.docker.com/compose/install/)
3. Clone this project: `git clone REPOSITORY_URL`
4. Move to the project folder: `cd REPOSITORY_NAME`

### Start application

1. Start local environment: `make start-local`
2. Check firstapp backend status: http://localhost:8080/health-check
3. firstapp backend API Docs: http://localhost:8080/openapi.html

### Tests and linting execution

1. Execute tests: `make test`
2. Execute static analysis: `make static-analysis`
3. Execute architecture tests: `make test-architecture`
4. Execute code style check: `make lint`
5. Fix code style: `make lint-fix`
6. Mess detector: `make mess-detector`
7. Execute all tests and linting before commit: `make pre-commit-validations`
