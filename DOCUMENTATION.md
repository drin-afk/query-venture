# Query-Venture — Full Project Documentation

> **An educational browser-based RPG for Advanced Database Management Systems**  
> Cavite State University Naic — ADBMS Module  
> Lecture Activity 2: Game Concept Development with Database Integration

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Game Concept](#2-game-concept)
3. [Tech Stack](#3-tech-stack)
4. [Project Structure](#4-project-structure)
5. [Database Design](#5-database-design)
6. [Backend API Reference](#6-backend-api-reference)
7. [Game Features](#7-game-features)
8. [Battle System](#8-battle-system)
9. [Power-Ups](#9-power-ups)
10. [Combo System](#10-combo-system)
11. [Achievement System](#11-achievement-system)
12. [Leaderboard](#12-leaderboard)
13. [Question Bank](#13-question-bank)
14. [Setup & Installation](#14-setup--installation)
15. [File Descriptions](#15-file-descriptions)

---

## 1. Project Overview

**Query-Venture** is a web-based adventure and learning game designed for students aged 15 and above. Players choose and customize a character, then battle through 5 levels of increasingly difficult enemies. The only weapon available is **knowledge** — players must answer ADBMS (Advanced Database Management Systems) questions correctly to deal damage and progress.

### Goals
- Entertain and educate simultaneously
- Teach ADBMS concepts through gameplay
- Cover topics: SQL, Normalization, Indexing, Transactions, Security, and Advanced ADBMS
- Help players understand how to prevent hackers and data breaches

### Platform
Web — runs on any modern browser via XAMPP (Apache + MySQL + PHP)

---

## 2. Game Concept

| Property       | Value                                                          |
|----------------|----------------------------------------------------------------|
| **Title**      | Query-Venture                                                  |
| **Genre**      | Adventure / Educational RPG                                    |
| **Platform**   | Web (XAMPP localhost)                                          |
| **Players**    | Individuals aged 15 and above                                  |
| **Unique Feature** | Blends fun gameplay with ADBMS education               |

### Story
Players select a hero (Byte or Nova), enter the **Database Realm**, and navigate 5 levels filled with enemies that represent database threats. Each enemy is defeated only when the player correctly answers database-related questions. As levels progress, enemies grow stronger and questions become harder.

### Screen Flow
```
Title Screen
    └─► Character Select
            └─► World Map
                    └─► Enemy Dialogue (pre-battle intro)
                            └─► Battle Screen
                                    ├─► Level Complete ──► World Map (or Victory)
                                    └─► Game Over ──► Retry / World Map
```

---

## 3. Tech Stack

| Layer      | Technology                          |
|------------|-------------------------------------|
| Frontend   | HTML5, CSS3, Vanilla JavaScript      |
| Backend    | PHP 8.x                             |
| Database   | MySQL (via XAMPP)                    |
| Auth       | PHP Sessions                         |
| PDF Export | FPDF (PHP library)                  |
| Fonts      | Orbitron, Share Tech Mono, VT323    |
| Audio      | Web Audio API (oscillator-based)     |
| Graphics   | Inline SVG sprites                   |
| Background | Canvas-based matrix rain animation   |

---

## 4. Project Structure

```
query-venture/
│
├── query-venture.html          ← Full game frontend (single file)
├── download-answers.php        ← Generates downloadable PDF answer key
├── fpdf.php                    ← FPDF library (PHP PDF generation)
├── DOCUMENTATION.md            ← This file
│
├── api/
│   ├── config.php              ← DB connection, session, CORS, error handling
│   │
│   ├── auth/
│   │   ├── check.php           ← GET  — check if session is active
│   │   ├── login.php           ← POST — log in with username + password
│   │   ├── register.php        ← POST — create new account
│   │   └── logout.php          ← GET  — destroy session
│   │
│   ├── player/
│   │   └── profile.php         ← GET — full profile | PUT — update character
│   │
│   ├── progress/
│   │   ├── get.php             ← GET  — load saved game state
│   │   ├── save.php            ← POST — save current level/score/HP
│   │   ├── level_complete.php  ← POST — record level result (upserts best)
│   │   └── reset.php          ← POST — reset progress to new game
│   │
│   ├── leaderboard/
│   │   └── index.php           ← GET — top 20 players by score
│   │
│   └── questions/
│       ├── get.php             ← GET  ?level=1–5 — fetch questions (no answer)
│       └── answer.php          ← POST — server-side answer validation
│
└── database/
    └── schema.sql              ← MySQL setup: all tables + 15 seeded questions
```

---

## 5. Database Design

### Tables

#### `players`
Stores registered player accounts.

| Column          | Type          | Notes                        |
|-----------------|---------------|------------------------------|
| `id`            | INT PK AI     | Primary key                  |
| `username`      | VARCHAR(20)   | Unique, 3–20 chars           |
| `password_hash` | VARCHAR(255)  | bcrypt hashed                |
| `email`         | VARCHAR(100)  | Optional, unique             |
| `created_at`    | TIMESTAMP     | Auto set on insert           |
| `last_login`    | TIMESTAMP     | Updated on each login        |

#### `characters`
Stores each player's selected hero and skin.

| Column       | Type             | Notes                        |
|--------------|------------------|------------------------------|
| `id`         | INT PK AI        |                              |
| `player_id`  | INT FK           | References `players.id`      |
| `char_type`  | ENUM('boy','girl')| Default: `'boy'`            |
| `skin_color` | VARCHAR(20)      | Default: `'#FFD39B'`         |
| `hair_color` | VARCHAR(20)      | Default: `'#2c1810'`         |
| `updated_at` | TIMESTAMP        | Auto-updated                 |

#### `game_progress`
Stores the player's current game state (save/load).

| Column          | Type      | Notes                        |
|-----------------|-----------|------------------------------|
| `id`            | INT PK AI |                              |
| `player_id`     | INT FK    | References `players.id`      |
| `current_level` | INT       | Default: 1                   |
| `max_unlocked`  | INT       | Default: 1 (highest unlocked)|
| `score`         | INT       | Default: 0                   |
| `player_hp`     | INT       | Default: 100                 |
| `updated_at`    | TIMESTAMP | Auto-updated                 |

#### `level_completions`
Tracks the best result per level per player.

| Column            | Type      | Notes                          |
|-------------------|-----------|--------------------------------|
| `id`              | INT PK AI |                                |
| `player_id`       | INT FK    | References `players.id`        |
| `level_id`        | INT       | 1–5                            |
| `stars`           | INT       | 1–3 stars earned               |
| `correct_answers` | INT       | Correct answers in that run    |
| `score_earned`    | INT       | Score earned in that run       |
| `completed_at`    | TIMESTAMP | Last completion timestamp      |
| **UNIQUE**        |           | `(player_id, level_id)` — upserts keep best result |

#### `questions`
Stores all quiz questions. `correct_index` is never sent to the client.

| Column          | Type         | Notes                         |
|-----------------|--------------|-------------------------------|
| `id`            | INT PK AI    |                               |
| `level_id`      | INT          | 1–5                           |
| `question_text` | TEXT         |                               |
| `opt_a`–`opt_d` | TEXT         | 4 answer choices              |
| `correct_index` | INT          | 0=A, 1=B, 2=C, 3=D            |
| `explanation`   | TEXT         | Shown after answering         |
| `topic`         | VARCHAR(100) | e.g. "SQL Basics"             |

### Entity Relationships

```
players ──< characters         (one player → one character profile)
players ──< game_progress      (one player → one save slot)
players ──< level_completions  (one player → many level records)
```

---

## 6. Backend API Reference

All endpoints return JSON. All POST/PUT endpoints accept `Content-Type: application/json`.  
Authentication uses PHP sessions via `PHPSESSID` cookie — include `credentials: 'include'` in all fetch() calls.

### Auth

| Method | Endpoint                    | Auth | Body / Params                        | Returns                            |
|--------|-----------------------------|------|--------------------------------------|------------------------------------|
| POST   | `api/auth/register.php`     | —    | `{username, password, email?}`       | `{message, username, playerId}`    |
| POST   | `api/auth/login.php`        | —    | `{username, password}`               | `{message, username, playerId}`    |
| GET    | `api/auth/check.php`        | —    | —                                    | `{loggedIn, playerId?, username?}` |
| GET    | `api/auth/logout.php`       | —    | —                                    | `{message}`                        |

### Player

| Method | Endpoint                    | Auth | Body / Params                        | Returns                            |
|--------|-----------------------------|------|--------------------------------------|------------------------------------|
| GET    | `api/player/profile.php`    | ✓    | —                                    | `{player, completions[]}`          |
| PUT    | `api/player/profile.php`    | ✓    | `{char_type?, skin_color?, hair_color?}` | `{message}`                    |

### Progress

| Method | Endpoint                           | Auth | Body / Params                                               | Returns        |
|--------|------------------------------------|------|-------------------------------------------------------------|----------------|
| GET    | `api/progress/get.php`             | ✓    | —                                                           | `{progress, completions[], character}` |
| POST   | `api/progress/save.php`            | ✓    | `{current_level, max_unlocked, score, player_hp}`           | `{message}`    |
| POST   | `api/progress/level_complete.php`  | ✓    | `{level_id, stars, correct_answers, score_earned}`          | `{message}`    |
| POST   | `api/progress/reset.php`           | ✓    | —                                                           | `{message}`    |

### Questions

| Method | Endpoint                            | Auth | Body / Params               | Returns                                    |
|--------|-------------------------------------|------|-----------------------------|--------------------------------------------|
| GET    | `api/questions/get.php?level=N`     | —    | `level` = 1–5               | `{level, questions[]}` (no correct_index) |
| POST   | `api/questions/answer.php`          | —    | `{question_id, selected}`   | `{correct, correct_index, explanation}`    |

> `selected`: pass **-1** for a timeout (always marked wrong server-side).  
> `correct_index` is **never** included in `get.php` — answers are validated server-side only.

### Leaderboard

| Method | Endpoint                    | Auth | Returns                                        |
|--------|-----------------------------|------|------------------------------------------------|
| GET    | `api/leaderboard/index.php` | —    | `{leaderboard[{rank,username,score,max_unlocked,updated_at}]}` |

### PDF Answer Key

| Method | Endpoint                    | Returns                         |
|--------|-----------------------------|---------------------------------|
| GET    | `download-answers.php`      | PDF file download (FPDF)        |

---

## 7. Game Features

### Characters

| Hero  | Description            | Class             | Ability                       |
|-------|------------------------|-------------------|-------------------------------|
| BYTE  | Database Guardian      | Boy (blue outfit) | Circuit-board motif, defense  |
| NOVA  | Query Specialist       | Girl (purple)     | DB motif, speed crits         |

Both characters support **3 skin tone options** and a **custom username** (up to 12 characters). Character selection and skin are saved to the database after each level.

### Levels

| # | Level Name     | Enemy Name  | Enemy HP | Enemy DMG | Topic                    |
|---|----------------|-------------|----------|-----------|--------------------------|
| 1 | SQL Dungeon    | SQL Slime   | 80       | 18        | SQL Basics               |
| 2 | Normal Nexus   | Norm Bot    | 100      | 22        | Normalization            |
| 3 | Index Citadel  | Index Imp   | 120      | 26        | Indexing & Queries       |
| 4 | Txn Tower      | Txn Troll   | 140      | 30        | Transactions & Security  |
| 5 | Hacker HQ      | Arch Hacker | 180      | 35        | Advanced ADBMS (Boss)    |

### Progression
- Levels unlock in order — complete Level N to unlock Level N+1
- Player HP restores to **100** at the start of each level
- Progress (level, score, HP, character) is saved to the database after every level completion

---

## 8. Battle System

### Answering Questions
- Each level contains **3 questions** loaded from the MySQL database
- Correct answer → deal damage to the enemy
- Wrong answer or timeout → take damage from the enemy
- **Damage dealt** = `floor((20 + remaining_seconds × 2) × combo_multiplier)`
- **Points earned** = `floor((100 + remaining_seconds × 10) × combo_multiplier)`
- Timer: **15 seconds** per question

### Winning a Level
- Enemy HP reaches 0, **OR**
- Player answers at least 2 out of 3 questions correctly

### Stars
| Stars | Condition                  |
|-------|---------------------------|
| ⭐    | 1 correct answer           |
| ⭐⭐  | 2 correct answers          |
| ⭐⭐⭐| 3 correct answers (Perfect)|

### Perfect Round Bonus
Answer all 3 questions correctly → **+500 bonus points** + gold flash animation

### Enemy Rage Mode
When enemy HP falls below **25%** of max:
- Enemy sprite glows red with pulsing animation
- "😡 RAGE MODE!" banner appears
- Enemy deals **+50% damage** (base dmg × 1.5)

### Game Over
Player HP drops to **0** → Game Over screen  
Player can **Retry** the same level or return to the **World Map**

---

## 9. Power-Ups

Each battle starts with **1 use of each** power-up. They reset every new level.

| Icon | Name   | Effect                                           | Limit     |
|------|--------|--------------------------------------------------|-----------|
| 💡   | HINT   | Eliminates 2 wrong answer buttons for the current question | 1 per level |
| 🛡️  | SHIELD | Absorbs the next enemy hit completely (shows "BLOCKED!") | 1 per level |
| ⚡   | BOOST  | Adds +8 seconds to the current question timer   | 1 per level |

> **Note:** HINT calls the server to confirm the correct answer before eliminating options — it never cheats by revealing the answer directly.

---

## 10. Combo System

Consecutive correct answers build a combo multiplier shown in the battle HUD.

| Combo Streak | Multiplier | Display             |
|--------------|------------|---------------------|
| 1 (normal)   | ×1.0       | COMBO x1 (grey)     |
| 2 in a row   | ×1.5       | 🔥 COMBO x2 (gold)  |
| 3+ in a row  | ×2.0       | 🔥🔥 COMBO x3 (orange, blinking) |

- A **combo flash** overlay pops up at the center of the screen on 2+ combos
- **Wrong answer or timeout** resets combo to x1
- Combo multiplier applies to **both damage dealt AND score earned**
- Damage numbers show a 🔥 icon when a multiplier is active

---

## 11. Achievement System

Achievements are stored in **localStorage** and displayed as slide-in toast notifications.

| Icon | Achievement      | Unlock Condition                              |
|------|------------------|-----------------------------------------------|
| 🏆   | First Victory    | Complete Level 1 for the first time           |
| ⭐   | Perfect Scholar  | Answer all 3 questions correctly in any level |
| ⚡   | Speed Demon      | Answer correctly with more than 12 sec remaining |
| 🛡️  | Untouchable      | Clear a level without taking any damage       |
| 🔥   | On Fire          | Build a 3× combo streak                       |
| 👑   | Database Master  | Complete all 5 levels                         |

Achievements are **permanent** — once earned they are stored locally and never shown again for that browser/device.

---

## 12. Leaderboard

### How it works
- Displayed as a **full-screen modal overlay** accessible from the Title Screen and the World Map
- Top 20 players ranked by total score
- Your own row is **highlighted in cyan** with a "YOU" tag
- Level progress shown as a visual bar (`███░░ 3/5`)
- **↻ REFRESH** button to fetch the latest data with a spin animation
- Click outside the modal or press CLOSE to dismiss

### Ranking
- Rank 1 → 🥇, Rank 2 → 🥈, Rank 3 → 🥉
- Ranks 4–20 → numbered (#4, #5, ...)
- Only players with `score > 0` appear on the board

---

## 13. Question Bank

### Level 1 — SQL Basics

| Q# | Question | Answer |
|----|----------|--------|
| 1  | What does SQL stand for? | **A) Structured Query Language** |
| 2  | Which SQL command retrieves data from a table? | **C) SELECT** |
| 3  | What is a PRIMARY KEY in a database? | **B) A unique identifier for each record** |

### Level 2 — Normalization

| Q# | Question | Answer |
|----|----------|--------|
| 4  | What is the main purpose of database normalization? | **B) Organizing data to reduce redundancy and dependency** |
| 5  | What does 1NF (First Normal Form) require? | **C) Each column must contain atomic (indivisible) values** |
| 6  | What does a FOREIGN KEY do in a relational database? | **B) Links a record in one table to a record in another table** |

### Level 3 — Indexing & Queries

| Q# | Question | Answer |
|----|----------|--------|
| 7  | What is the primary purpose of a database INDEX? | **B) Speeding up data retrieval and search operations** |
| 8  | What does the SQL WHERE clause do? | **D) Filters records based on a specified condition** |
| 9  | What is a VIEW in SQL? | **C) A virtual table based on the result of a SELECT query** |

### Level 4 — Transactions & Security

| Q# | Question | Answer |
|----|----------|--------|
| 10 | What does ACID stand for in database transactions? | **B) Atomicity, Consistency, Isolation, Durability** |
| 11 | What is a SQL Injection attack? | **B) A security attack that inserts malicious SQL code into queries** |
| 12 | What is the purpose of database ENCRYPTION? | **D) Converting data into a coded format to prevent unauthorized access** |

### Level 5 — Advanced ADBMS (Boss)

| Q# | Question | Answer |
|----|----------|--------|
| 13 | What is database REPLICATION? | **C) Creating synchronized copies across multiple servers** |
| 14 | What is a DEADLOCK in database systems? | **D) When two transactions permanently wait for each other to release locks** |
| 15 | What is database SHARDING? | **D) Splitting a large database into smaller parts across multiple servers** |

