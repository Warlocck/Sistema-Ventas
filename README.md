# Ticket Sales System

Web-based ticket sales and point-of-sale system developed with PHP and PostgreSQL.

## Features

* Customer management
* Product and inventory management
* Point-of-sale interface
* Sales registration
* Sales and ticket search
* Customer and seller management
* QR code integration
* PostgreSQL database integration

## Tech Stack

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat\&logo=php\&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-4169E1?style=flat\&logo=postgresql\&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat\&logo=html5\&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat\&logo=css3\&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat\&logo=javascript\&logoColor=black)

## Project Structure

```text
.
├── app.php
├── index.html
├── login.html
├── PuntoDeVenta.html
├── articulo.html
├── ticket.html
├── clientes_vendedores.html
├── buscar_articulo.php
├── buscar_cliente.php
├── buscar_venta.php
├── validation.php
├── config/
│   └── database.example.php
├── db/
│   └── dbTicketVentas.sql
├── resources/
│   └── QRCODE.png
└── styles/
    ├── login.css
    └── style.css
```

## Database

The system uses PostgreSQL with a database named:

```text
dbTicketVentas
```

The database script is included in:

```text
db/dbTicketVentas.sql
```

## Local Setup

1. Install PHP and PostgreSQL.
2. Create a PostgreSQL database named `dbTicketVentas`.
3. Execute the SQL script located in `db/dbTicketVentas.sql`.
4. Copy `config/database.example.php` to:

```text
config/database.php
```

5. Configure the local PostgreSQL credentials in `config/database.php`.
6. Start the PHP development server:

```bash
php -S localhost:8000
```

7. Open the application in your browser.

> `config/database.php` is excluded from version control because it contains local database configuration.

## Purpose

Academic software project developed to manage sales, customers, products and ticket-related operations through a web-based point-of-sale system.
