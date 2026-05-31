# Query-Venture — ADBMS Adventure Game

**An educational browser-based RPG for Advanced Database Management Systems**
*Cavite State University Naic — ADBMS Module*

---

## Overview

Query-Venture is a retro-styled, cyberpunk RPG quiz game where players battle enemy hackers by answering ADBMS questions correctly. Built as a single HTML file with no dependencies beyond Google Fonts.

---

## Features

- **5 levels** covering core ADBMS topics
- **2 playable characters** (Byte & Nova) with 3 skin tones each
- **Turn-based battle system** with HP, damage, and score tracking
- **15-second countdown timer** per question (bonus points for speed)
- **Visual effects**: matrix rain background, particle bursts, screen flash, damage numbers, scanlines
- **Chiptune sound effects** via Web Audio API
- **Fully responsive** layout for desktop and mobile

---

## Levels & Topics

| # | Level Name | Enemy | Enemy HP | Topic |
|---|------------|-------|----------|-------|
| 1 | SQL Dungeon | SQL Slime | 80 | SQL Basics |
| 2 | Normal Nexus | Norm Bot | 100 | Normalization |
| 3 | Index Citadel | Index Imp | 120 | Indexing & Queries |
| 4 | Txn Tower | Txn Troll | 140 | Transactions & Security |
| 5 | Hacker HQ | Arch Hacker | 180 | Advanced ADBMS |

---

## Gameplay Mechanics

### Answering Questions
- Each level contains **3 questions**
- Answer correctly → deal damage to the enemy
- Answer incorrectly (or time out) → take damage from the enemy
- Damage dealt = `20 + (remaining_seconds × 2)`
- Points earned = `100 + (remaining_seconds × 10)`

### Player HP
- Starts at **100 HP**, restored at the beginning of each level
- If HP drops to 0 → **Game Over**

### Winning a Level
- Enemy HP reaches 0, **or**
- Player answers at least 2 out of 3 questions correctly

### Stars
- ⭐ 1 star — 1 correct answer
- ⭐⭐ 2 stars — 2 correct answers
- ⭐⭐⭐ 3 stars — 3 correct answers

---

## Characters

### Byte — Database Guardian
- High defense vs SQL attacks
- Blue outfit with circuit-board motif

### Nova — Query Specialist
- Critical hits on correct answers
- Purple outfit with DB motif

Both characters support **3 skin tone options** and custom usernames (up to 12 characters).

---

## Screens & Flow

```
Title Screen
    └─► Character Select
            └─► World Map
                    └─► Battle Screen
                            ├─► Level Complete ──► World Map (or Victory)
                            └─► Game Over ──► Retry / World Map
```

---

## Technical Details

| Property | Value |
|----------|-------|
| Format | Single HTML file (no build step) |
| Fonts | Orbitron, Share Tech Mono, VT323 (Google Fonts) |
| Audio | Web Audio API (oscillator-based beeps) |
| Graphics | Inline SVG sprites |
| Background | Canvas-based matrix rain animation |
| Storage | None — all state held in memory (`G` object) |

---

## Question Bank

### Level 1 — SQL Basics
1. What does SQL stand for? → **Structured Query Language**
2. Which SQL command retrieves data from a table? → **SELECT**
3. What is a PRIMARY KEY? → **A unique identifier for each record**

### Level 2 — Normalization
1. Main purpose of database normalization? → **Reduce redundancy and dependency**
2. What does 1NF require? → **Atomic (indivisible) column values**
3. What does a FOREIGN KEY do? → **Links a record to a record in another table**

### Level 3 — Indexing & Queries
1. Primary purpose of a database INDEX? → **Speeds up data retrieval**
2. What does the SQL WHERE clause do? → **Filters records based on a condition**
3. What is a VIEW in SQL? → **A virtual table based on a SELECT query**

### Level 4 — Transactions & Security
1. What does ACID stand for? → **Atomicity, Consistency, Isolation, Durability**
2. What is a SQL Injection attack? → **Inserts malicious SQL code into queries**
3. Purpose of database ENCRYPTION? → **Converts data to prevent unauthorized access**

### Level 5 — Advanced ADBMS
1. What is database REPLICATION? → **Synchronized copies across multiple servers**
2. What is a DEADLOCK? → **Two transactions permanently waiting for each other**
3. What is database SHARDING? → **Splitting a large database across multiple servers**

---

## How to Run

1. Save the `.html` file to your computer
2. Open it in any modern web browser (Chrome, Firefox, Edge)
3. No internet connection required after fonts load
4. No installation or server needed

---

*Query-Venture © Cavite State University Naic — ADBMS Module*