---

## 14. Setup & Installation

### Requirements
- **XAMPP** (Apache + MySQL + PHP 8.x) — [https://www.apachefriends.org](https://www.apachefriends.org)
- A modern browser (Chrome, Firefox, Edge)

### Step 1 — Copy Files
Place the entire `query-venture` folder inside your XAMPP htdocs directory:
```
C:\xampp\htdocs\query-venture\
```

### Step 2 — Import the Database
1. Start **XAMPP Control Panel** → click **Start** on both **Apache** and **MySQL**
2. Open your browser and go to: `http://localhost/phpmyadmin`
3. Click **Import** in the top menu
4. Click **Choose File** → select `database/schema.sql`
5. Click **Go** at the bottom

This creates the `query_venture` database with all 5 tables and seeds all 15 questions automatically.

### Step 3 — Play the Game
Open your browser and navigate to:
```
http://localhost/query-venture/query-venture.html
```

### Step 4 — Download Answer Key (Optional)
```
http://localhost/query-venture/download-answers.php
```
This downloads a styled PDF with all 15 questions, correct answers highlighted, and explanations.

---

## 15. File Descriptions

| File | Description |
|------|-------------|
| `query-venture.html` | The entire frontend game — all HTML, CSS, and JavaScript in one file. Includes auth overlay, enemy dialogue, battle screen, combo/power-up systems, achievements, and leaderboard modal. |
| `download-answers.php` | Generates a formatted PDF answer key using FPDF. Downloads directly when accessed in the browser. |
| `fpdf.php` | FPDF library (v1.8, patched for PHP 8 compatibility). Handles PDF generation server-side. |
| `api/config.php` | Shared configuration included by all API endpoints. Sets up DB connection, session, CORS headers, JSON response helpers, and a global exception handler that catches any uncaught error and returns JSON instead of an HTML error page. |
| `api/auth/register.php` | Creates a new player account (transaction: inserts into `players`, `characters`, and `game_progress`). Starts a PHP session on success. |
| `api/auth/login.php` | Validates username + bcrypt password, updates `last_login`, starts PHP session. |
| `api/auth/check.php` | Returns whether the current PHP session is active. Called on page load to auto-resume sessions. |
| `api/auth/logout.php` | Destroys the PHP session. |
| `api/player/profile.php` | GET: returns full player profile with JOIN across all tables. PUT: updates character type/skin/hair only (early-return, no wasteful SELECT on PUT). |
| `api/progress/get.php` | Returns the player's saved game state, level completions, and character. |
| `api/progress/save.php` | Updates current level, score, HP. Uses `GREATEST(max_unlocked, ?)` to never decrease the unlocked level. |
| `api/progress/level_complete.php` | Upserts level completion with `ON DUPLICATE KEY UPDATE` — always keeps the player's best result (highest stars/score). |
| `api/progress/reset.php` | Resets progress to level 1 and deletes all level completions. |
| `api/leaderboard/index.php` | Returns top 20 players ordered by score, with rank numbers added in PHP. |
| `api/questions/get.php` | Returns questions for a given level. `correct_index` is intentionally excluded from the response. |
| `api/questions/answer.php` | Validates answers server-side. Accepts `selected: -1` as a timeout sentinel (always wrong). Never trusts the client for correctness. |
| `database/schema.sql` | Complete MySQL setup script — creates the `query_venture` database, all 5 tables with foreign keys, and inserts all 15 questions across 5 levels. Safe to re-run (`CREATE TABLE IF NOT EXISTS`). |

---

## Security Notes

| Concern | Implementation |
|---------|---------------|
| Password storage | bcrypt via PHP `password_hash()` |
| SQL injection | All queries use PDO prepared statements with bound parameters |
| Answer cheating | `correct_index` never sent to the browser; answers validated server-side only |
| Session auth | `requireLogin()` called on all protected endpoints; returns HTTP 401 if not authenticated |
| Error exposure | `display_errors = 0` in production; global exception handler returns JSON (not stack traces) |
| CORS | Origin echoed back with `Access-Control-Allow-Credentials: true` for XAMPP localhost compatibility |

---

*Query-Venture © Cavite State University Naic — ADBMS Module*  
*Built with HTML · CSS · JavaScript · PHP · MySQL*
