<?php

namespace Schema;

use PDO;
use Core\Security;
use Model\Link;

const VERSION = 54;

function version_54($pdo)
{
    $pdo->exec('ALTER TABLE task_has_links ADD COLUMN opposite_task_link_id INTEGER DEFAULT 0');

    $urq = $pdo->prepare('UPDATE task_has_links SET opposite_task_link_id=? WHERE id=?');

    $rq = $pdo->prepare('SELECT tl1.*
        FROM task_has_links tl1
        ORDER BY tl1.id
        ');
    $rq->execute();
    $task_links = $rq->fetchAll(PDO::FETCH_ASSOC);
    for ($i=0; $i<count($task_links); ($i=$i+2)) {
        $urq->execute(array($task_links[$i+1]['id'], $task_links[$i]['id']));
        $urq->execute(array($task_links[$i]['id'], $task_links[$i+1]['id']));
    }
}

function version_53($pdo)
{
    $pdo->exec("ALTER TABLE subtask_time_tracking ADD COLUMN time_spent FLOAT DEFAULT 0");
}

function version_52($pdo)
{
    $pdo->exec('CREATE TABLE budget_lines (
        `id` INT NOT NULL AUTO_INCREMENT,
        `project_id` INT NOT NULL,
        `amount` FLOAT NOT NULL,
        `date` VARCHAR(10) NOT NULL,
        `comment` TEXT,
        FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
        PRIMARY KEY(id)
    ) ENGINE=InnoDB CHARSET=utf8');
}

function version_51($pdo)
{
    $pdo->exec('CREATE TABLE timetable_day (
        id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        start VARCHAR(5) NOT NULL,
        end VARCHAR(5) NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        PRIMARY KEY(id)
    ) ENGINE=InnoDB CHARSET=utf8');

    $pdo->exec('CREATE TABLE timetable_week (
        id INT NOT NULL AUTO_INCREMENT,
        user_id INTEGER NOT NULL,
        day INT NOT NULL,
        start VARCHAR(5) NOT NULL,
        end VARCHAR(5) NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        PRIMARY KEY(id)
    ) ENGINE=InnoDB CHARSET=utf8');

    $pdo->exec('CREATE TABLE timetable_off (
        id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        date VARCHAR(10) NOT NULL,
        all_day TINYINT(1) DEFAULT 0,
        start VARCHAR(5) DEFAULT 0,
        end VARCHAR(5) DEFAULT 0,
        comment TEXT,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        PRIMARY KEY(id)
    ) ENGINE=InnoDB CHARSET=utf8');

    $pdo->exec('CREATE TABLE timetable_extra (
        id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        date VARCHAR(10) NOT NULL,
        all_day TINYINT(1) DEFAULT 0,
        start VARCHAR(5) DEFAULT 0,
        end VARCHAR(5) DEFAULT 0,
        comment TEXT,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        PRIMARY KEY(id)
    ) ENGINE=InnoDB CHARSET=utf8');
}

function version_50($pdo)
{
    $pdo->exec("CREATE TABLE hourly_rates (
        id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        rate FLOAT DEFAULT 0,
        date_effective INTEGER NOT NULL,
        currency TEXT NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
        PRIMARY KEY(id)
    ) ENGINE=InnoDB CHARSET=utf8");
}

function version_49($pdo)
{
    $pdo->exec('ALTER TABLE subtasks ADD COLUMN position INTEGER DEFAULT 1');

    $task_id = 0;
    $urq = $pdo->prepare('UPDATE subtasks SET position=? WHERE id=?');

    $rq = $pdo->prepare('SELECT * FROM subtasks ORDER BY task_id, id ASC');
    $rq->execute();

    foreach ($rq->fetchAll(PDO::FETCH_ASSOC) as $subtask) {

        if ($task_id != $subtask['task_id']) {
            $position = 1;
            $task_id = $subtask['task_id'];
        }

        $urq->execute(array($position, $subtask['id']));
        $position++;
    }
}

function version_48($pdo)
{
    $pdo->exec('RENAME TABLE task_has_files TO files');
    $pdo->exec('RENAME TABLE task_has_subtasks TO subtasks');
}

function version_47($pdo)
{
    $pdo->exec('ALTER TABLE projects ADD COLUMN description TEXT');
}

function version_46($pdo)
{
    $pdo->exec("CREATE TABLE links (
        id INT NOT NULL AUTO_INCREMENT,
        label VARCHAR(255) NOT NULL,
        opposite_id INT DEFAULT 0,
        PRIMARY KEY(id),
        UNIQUE(label)
    ) ENGINE=InnoDB CHARSET=utf8");

    $pdo->exec("CREATE TABLE task_has_links (
        id INT NOT NULL AUTO_INCREMENT,
        link_id INT NOT NULL,
        task_id INT NOT NULL,
        opposite_task_id INT NOT NULL,
        FOREIGN KEY(link_id) REFERENCES links(id) ON DELETE CASCADE,
        FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE,
        FOREIGN KEY(opposite_task_id) REFERENCES tasks(id) ON DELETE CASCADE,
        PRIMARY KEY(id)
    ) ENGINE=InnoDB CHARSET=utf8");

    $pdo->exec("CREATE INDEX task_has_links_task_index ON task_has_links(task_id)");
    $pdo->exec("CREATE UNIQUE INDEX task_has_links_unique ON task_has_links(link_id, task_id, opposite_task_id)");

    $rq = $pdo->prepare('INSERT INTO links (label, opposite_id) VALUES (?, ?)');
    $rq->execute(array('relates to', 0));
    $rq->execute(array('blocks', 3));
    $rq->execute(array('is blocked by', 2));
    $rq->execute(array('duplicates', 5));
    $rq->execute(array('is duplicated by', 4));
    $rq->execute(array('is a child of', 7));
    $rq->execute(array('is a parent of', 6));
    $rq->execute(array('targets milestone', 9));
    $rq->execute(array('is a milestone of', 8));
    $rq->execute(array('fixes', 11));
    $rq->execute(array('is fixed by', 10));
}

function version_45($pdo)
{
    $pdo->exec('ALTER TABLE tasks ADD COLUMN date_moved INT DEFAULT 0');

    /* Update tasks.date_moved from project_activities table if tasks.date_moved = null or 0.
     * We take max project_activities.date_creation where event_name in task.create','task.move.column
     * since creation date is always less than task moves
     */
    $pdo->exec("UPDATE tasks
                SET date_moved = (
                    SELECT md
                    FROM (
                        SELECT task_id, max(date_creation) md
                        FROM project_activities
                        WHERE event_name IN ('task.create', 'task.move.column')
                        GROUP BY task_id
                    ) src
                    WHERE id = src.task_id
                )
                WHERE (date_moved IS NULL OR date_moved = 0) AND id IN (
                    SELECT task_id
                    FROM (
                        SELECT task_id, max(date_creation) md
                        FROM project_activities
                        WHERE event_name IN ('task.create', 'task.move.column')
                        GROUP BY task_id
                    ) src
                )");

    // If there is no activities for some tasks use the date_creation
    $pdo->exec("UPDATE tasks SET date_moved = date_creation WHERE date_moved IS NULL OR date_moved = 0");
}

function version_44($pdo)
{
    $pdo->exec('ALTER TABLE users ADD COLUMN disable_login_form TINYINT(1) DEFAULT 0');
}

function version_43($pdo)
{
    $rq = $pdo->prepare('INSERT INTO settings VALUES (?, ?)');
    $rq->execute(array('subtask_restriction', '0'));
    $rq->execute(array('subtask_time_tracking', '0'));

    $pdo->exec("
        CREATE TABLE subtask_time_tracking (
            id INT NOT NULL AUTO_INCREMENT,
            user_id INT NOT NULL,
            subtask_id INT NOT NULL,
            start INT DEFAULT 0,
            end INT DEFAULT 0,
            PRIMARY KEY(id),
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(subtask_id) REFERENCES task_has_subtasks(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8
    ");
}

function version_42($pdo)
{
    $pdo->exec('ALTER TABLE columns ADD COLUMN description TEXT');
}

function version_41($pdo)
{
    $pdo->exec('ALTER TABLE users ADD COLUMN timezone VARCHAR(50)');
    $pdo->exec('ALTER TABLE users ADD COLUMN language CHAR(5)');
}

function version_40($pdo)
{
    // Avoid some full table scans
    $pdo->exec('CREATE INDEX users_admin_idx ON users(is_admin)');
    $pdo->exec('CREATE INDEX columns_project_idx ON columns(project_id)');
    $pdo->exec('CREATE INDEX tasks_project_idx ON tasks(project_id)');
    $pdo->exec('CREATE INDEX swimlanes_project_idx ON swimlanes(project_id)');
    $pdo->exec('CREATE INDEX categories_project_idx ON project_has_categories(project_id)');
    $pdo->exec('CREATE INDEX subtasks_task_idx ON task_has_subtasks(task_id)');
    $pdo->exec('CREATE INDEX files_task_idx ON task_has_files(task_id)');
    $pdo->exec('CREATE INDEX comments_task_idx ON comments(task_id)');

    // Set the ownership for all private projects
    $rq = $pdo->prepare('SELECT id FROM projects WHERE is_private=1');
    $rq->execute();
    $project_ids = $rq->fetchAll(PDO::FETCH_COLUMN, 0);

    $rq = $pdo->prepare('UPDATE project_has_users SET is_owner=1 WHERE project_id=?');

    foreach ($project_ids as $project_id) {
        $rq->execute(array($project_id));
    }
}

function version_39($pdo)
{
    $rq = $pdo->prepare('INSERT INTO settings VALUES (?, ?)');
    $rq->execute(array('project_categories', ''));
}

function version_38($pdo)
{
    $pdo->exec("
        CREATE TABLE swimlanes (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(200) NOT NULL,
            position INT DEFAULT 1,
            is_active INT DEFAULT 1,
            project_id INT,
            PRIMARY KEY(id),
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            UNIQUE (name, project_id)
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec('ALTER TABLE tasks ADD COLUMN swimlane_id INT DEFAULT 0');
    $pdo->exec("ALTER TABLE projects ADD COLUMN default_swimlane VARCHAR(200) DEFAULT 'Default swimlane'");
    $pdo->exec("ALTER TABLE projects ADD COLUMN show_default_swimlane INT DEFAULT 1");
}

function version_37($pdo)
{
    $pdo->exec("ALTER TABLE project_has_users ADD COLUMN is_owner TINYINT(1) DEFAULT '0'");
}

function version_36($pdo)
{
    $pdo->exec('ALTER TABLE tasks MODIFY title VARCHAR(255) NOT NULL');
}

function version_35($pdo)
{
    $pdo->exec("
        CREATE TABLE project_daily_summaries (
            id INT NOT NULL AUTO_INCREMENT,
            day CHAR(10) NOT NULL,
            project_id INT NOT NULL,
            column_id INT NOT NULL,
            total INT NOT NULL DEFAULT 0,
            PRIMARY KEY(id),
            FOREIGN KEY(column_id) REFERENCES columns(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec('CREATE UNIQUE INDEX project_daily_column_stats_idx ON project_daily_summaries(day, project_id, column_id)');
}

function version_34($pdo)
{
    $pdo->exec("ALTER TABLE projects ADD COLUMN is_everybody_allowed TINYINT(1) DEFAULT '0'");
}

function version_33($pdo)
{
    $pdo->exec("
        CREATE TABLE project_activities (
            id INT NOT NULL AUTO_INCREMENT,
            date_creation INT NOT NULL,
            event_name VARCHAR(50) NOT NULL,
            creator_id INT,
            project_id INT,
            task_id INT,
            data TEXT,
            PRIMARY KEY(id),
            FOREIGN KEY(creator_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec('DROP TABLE task_has_events');
    $pdo->exec('DROP TABLE comment_has_events');
    $pdo->exec('DROP TABLE subtask_has_events');
}

function version_32($pdo)
{
    $pdo->exec("ALTER TABLE tasks ADD COLUMN date_started INTEGER");
    $pdo->exec("ALTER TABLE tasks ADD COLUMN time_spent FLOAT DEFAULT 0");
    $pdo->exec("ALTER TABLE tasks ADD COLUMN time_estimated FLOAT DEFAULT 0");

    $pdo->exec("ALTER TABLE task_has_subtasks MODIFY time_estimated FLOAT");
    $pdo->exec("ALTER TABLE task_has_subtasks MODIFY time_spent FLOAT");
}

function version_31($pdo)
{
    $pdo->exec("ALTER TABLE projects ADD COLUMN is_private TINYINT(1) DEFAULT '0'");
}

function version_30($pdo)
{
    $rq = $pdo->prepare('INSERT INTO settings VALUES (?, ?)');
    $rq->execute(array('application_date_format', 'm/d/Y'));
}

function version_29($pdo)
{
    $pdo->exec("
        CREATE TABLE settings (
            `option` VARCHAR(100) PRIMARY KEY,
            `value` VARCHAR(255) DEFAULT ''
        )
    ");

    // Migrate old config parameters
    $rq = $pdo->prepare('SELECT * FROM config');
    $rq->execute();
    $parameters = $rq->fetch(PDO::FETCH_ASSOC);

    $rq = $pdo->prepare('INSERT INTO settings VALUES (?, ?)');
    $rq->execute(array('board_highlight_period', defined('RECENT_TASK_PERIOD') ? RECENT_TASK_PERIOD : 48*60*60));
    $rq->execute(array('board_public_refresh_interval', defined('BOARD_PUBLIC_CHECK_INTERVAL') ? BOARD_PUBLIC_CHECK_INTERVAL : 60));
    $rq->execute(array('board_private_refresh_interval', defined('BOARD_CHECK_INTERVAL') ? BOARD_CHECK_INTERVAL : 10));
    $rq->execute(array('board_columns', $parameters['default_columns']));
    $rq->execute(array('webhook_url_task_creation', $parameters['webhooks_url_task_creation']));
    $rq->execute(array('webhook_url_task_modification', $parameters['webhooks_url_task_modification']));
    $rq->execute(array('webhook_token', $parameters['webhooks_token']));
    $rq->execute(array('api_token', $parameters['api_token']));
    $rq->execute(array('application_language', $parameters['language']));
    $rq->execute(array('application_timezone', $parameters['timezone']));
    $rq->execute(array('application_url', defined('KANBOARD_URL') ? KANBOARD_URL : ''));

    $pdo->exec('DROP TABLE config');
}

function version_28($pdo)
{
    $pdo->exec("ALTER TABLE tasks ADD COLUMN reference VARCHAR(50) DEFAULT ''");
    $pdo->exec("ALTER TABLE comments ADD COLUMN reference VARCHAR(50) DEFAULT ''");

    $pdo->exec('CREATE INDEX tasks_reference_idx ON tasks(reference)');
    $pdo->exec('CREATE INDEX comments_reference_idx ON comments(reference)');
}

function version_27($pdo)
{
    $pdo->exec('CREATE UNIQUE INDEX users_username_idx ON users(username)');
}

function version_26($pdo)
{
    $pdo->exec("ALTER TABLE config ADD COLUMN default_columns VARCHAR(255) DEFAULT ''");
}

function version_25($pdo)
{
    $pdo->exec("
        CREATE TABLE task_has_events (
            id INT NOT NULL AUTO_INCREMENT,
            date_creation INT NOT NULL,
            event_name TEXT NOT NULL,
            creator_id INT,
            project_id INT,
            task_id INT,
            data TEXT,
            FOREIGN KEY(creator_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        CREATE TABLE subtask_has_events (
            id INT NOT NULL AUTO_INCREMENT,
            date_creation INT NOT NULL,
            event_name TEXT NOT NULL,
            creator_id INT,
            project_id INT,
            subtask_id INT,
            task_id INT,
            data TEXT,
            FOREIGN KEY(creator_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(subtask_id) REFERENCES task_has_subtasks(id) ON DELETE CASCADE,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        CREATE TABLE comment_has_events (
            id INT NOT NULL AUTO_INCREMENT,
            date_creation INT NOT NULL,
            event_name TEXT NOT NULL,
            creator_id INT,
            project_id INT,
            comment_id INT,
            task_id INT,
            data TEXT,
            FOREIGN KEY(creator_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(comment_id) REFERENCES comments(id) ON DELETE CASCADE,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB CHARSET=utf8
    ");
}

function version_24($pdo)
{
    $pdo->exec("ALTER TABLE projects ADD COLUMN is_public TINYINT(1) DEFAULT '0'");
}

function version_23($pdo)
{
    $pdo->exec("ALTER TABLE users ADD COLUMN notifications_enabled TINYINT(1) DEFAULT '0'");

    $pdo->exec("
        CREATE TABLE user_has_notifications (
            user_id INT,
            project_id INT,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            UNIQUE(project_id, user_id)
        );
    ");
}

function version_22($pdo)
{
    $pdo->exec("ALTER TABLE config ADD COLUMN webhooks_url_task_modification VARCHAR(255)");
    $pdo->exec("ALTER TABLE config ADD COLUMN webhooks_url_task_creation VARCHAR(255)");
}

function version_21($pdo)
{
    $pdo->exec("ALTER TABLE tasks ADD COLUMN creator_id INTEGER DEFAULT '0'");
    $pdo->exec("ALTER TABLE tasks ADD COLUMN date_modification INTEGER DEFAULT '0'");
}

function version_20($pdo)
{
    $pdo->exec("ALTER TABLE users ADD COLUMN github_id VARCHAR(30)");
}

function version_19($pdo)
{
    $pdo->exec("ALTER TABLE config ADD COLUMN api_token VARCHAR(255) DEFAULT ''");
    $pdo->exec("UPDATE config SET api_token='".Security::generateToken()."'");
}

function version_18($pdo)
{
    $pdo->exec("
        CREATE TABLE task_has_subtasks (
            id INT NOT NULL AUTO_INCREMENT,
            title VARCHAR(255),
            status INT DEFAULT 0,
            time_estimated INT DEFAULT 0,
            time_spent INT DEFAULT 0,
            task_id INT NOT NULL,
            user_id INT,
            PRIMARY KEY (id),
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8"
    );
}

function version_17($pdo)
{
    $pdo->exec("
        CREATE TABLE task_has_files (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(50),
            path VARCHAR(255),
            is_image TINYINT(1) DEFAULT 0,
            task_id INT,
            PRIMARY KEY (id),
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8"
    );
}

function version_16($pdo)
{
    $pdo->exec("
        CREATE TABLE project_has_categories (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(255),
            project_id INT,
            PRIMARY KEY (id),
            UNIQUE KEY `idx_project_category` (project_id, name),
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8"
    );

    $pdo->exec("ALTER TABLE tasks ADD COLUMN category_id INT DEFAULT 0");
}

function version_15($pdo)
{
    $pdo->exec("ALTER TABLE projects ADD COLUMN last_modified INT DEFAULT 0");
}

function version_14($pdo)
{
    $pdo->exec("ALTER TABLE users ADD COLUMN name VARCHAR(255)");
    $pdo->exec("ALTER TABLE users ADD COLUMN email VARCHAR(255)");
    $pdo->exec("ALTER TABLE users ADD COLUMN google_id VARCHAR(30)");
}

function version_13($pdo)
{
    $pdo->exec("ALTER TABLE users ADD COLUMN is_ldap_user TINYINT(1) DEFAULT 0");
}

function version_12($pdo)
{
    $pdo->exec("
        CREATE TABLE remember_me (
            id INT NOT NULL AUTO_INCREMENT,
            user_id INT,
            ip VARCHAR(40),
            user_agent VARCHAR(255),
            token VARCHAR(255),
            sequence VARCHAR(255),
            expiration INT,
            date_creation INT,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB CHARSET=utf8"
    );

    $pdo->exec("
        CREATE TABLE last_logins (
            id INT NOT NULL AUTO_INCREMENT,
            auth_type VARCHAR(25),
            user_id INT,
            ip VARCHAR(40),
            user_agent VARCHAR(255),
            date_creation INT,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
            PRIMARY KEY (id),
            INDEX (user_id)
        ) ENGINE=InnoDB CHARSET=utf8"
    );
}

function version_1($pdo)
{
    $pdo->exec("
        CREATE TABLE config (
            language CHAR(5) DEFAULT 'en_US',
            webhooks_token VARCHAR(255) DEFAULT '',
            timezone VARCHAR(50) DEFAULT 'UTC'
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        CREATE TABLE users (
            id INT NOT NULL AUTO_INCREMENT,
            username VARCHAR(50),
            password VARCHAR(255),
            is_admin TINYINT DEFAULT 0,
            default_project_id INT DEFAULT 0,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        CREATE TABLE projects (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(50) UNIQUE,
            is_active TINYINT DEFAULT 1,
            token VARCHAR(255),
            PRIMARY KEY (id)
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        CREATE TABLE project_has_users (
            id INT NOT NULL AUTO_INCREMENT,
            project_id INT,
            user_id INT,
            PRIMARY KEY (id),
            UNIQUE KEY `idx_project_user` (project_id, user_id),
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        CREATE TABLE columns (
            id INT NOT NULL AUTO_INCREMENT,
            title VARCHAR(255),
            position INT NOT NULL,
            project_id INT NOT NULL,
            task_limit INT DEFAULT '0',
            UNIQUE KEY `idx_title_project` (title, project_id),
            PRIMARY KEY (id),
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        CREATE TABLE tasks (
            id INT NOT NULL AUTO_INCREMENT,
            title VARCHAR(255),
            description TEXT,
            date_creation INT,
            date_completed INT,
            date_due INT,
            color_id VARCHAR(50),
            project_id INT,
            column_id INT,
            owner_id INT DEFAULT '0',
            position INT,
            score INT,
            is_active TINYINT DEFAULT 1,
            PRIMARY KEY (id),
            INDEX `idx_task_active` (is_active),
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(column_id) REFERENCES columns(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        CREATE TABLE comments (
            id INT NOT NULL AUTO_INCREMENT,
            task_id INT,
            user_id INT,
            `date` INT,
            comment TEXT,
            PRIMARY KEY (id),
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        CREATE TABLE actions (
            id INT NOT NULL AUTO_INCREMENT,
            project_id INT,
            event_name VARCHAR(50),
            action_name VARCHAR(50),
            PRIMARY KEY (id),
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        CREATE TABLE action_has_params (
            id INT NOT NULL AUTO_INCREMENT,
            action_id INT,
            name VARCHAR(50),
            value VARCHAR(50),
            PRIMARY KEY (id),
            FOREIGN KEY(action_id) REFERENCES actions(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8
    ");

    $pdo->exec("
        INSERT INTO users
        (username, password, is_admin)
        VALUES ('admin', '".\password_hash('admin', PASSWORD_BCRYPT)."', '1')
    ");

    $pdo->exec("
        INSERT INTO config
        (webhooks_token)
        VALUES ('".Security::generateToken()."')
    ");
}
