-- Query-Venture Database Schema
-- Run this in phpMyAdmin or MySQL CLI before starting the game.
-- phpMyAdmin: Import this file, OR paste into the SQL tab.

CREATE DATABASE IF NOT EXISTS query_venture
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE query_venture;

-- ── TABLES ────────────────────────────────────────────────────────────────────

CREATE TABLE IF NOT EXISTS players (
  id            INT          AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(20)  UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  email         VARCHAR(100) UNIQUE DEFAULT NULL,
  created_at    TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  last_login    TIMESTAMP    NULL DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS characters (
  id          INT         AUTO_INCREMENT PRIMARY KEY,
  player_id   INT         NOT NULL,
  char_type   ENUM('boy','girl') NOT NULL DEFAULT 'boy',
  skin_color  VARCHAR(20) NOT NULL DEFAULT '#FFD39B',
  hair_color  VARCHAR(20) NOT NULL DEFAULT '#2c1810',
  updated_at  TIMESTAMP   DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_player (player_id),
  FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS game_progress (
  id            INT       AUTO_INCREMENT PRIMARY KEY,
  player_id     INT       NOT NULL,
  current_level INT       NOT NULL DEFAULT 1,
  max_unlocked  INT       NOT NULL DEFAULT 1,
  score         INT       NOT NULL DEFAULT 0,
  player_hp     INT       NOT NULL DEFAULT 100,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_player (player_id),
  FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS level_completions (
  id              INT       AUTO_INCREMENT PRIMARY KEY,
  player_id       INT       NOT NULL,
  level_id        INT       NOT NULL,
  stars           INT       NOT NULL DEFAULT 0,
  correct_answers INT       NOT NULL DEFAULT 0,
  score_earned    INT       NOT NULL DEFAULT 0,
  completed_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uq_player_level (player_id, level_id),
  FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS questions (
  id            INT  AUTO_INCREMENT PRIMARY KEY,
  level_id      INT  NOT NULL,
  question_text TEXT NOT NULL,
  opt_a         TEXT NOT NULL,
  opt_b         TEXT NOT NULL,
  opt_c         TEXT NOT NULL,
  opt_d         TEXT NOT NULL,
  correct_index INT  NOT NULL COMMENT '0=A 1=B 2=C 3=D',
  explanation   TEXT,
  topic         VARCHAR(100)
);

-- ── SEED QUESTIONS ─────────────────────────────────────────────────────────────

INSERT INTO questions (level_id, question_text, opt_a, opt_b, opt_c, opt_d, correct_index, explanation, topic) VALUES
-- Level 1 — SQL Basics
(1,'What does SQL stand for?',
 'Structured Query Language','Simple Query Logic','Sequential Query Link','Structured Question List',
 0,'SQL = Structured Query Language — the standard language for relational databases.','SQL Basics'),
(1,'Which SQL command retrieves data from a table?',
 'INSERT','DELETE','SELECT','UPDATE',
 2,'SELECT retrieves data. INSERT adds rows, UPDATE modifies rows, DELETE removes rows.','SQL Basics'),
(1,'What is a PRIMARY KEY in a database?',
 'A password for the database','A unique identifier for each record in a table','The first column in any table','A key used to lock records',
 1,'A PRIMARY KEY uniquely identifies each row. It must be UNIQUE and NOT NULL.','SQL Basics'),

-- Level 2 — Normalization
(2,'What is the main purpose of database normalization?',
 'Encrypting all database data','Organizing data to reduce redundancy and dependency','Making the database run faster','Converting data to binary format',
 1,'Normalization reduces data redundancy and improves data integrity.','Normalization'),
(2,'What does 1NF (First Normal Form) require?',
 'Having at least one foreign key','All columns must share the same data type','Each column must contain atomic (indivisible) values','Having exactly one primary key',
 2,'1NF: each cell holds a single atomic value — no repeating groups or arrays.','Normalization'),
(2,'What does a FOREIGN KEY do in a relational database?',
 'Creates a new table automatically','Links a record in one table to a record in another table','Opens an external database file','Deletes duplicate records automatically',
 1,'FOREIGN KEY references the PRIMARY KEY of another table, establishing a relationship.','Normalization'),

-- Level 3 — Indexing & Queries
(3,'What is the primary purpose of a database INDEX?',
 'Encrypting table columns for security','Speeding up data retrieval and search operations','Organizing pages alphabetically','Counting total database records',
 1,'An INDEX lets the DB find rows faster — like a book\'s index.','Indexing'),
(3,'What does the SQL WHERE clause do?',
 'Joins two tables together','Shows table structure','Creates a new database','Filters records based on a specified condition',
 3,'WHERE filters rows in a query result — only matching rows are returned.','Queries'),
(3,'What is a VIEW in SQL?',
 'A 3D visualization of database data','A backup copy of the database','A virtual table based on the result of a SELECT query','A screenshot of the current database state',
 2,'A VIEW is a saved SELECT query treated as a virtual table — stores no data itself.','Queries'),

-- Level 4 — Transactions & Security
(4,'What does ACID stand for in database transactions?',
 'Access, Control, Input, Data','Atomicity, Consistency, Isolation, Durability','Authentication, Cipher, Integrity, Decryption','Atomic, Combined, Isolated, Distributed',
 1,'ACID = Atomicity, Consistency, Isolation, Durability — reliable transaction guarantees.','Transactions'),
(4,'What is a SQL Injection attack?',
 'Adding SQL config files to a server','A security attack that inserts malicious SQL code into database queries','Injecting data into multiple databases at once','A database performance optimization method',
 1,'SQL Injection inserts malicious SQL into input fields — a critical security vulnerability!','Security'),
(4,'What is the purpose of database ENCRYPTION?',
 'Organizing tables alphabetically','Backing up the database to prevent data loss','Deleting sensitive data permanently','Converting data into a coded format to prevent unauthorized access',
 3,'Encryption converts data to unreadable format — only parties with the key can read it.','Security'),

-- Level 5 — Advanced ADBMS
(5,'What is database REPLICATION?',
 'Removing duplicate records from tables','A type of SQL JOIN operation','Creating synchronized copies of a database across multiple servers','Encrypting user passwords with a salt',
 2,'Replication copies data to multiple servers — improves availability and fault tolerance.','Advanced ADBMS'),
(5,'What is a DEADLOCK in database systems?',
 'When the database server crashes permanently','When data is deleted without a backup','When the storage disk is completely full','When two transactions permanently wait for each other to release locks',
 3,'Deadlock: Tx A waits for B\'s lock, B waits for A\'s — neither can proceed!','Advanced ADBMS'),
(5,'What is database SHARDING?',
 'Encrypting database files for security','Combining multiple databases into one','Creating full backups of the entire database','Splitting a large database into smaller parts distributed across multiple servers',
 3,'Sharding horizontally partitions data across servers — each shard holds a subset.','Advanced ADBMS');
