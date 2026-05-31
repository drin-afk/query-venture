<?php
require_once __DIR__ . '/fpdf.php';

// ── ALL QUESTIONS & ANSWERS ────────────────────────────────────────────────────
$levels = [
  [
    'num'    => 1,
    'name'   => 'SQL Dungeon',
    'enemy'  => 'SQL Slime',
    'topic'  => 'SQL Basics',
    'color'  => [46, 125, 50],   // green
    'badge'  => 'LEVEL 1',
    'qs'     => [
      ['q' => 'What does SQL stand for?',
       'opts' => ['A' => 'Structured Query Language','B' => 'Simple Query Logic','C' => 'Sequential Query Link','D' => 'Structured Question List'],
       'ans' => 'A', 'exp' => 'SQL = Structured Query Language — the standard language for relational databases.'],
      ['q' => 'Which SQL command retrieves data from a table?',
       'opts' => ['A' => 'INSERT','B' => 'DELETE','C' => 'SELECT','D' => 'UPDATE'],
       'ans' => 'C', 'exp' => 'SELECT retrieves data. INSERT adds rows, UPDATE modifies, DELETE removes.'],
      ['q' => 'What is a PRIMARY KEY in a database?',
       'opts' => ['A' => 'A password for the database','B' => 'A unique identifier for each record in a table','C' => 'The first column in any table','D' => 'A key used to lock records'],
       'ans' => 'B', 'exp' => 'A PRIMARY KEY uniquely identifies each row. It must be UNIQUE and NOT NULL.'],
    ],
  ],
  [
    'num'    => 2,
    'name'   => 'Normal Nexus',
    'enemy'  => 'Norm Bot',
    'topic'  => 'Normalization',
    'color'  => [21, 101, 192],  // blue
    'badge'  => 'LEVEL 2',
    'qs'     => [
      ['q' => 'What is the main purpose of database normalization?',
       'opts' => ['A' => 'Encrypting all database data','B' => 'Organizing data to reduce redundancy and dependency','C' => 'Making the database run faster','D' => 'Converting data to binary format'],
       'ans' => 'B', 'exp' => 'Normalization reduces data redundancy and improves data integrity.'],
      ['q' => 'What does 1NF (First Normal Form) require?',
       'opts' => ['A' => 'Having at least one foreign key','B' => 'All columns must share the same data type','C' => 'Each column must contain atomic (indivisible) values','D' => 'Having exactly one primary key'],
       'ans' => 'C', 'exp' => '1NF: each cell holds a single atomic value — no repeating groups or arrays.'],
      ['q' => 'What does a FOREIGN KEY do in a relational database?',
       'opts' => ['A' => 'Creates a new table automatically','B' => 'Links a record in one table to a record in another table','C' => 'Opens an external database file','D' => 'Deletes duplicate records automatically'],
       'ans' => 'B', 'exp' => 'FOREIGN KEY references the PRIMARY KEY of another table, establishing a relationship.'],
    ],
  ],
  [
    'num'    => 3,
    'name'   => 'Index Citadel',
    'enemy'  => 'Index Imp',
    'topic'  => 'Indexing & Queries',
    'color'  => [106, 27, 154],  // purple
    'badge'  => 'LEVEL 3',
    'qs'     => [
      ['q' => 'What is the primary purpose of a database INDEX?',
       'opts' => ['A' => 'Encrypting table columns for security','B' => 'Speeding up data retrieval and search operations','C' => 'Organizing pages alphabetically','D' => 'Counting total database records'],
       'ans' => 'B', 'exp' => 'An INDEX lets the DB find rows faster — like a book index.'],
      ['q' => 'What does the SQL WHERE clause do?',
       'opts' => ['A' => 'Joins two tables together','B' => 'Shows table structure','C' => 'Creates a new database','D' => 'Filters records based on a specified condition'],
       'ans' => 'D', 'exp' => 'WHERE filters rows in a query result — only matching rows are returned.'],
      ['q' => 'What is a VIEW in SQL?',
       'opts' => ['A' => 'A 3D visualization of database data','B' => 'A backup copy of the database','C' => 'A virtual table based on the result of a SELECT query','D' => 'A screenshot of the current database state'],
       'ans' => 'C', 'exp' => 'A VIEW is a saved SELECT query treated as a virtual table — stores no data itself.'],
    ],
  ],
  [
    'num'    => 4,
    'name'   => 'Txn Tower',
    'enemy'  => 'Txn Troll',
    'topic'  => 'Transactions & Security',
    'color'  => [183, 28, 28],   // red
    'badge'  => 'LEVEL 4',
    'qs'     => [
      ['q' => 'What does ACID stand for in database transactions?',
       'opts' => ['A' => 'Access, Control, Input, Data','B' => 'Atomicity, Consistency, Isolation, Durability','C' => 'Authentication, Cipher, Integrity, Decryption','D' => 'Atomic, Combined, Isolated, Distributed'],
       'ans' => 'B', 'exp' => 'ACID = Atomicity, Consistency, Isolation, Durability — reliable transaction guarantees.'],
      ['q' => 'What is a SQL Injection attack?',
       'opts' => ['A' => 'Adding SQL config files to a server','B' => 'A security attack that inserts malicious SQL code into database queries','C' => 'Injecting data into multiple databases at once','D' => 'A database performance optimization method'],
       'ans' => 'B', 'exp' => 'SQL Injection inserts malicious SQL into input fields — a critical security vulnerability!'],
      ['q' => 'What is the purpose of database ENCRYPTION?',
       'opts' => ['A' => 'Organizing tables alphabetically','B' => 'Backing up the database to prevent data loss','C' => 'Deleting sensitive data permanently','D' => 'Converting data into a coded format to prevent unauthorized access'],
       'ans' => 'D', 'exp' => 'Encryption converts data to unreadable format — only parties with the key can read it.'],
    ],
  ],
  [
    'num'    => 5,
    'name'   => 'Hacker HQ',
    'enemy'  => 'Arch Hacker',
    'topic'  => 'Advanced ADBMS',
    'color'  => [0, 77, 64],     // teal/dark
    'badge'  => 'LEVEL 5 (BOSS)',
    'qs'     => [
      ['q' => 'What is database REPLICATION?',
       'opts' => ['A' => 'Removing duplicate records from tables','B' => 'A type of SQL JOIN operation','C' => 'Creating synchronized copies of a database across multiple servers','D' => 'Encrypting user passwords with a salt'],
       'ans' => 'C', 'exp' => 'Replication copies data to multiple servers — improves availability and fault tolerance.'],
      ['q' => 'What is a DEADLOCK in database systems?',
       'opts' => ['A' => 'When the database server crashes permanently','B' => 'When data is deleted without a backup','C' => 'When the storage disk is completely full','D' => 'When two transactions permanently wait for each other to release locks'],
       'ans' => 'D', 'exp' => 'Deadlock: Tx A waits for B\'s lock, B waits for A\'s — neither can proceed!'],
      ['q' => 'What is database SHARDING?',
       'opts' => ['A' => 'Encrypting database files for security','B' => 'Combining multiple databases into one','C' => 'Creating full backups of the entire database','D' => 'Splitting a large database into smaller parts distributed across multiple servers'],
       'ans' => 'D', 'exp' => 'Sharding horizontally partitions data across servers — each shard holds a subset.'],
    ],
  ],
];

