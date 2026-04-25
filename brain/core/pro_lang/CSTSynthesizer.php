<?php
namespace Core\ProLang;

/**
 * Code Syntax Tree (CST) Synthesizer
 * Offline generator that pieces together abstract syntax nodes to form valid code
 * based on NLU parameters.
 */
class CSTSynthesizer {

    /**
     * Wrap generated code in a formatted UI block for the chat interface
     */
    public function formatCodeBlock(string $code, string $language): string {
        $html = "<div class='code-block-wrapper' style='margin: 15px 0; border-radius: 8px; overflow: hidden; background: #1e1e1e; border: 1px solid rgba(255,255,255,0.1);'>";
        $html .= "<div class='code-header' style='background: #2d2d2d; padding: 5px 15px; font-size: 12px; color: #aaa; display: flex; justify-content: space-between; border-bottom: 1px solid #444;'>";
        $html .= "<span>" . strtoupper($language) . "</span>";
        $html .= "<button onclick='navigator.clipboard.writeText(this.parentElement.nextElementSibling.innerText)' style='background:none;border:none;color:#aaa;cursor:pointer;font-size:12px;'>Copy</button>";
        $html .= "</div>";
        $html .= "<pre style='margin: 0; padding: 15px; overflow-x: auto;'><code class='language-{$language}' style='color: #d4d4d4; font-family: monospace; font-size: 14px;'>" . htmlspecialchars($code) . "</code></pre>";
        $html .= "</div>";
        return $html;
    }

    /**
     * Build generic function block
     */
    public function buildFunction(string $lang, string $name, array $params = [], string $body = ''): string {
        $name = empty($name) ? 'myFunction' : $name;
        $paramStr = implode(', ', $params);

        switch ($lang) {
            case 'php':
                $paramStr = implode(', ', array_map(fn($p) => '$' . $p, $params));
                return "function {$name}({$paramStr}) {\n    // TODO: Implement logic\n    {$body}\n    return true;\n}";
            case 'javascript':
            case 'js':
                return "function {$name}({$paramStr}) {\n    // TODO: Implement logic\n    {$body}\n    return true;\n}";
            case 'python':
                return "def {$name}({$paramStr}):\n    # TODO: Implement logic\n    {$body}\n    return True";
            default:
                return "function {$name}() {}";
        }
    }

    /**
     * Build generic class block
     */
    public function buildClass(string $lang, string $name, array $methods = []): string {
        $name = empty($name) ? 'MyClass' : ucfirst($name);

        switch ($lang) {
            case 'php':
                $code = "class {$name} {\n";
                $code .= "    public function __construct() {\n        // Constructor\n    }\n";
                foreach ($methods as $m) {
                    $code .= "\n    public " . $this->buildFunction('php', $m) . "\n";
                }
                $code .= "}";
                return $code;
            
            case 'javascript':
            case 'js':
                $code = "class {$name} {\n";
                $code .= "    constructor() {\n        // Constructor\n    }\n";
                foreach ($methods as $m) {
                    $code .= "\n    {$m}() {\n        // Logic\n    }\n";
                }
                $code .= "}";
                return $code;

            case 'python':
                $code = "class {$name}:\n";
                $code .= "    def __init__(self):\n        pass\n";
                foreach ($methods as $m) {
                    $code .= "\n    def {$m}(self):\n        pass\n";
                }
                return $code;

            default:
                return "class {$name} {}";
        }
    }

