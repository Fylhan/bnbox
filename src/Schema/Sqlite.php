<?php
namespace Schema;

use Core\Security;
use PDO;

const VERSION = 1;

function version_1($pdo)
{
    $pdo->exec("
        CREATE TABLE settings (
            option TEXT PRIMARY KEY,
            value TEXT DEFAULT ''
        )
    ");
    
    $pdo->exec("
        CREATE TABLE user (
            id INTEGER PRIMARY KEY,
            username TEXT NOT NULL,
            password TEXT NOT NULL,
            role INTEGER DEFAULT 0,
            name TEXT,
            email TEXT
        )
    ");
    
    $pdo->exec("
        CREATE TABLE section (
            id INTEGER PRIMARY KEY,
            url TEXT UNIQUE NOT NULL,
            title TEXT NOT NULL,
            description TEXT,
            is_active INTEGER DEFAULT 1
        )
    ");
    
    $pdo->exec("
        CREATE TABLE category (
            id INTEGER PRIMARY KEY,
            url TEXT UNIQUE NOT NULL,
            title TEXT NOT NULL,
            description TEXT,
            is_active INTEGER DEFAULT 1,
            section_id INTEGER,
            FOREIGN KEY(section_id) REFERENCES section(id) ON DELETE CASCADE
        )
    ");
    
    $pdo->exec("
        CREATE TABLE article (
            id INTEGER PRIMARY KEY,
            url TEXT UNIQUE NOT NULL,
            title TEXT NOT NULL,
            content TEXT NOT NULL,
            date_creation INTEGER,
            date_publication INTEGER,
            date_update INTEGER,
            state INTEGER DEFAULT 0,
            category_id INTEGER,
            user_id INTEGER,
            FOREIGN KEY(category_id) REFERENCES category(id) ON DELETE CASCADE,
            FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE
        )
    ");
    
    $pdo->exec('CREATE TABLE remember_me (
            id INTEGER PRIMARY KEY,
            user_id INTEGER,
            ip TEXT,
            user_agent TEXT,
            token TEXT,
            sequence TEXT,
            expiration INTEGER,
            date_creation INTEGER,
            FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE
        )');
    
    $pdo->exec('CREATE TABLE last_logins (
            id INTEGER PRIMARY KEY,
            auth_type TEXT,
            user_id INTEGER,
            ip TEXT,
            user_agent TEXT,
            date_creation INTEGER,
            FOREIGN KEY(user_id) REFERENCES user(id) ON DELETE CASCADE
        )');
    
    $pdo->exec('CREATE INDEX last_logins_user_idx ON last_logins(user_id)');
    
    $rq = $pdo->prepare('INSERT INTO user (username, password, role) VALUES (?, ?, ?)');
    $rq->execute(array(
        'admin',
        password_hash('admin', PASSWORD_BCRYPT),
        '1'
    ));
    
    $rq = $pdo->prepare('INSERT INTO section (url, title, description) VALUES (?, ?, ?)');
    $rq->execute(array(
        'etude',
        'Etudes',
        'Ancien cahier de l\'élève'
    ));
    $rq->execute(array(
        'developpement',
        'Développement',
        'Ancien atelier webmaster'
    ));
    $rq->execute(array(
        'animation',
        'Animation',
        'Animation'
    ));
    $rq->execute(array(
        'jeux-videos',
        'Jeux vidéos',
        'Jeux vidéos'
    ));
    $rq->execute(array(
        'news',
        'News',
        'News'
    ));
    $rq->execute(array(
        'page',
        'Page',
        'Page'
    ));
    
    $rq = $pdo->prepare('INSERT INTO category (url, title, section_id) VALUES (?, ?, ?)');
    $rq->execute(array(
        'francais',
        'Français',
        1
    ));
    $rq->execute(array(
        'histoire',
        'Histoire',
        1
    ));
    $rq->execute(array(
        'mathematiques',
        'Mathématiques',
        1
    ));
    $rq->execute(array(
        'physique-chimie',
        'Physique-Chimie',
        1
    ));
    $rq->execute(array(
        'professeur-des-ecoles',
        'Professeur des écoles',
        1
    ));
    $rq->execute(array(
        'grandes-ecoles',
        'Grandes écoles',
        1
    ));
    $rq->execute(array(
        'web',
        'Web',
        2
    ));
    $rq->execute(array(
        'petits-jeux',
        'Petits jeux',
        3
    ));
    $rq->execute(array(
        'grands-jeux',
        'Grands jeux',
        3
    ));
    $rq->execute(array(
        'chansons',
        'Chansons',
        3
    ));
    $rq->execute(array(
        'enigme',
        'Enigme',
        3
    ));
    $rq->execute(array(
        'administration',
        'Administration',
        3
    ));
    $rq->execute(array(
        'codes',
        'Codes',
        4
    ));
    $rq->execute(array(
        'solutions',
        'Solutions',
        4
    ));
    $rq->execute(array(
        'tests',
        'Tests',
        4
    ));
    $rq->execute(array(
        'news',
        'News',
        5
    ));
    $rq->execute(array(
        'aide',
        'Aide',
        5
    ));
    
    $rq = $pdo->prepare('INSERT INTO settings VALUES (?, ?)');
    $rq->execute(array(
        'application_date_format',
        'm/d/Y'
    ));
    $rq->execute(array(
        'board_highlight_period',
        defined('RECENT_TASK_PERIOD') ? RECENT_TASK_PERIOD : 48 * 60 * 60
    ));
    $rq->execute(array(
        'board_public_refresh_interval',
        defined('BOARD_PUBLIC_CHECK_INTERVAL') ? BOARD_PUBLIC_CHECK_INTERVAL : 60
    ));
    $rq->execute(array(
        'board_private_refresh_interval',
        defined('BOARD_CHECK_INTERVAL') ? BOARD_CHECK_INTERVAL : 10
    ));
    $rq->execute(array(
        'application_language',
        'fr'
    ));
    $rq->execute(array(
        'application_timezone',
        'UTC+1'
    ));
    $rq->execute(array(
        'application_url',
        defined('KANBOARD_URL') ? KANBOARD_URL : ''
    ));
}