// ── PDF CLASS WITH CUSTOM HEADER/FOOTER ───────────────────────────────────────
class AnswerSheet extends FPDF {
  function Header() {
    // top accent bar
    $this->SetFillColor(7, 11, 18);
    $this->Rect(0, 0, 210, 18, 'F');
    $this->SetFont('Helvetica', 'B', 13);
    $this->SetTextColor(0, 200, 230);
    $this->SetXY(10, 4);
    $this->Cell(0, 10, 'QUERY-VENTURE  |  ADBMS Answer Key', 0, 0, 'L');
    $this->SetFont('Helvetica', '', 8);
    $this->SetTextColor(120, 160, 200);
    $this->SetXY(10, 11);
    $this->Cell(0, 6, 'Cavite State University Naic  |  Advanced Database Management Systems', 0, 0, 'L');
    $this->Ln(12);
  }
  function Footer() {
    $this->SetY(-14);
    $this->SetFillColor(7, 11, 18);
    $this->Rect(0, $this->GetY(), 210, 20, 'F');
    $this->SetFont('Helvetica', 'I', 8);
    $this->SetTextColor(80, 120, 160);
    $this->Cell(0, 10, 'Page ' . $this->PageNo() . '  |  Query-Venture ADBMS Module', 0, 0, 'C');
  }
}

$pdf = new AnswerSheet('P', 'mm', 'A4');
$pdf->SetMargins(14, 22, 14);
$pdf->SetAutoPageBreak(true, 18);
$pdf->AddPage();

// ── COVER TITLE BLOCK ─────────────────────────────────────────────────────────
$pdf->SetFillColor(7, 11, 18);
$pdf->Rect(14, $pdf->GetY(), 182, 28, 'F');
$pdf->SetFont('Helvetica', 'B', 22);
$pdf->SetTextColor(0, 200, 230);
$pdf->SetXY(14, $pdf->GetY() + 4);
$pdf->Cell(182, 10, 'ANSWER KEY', 0, 2, 'C');
$pdf->SetFont('Helvetica', '', 11);
$pdf->SetTextColor(100, 180, 220);
$pdf->Cell(182, 8, '15 Questions Across 5 Levels', 0, 2, 'C');
$pdf->Ln(8);

