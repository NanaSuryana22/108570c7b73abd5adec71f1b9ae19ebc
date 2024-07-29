1. Clone / download project atau repository ini
2. jalankan composer require phpmailer/phpmailer pada command line
3. jalankan composer require firebase/php-jwt
4. Buat database secara manual di POstgreSQL dengan nama database TugasEmailNanaSuryana atau jalankan script SQL berikut : 
-- Database: TugasEmailNanaSuryana

-- DROP DATABASE IF EXISTS "TugasEmailNanaSuryana";

CREATE DATABASE "TugasEmailNanaSuryana"
    WITH
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'English_United States.1252'
    LC_CTYPE = 'English_United States.1252'
    LOCALE_PROVIDER = 'libc'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1
    IS_TEMPLATE = False;

5. Buat table users dengan menjalankan script SQL berikut : 

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

6. Buat Table Email dengan menjalankan script SQL berikut : 

CREATE TABLE emails (
    id SERIAL PRIMARY KEY,
	user_id INTEGER,
    recipient VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
	status VARCHAR(50) DEFAULT 'pending',
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


