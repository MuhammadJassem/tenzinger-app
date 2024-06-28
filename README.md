# Tenzinger Compensation

Symfony app for the compensation calculation assignment

## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/) (v2.10+)
2. Run `Make build` to build fresh images
3. Run `Make up` to set up and start a fresh Symfony project
4. Run `Make fixtures` to generate some employees
5. Open `https://tenzinger.localhost/` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
6. Run `Make down` to stop the Docker containers.
7. You can also run `Make test` to see available tests

## Features

* Employees list(homepage) where you can select a valid month and year(not more than 10 years ago) and then you can view the compensations for all employees and also download the compensation data as CSV file
* You can also access employee data page and from there you can again select a valid month and year and then you can view the compensations data