    /**
     * Build generic database connection block
     */
    public function buildDBConnection(string $lang): string {
        switch ($lang) {
            case 'php':
                return <<<PHP
\$host = 'localhost';
\$db   = 'hritik_db';
\$user = 'root';
\$pass = '';
\$charset = 'utf8mb4';

\$dsn = "mysql:host=\$host;dbname=\$db;charset=\$charset";
\$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    \$pdo = new PDO(\$dsn, \$user, \$pass, \$options);
    echo "Database Connected Successfully";
} catch (\PDOException \$e) {
    throw new \PDOException(\$e->getMessage(), (int)\$e->getCode());
}
PHP;
            case 'javascript':
            case 'js':
            case 'node':
                return <<<JS
const mysql = require('mysql2/promise');

async function connectDB() {
    try {
        const connection = await mysql.createConnection({
            host: 'localhost',
            user: 'root',
            database: 'hritik_db'
        });
        console.log('Database Connected Successfully');
        return connection;
    } catch (error) {
        console.error('Database connection failed:', error);
    }
}
JS;
            case 'python':
                return <<<PYTHON
import mysql.connector
from mysql.connector import Error

try:
    connection = mysql.connector.connect(
        host='localhost',
        database='hritik_db',
        user='root',
        password=''
    )
    if connection.is_connected():
        print("Database Connected Successfully")
except Error as e:
    print(f"Error while connecting to MySQL: {e}")
PYTHON;
            default:
                return "// DB Connection for {$lang} not supported offline yet.";
        }
    }

    /**
     * Build CRUD operations for a specific table
     */
    public function buildCRUD(string $lang, string $table): string {
        $table = empty($table) ? 'items' : $table;
        $class = ucfirst($table);

        switch ($lang) {
            case 'php':
                return <<<PHP
class {$class}Controller {
    private \$pdo;

    public function __construct(\$pdo) {
        \$this->pdo = \$pdo;
    }

    public function create(\$data) {
        \$sql = "INSERT INTO {$table} (title, description) VALUES (?, ?)";
        return \$this->pdo->prepare(\$sql)->execute([\$data['title'], \$data['description']]);
    }

    public function read(\$id = null) {
        if (\$id) {
            \$stmt = \$this->pdo->prepare("SELECT * FROM {$table} WHERE id = ?");
            \$stmt->execute([\$id]);
            return \$stmt->fetch();
        }
        return \$this->pdo->query("SELECT * FROM {$table}")->fetchAll();
    }

    public function update(\$id, \$data) {
        \$sql = "UPDATE {$table} SET title = ?, description = ? WHERE id = ?";
        return \$this->pdo->prepare(\$sql)->execute([\$data['title'], \$data['description'], \$id]);
    }

    public function delete(\$id) {
        return \$this->pdo->prepare("DELETE FROM {$table} WHERE id = ?")->execute([\$id]);
    }
}
PHP;
            case 'python':
                return <<<PYTHON
class {$class}Manager:
    def __init__(self, db_connection):
        self.db = db_connection

    def create(self, title, description):
        cursor = self.db.cursor()
        cursor.execute("INSERT INTO {$table} (title, description) VALUES (%s, %s)", (title, description))
        self.db.commit()

    def read(self, id=None):
        cursor = self.db.cursor(dictionary=True)
        if id:
            cursor.execute("SELECT * FROM {$table} WHERE id = %s", (id,))
            return cursor.fetchone()
        cursor.execute("SELECT * FROM {$table}")
        return cursor.fetchall()
PYTHON;
            default:
                return "// CRUD for {$lang} is in development.";
        }
    }

    /**
     * Build Authentication boilerplate
     */
    public function buildAuth(string $lang): string {
        switch ($lang) {
            case 'php':
                return <<<PHP
session_start();

function login(\$username, \$password, \$pdo) {
    \$stmt = \$pdo->prepare("SELECT * FROM users WHERE username = ?");
    \$stmt->execute([\$username]);
    \$user = \$stmt->fetch();

    if (\$user && password_verify(\$password, \$user['password'])) {
        \$_SESSION['user_id'] = \$user['id'];
        return true;
    }
    return false;
}

function isLoggedIn() {
    return isset(\$_SESSION['user_id']);
}
PHP;
            default:
                return "// Auth boilerplate for {$lang} coming soon.";
        }
    }
}