// ── INTRO NOTE ────────────────────────────────────────────────────────────────
$pdf->SetFillColor(230, 245, 255);
$pdf->SetDrawColor(0, 200, 230);
$pdf->RoundedRect(14, $pdf->GetY(), 182, 12, 2, 'DF');
$pdf->SetFont('Helvetica', 'I', 9);
$pdf->SetTextColor(30, 80, 120);
$pdf->SetXY(18, $pdf->GetY() + 2);
$pdf->MultiCell(174, 4.5,
  'Correct answers are highlighted in green. Each answer includes a short explanation to reinforce learning.',
  0, 'L');
$pdf->Ln(6);

// ── LEVELS ────────────────────────────────────────────────────────────────────
$qNum = 1;
foreach ($levels as $lvl) {
  [$r, $g, $b] = $lvl['color'];

  // Level header bar
  $pdf->SetFillColor($r, $g, $b);
  $pdf->Rect(14, $pdf->GetY(), 182, 10, 'F');
  $pdf->SetFont('Helvetica', 'B', 11);
  $pdf->SetTextColor(255, 255, 255);
  $pdf->SetXY(18, $pdf->GetY() + 1.5);
  $pdf->Cell(90, 7, $lvl['badge'] . '  —  ' . $lvl['name'], 0, 0, 'L');
  $pdf->SetFont('Helvetica', '', 9);
  $pdf->SetTextColor(220, 235, 255);
  $pdf->Cell(88, 7, 'Enemy: ' . $lvl['enemy'] . '   |   Topic: ' . $lvl['topic'], 0, 1, 'R');
  $pdf->Ln(3);

  foreach ($lvl['qs'] as $i => $q) {
    // Check page break
    if ($pdf->GetY() > 248) { $pdf->AddPage(); }

    // Question number + text
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->SetTextColor(20, 40, 70);
    $pdf->SetX(14);
    $pdf->Cell(8, 5, 'Q' . $qNum . '.', 0, 0, 'L');
    $pdf->SetFont('Helvetica', 'B', 9);
    $pdf->MultiCell(174, 5, $q['q'], 0, 'L');
    $pdf->Ln(1);

    // Options
    foreach ($q['opts'] as $letter => $text) {
      $isCorrect = ($letter === $q['ans']);
      $pdf->SetX(22);
      if ($isCorrect) {
        // Green highlight for correct answer
        $pdf->SetFillColor(220, 255, 225);
        $pdf->SetDrawColor($r, $g, $b);
        $xPos = $pdf->GetX();
        $yPos = $pdf->GetY();
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->SetTextColor(0, 120, 40);
        $pdf->Cell(5, 5.5, $letter . ')', 0, 0, 'L');
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->MultiCell(162, 5.5, $text . '  <- CORRECT', 0, 'L', true);
      } else {
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(80, 80, 100);
        $pdf->Cell(5, 5, $letter . ')', 0, 0, 'L');
        $pdf->MultiCell(162, 5, $text, 0, 'L');
      }
    }

    // Explanation box
    $pdf->Ln(1);
    $pdf->SetFillColor(248, 252, 255);
    $pdf->SetDrawColor(180, 210, 240);
    $pdf->SetX(22);
    $startY = $pdf->GetY();
    $pdf->SetFont('Helvetica', 'I', 8.5);
    $pdf->SetTextColor(50, 90, 140);
    $pdf->MultiCell(168, 4.8, chr(10) . '  Explanation: ' . $q['exp'], 1, 'L', true);
    $pdf->Ln(4);

    $qNum++;
  }
  $pdf->Ln(4);
}

// ── BACK COVER STRIP ─────────────────────────────────────────────────────────
if ($pdf->GetY() > 240) { $pdf->AddPage(); }
$pdf->SetFillColor(7, 11, 18);
$pdf->Rect(14, $pdf->GetY(), 182, 14, 'F');
$pdf->SetFont('Helvetica', 'B', 10);
$pdf->SetTextColor(0, 200, 230);
$pdf->SetXY(14, $pdf->GetY() + 2);
$pdf->Cell(182, 5, 'QUERY-VENTURE  |  ADBMS Module  |  Cavite State University Naic', 0, 2, 'C');
$pdf->SetFont('Helvetica', 'I', 8);
$pdf->SetTextColor(80, 140, 180);
$pdf->Cell(182, 4, '"Defeat the Hackers. Master the Database."', 0, 0, 'C');

// ── OUTPUT ────────────────────────────────────────────────────────────────────
$pdf->Output('D', 'QueryVenture_AnswerKey.pdf');
