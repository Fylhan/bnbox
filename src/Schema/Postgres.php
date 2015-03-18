<?php

namespace Schema;

use PDO;
use Core\Security;
use Model\Link;

const VERSION = 35;

function version_35($pdo)
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

function version_34($pdo)
{
    $pdo->exec("ALTER TABLE subtask_time_tracking ADD COLUMN time_spent REAL DEFAULT 0");
}

function version_33($pdo)
{
    $pdo->exec('CREATE TABLE budget_lines (
        "id" SERIAL PRIMARY KEY,
        "project_id" INTEGER NOT NULL,
        "amount" REAL NOT NULL,
        "date" VARCHAR(10) NOT NULL,
        "comment" TEXT,
        FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
    )');
}

function version_32($pdo)
{
    $pdo->exec('CREATE TABLE timetable_day (
        "id" SERIAL PRIMARY KEY,
        "user_id" INTEGER NOT NULL,
        "start" VARCHAR(5) NOT NULL,
        "end" VARCHAR(5) NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE timetable_week (
        "id" SERIAL PRIMARY KEY,
        "user_id" INTEGER NOT NULL,
        "day" INTEGER NOT NULL,
        "start" VARCHAR(5) NOT NULL,
        "end" VARCHAR(5) NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE timetable_off (
        "id" SERIAL PRIMARY KEY,
        "user_id" INTEGER NOT NULL,
        "date" VARCHAR(10) NOT NULL,
        "all_day" BOOLEAN DEFAULT \'0\',
        "start" VARCHAR(5) DEFAULT 0,
        "end" VARCHAR(5) DEFAULT 0,
        "comment" TEXT,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    )');

    $pdo->exec('CREATE TABLE timetable_extra (
        "id" SERIAL PRIMARY KEY,
        "user_id" INTEGER NOT NULL,
        "date" VARCHAR(10) NOT NULL,
        "all_day" BOOLEAN DEFAULT \'0\',
        "start" VARCHAR(5) DEFAULT 0,
        "end" VARCHAR(5) DEFAULT 0,
        "comment" TEXT,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    )');
}

function version_31($pdo)
{
    $pdo->exec("CREATE TABLE hourly_rates (
        id SERIAL PRIMARY KEY,
        user_id INTEGER NOT NULL,
        rate REAL DEFAULT 0,
        date_effective INTEGER NOT NULL,
        currency TEXT NOT NULL,
        FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
}

function version_30($pdo)
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

function version_29($pdo)
{
    $pdo->exec('ALTER TABLE task_has_files RENAME TO files');
    $pdo->exec('ALTER TABLE task_has_subtasks RENAME TO subtasks');
}

function version_28($pdo)
{
    $pdo->exec('ALTER TABLE projects ADD COLUMN description TEXT');
}

function version_27($pdo)
{
    $pdo->exec('CREATE TABLE links (
        "id" SERIAL PRIMARY KEY,
        "label" VARCHAR(255) NOT NULL,
        "opposite_id" INTEGER DEFAULT 0,
        UNIQUE("label")
    )');

    $pdo->exec("CREATE TABLE task_has_links (
        id SERIAL PRIMARY KEY,
        link_id INTEGER NOT NULL,
        task_id INTEGER NOT NULL,
        opposite_task_id INTEGER NOT NULL,
        FOREIGN KEY(link_id) REFERENCES links(id) ON DELETE CASCADE,
        FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE,
        FOREIGN KEY(opposite_task_id) REFERENCES tasks(id) ON DELETE CASCADE
    )");

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

function version_26($pdo)
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

function version_25($pdo)
{
    $pdo->exec("ALTER TABLE users ADD COLUMN disable_login_form BOOLEAN DEFAULT '0'");
}

function version_24($pdo)
{
    $rq = $pdo->prepare('INSERT INTO settings VALUES (?, ?)');
    $rq->execute(array('subtask_restriction', '0'));
    $rq->execute(array('subtask_time_tracking', '0'));

    $pdo->exec('
        CREATE TABLE subtask_time_tracking (
            id SERIAL PRIMARY KEY,
            "user_id" INTEGER NOT NULL,
            "subtask_id" INTEGER NOT NULL,
            "start" INTEGER DEFAULT 0,
            "end" INTEGER DEFAULT 0,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(subtask_id) REFERENCES task_has_subtasks(id) ON DELETE CASCADE
        )
    ');
}

function version_23($pdo)
{
    $pdo->exec('ALTER TABLE columns ADD COLUMN description TEXT');
}

function version_22($pdo)
{
    $pdo->exec('ALTER TABLE users ADD COLUMN timezone VARCHAR(50)');
    $pdo->exec('ALTER TABLE users ADD COLUMN language CHAR(5)');
}

function version_21($pdo)
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
    $rq = $pdo->prepare("SELECT id FROM projects WHERE is_private='1'");
    $rq->execute();
    $project_ids = $rq->fetchAll(PDO::FETCH_COLUMN, 0);

    $rq = $pdo->prepare("UPDATE project_has_users SET is_owner='1' WHERE project_id=?");

    foreach ($project_ids as $project_id) {
        $rq->execute(array($project_id));
    }
}

function version_20($pdo)
{
    $rq = $pdo->prepare('INSERT INTO settings VALUES (?, ?)');
    $rq->execute(array('project_categories', ''));
}

function version_19($pdo)
{
    $pdo->exec("
        CREATE TABLE swimlanes (
            id SERIAL PRIMARY KEY,
            name VARCHAR(200) NOT NULL,
            position INTEGER DEFAULT 1,
            is_active BOOLEAN DEFAULT '1',
            project_id INTEGER,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            UNIQUE (name, project_id)
        )
    ");

    $pdo->exec('ALTER TABLE tasks ADD COLUMN swimlane_id INTEGER DEFAULT 0');
    $pdo->exec("ALTER TABLE projects ADD COLUMN default_swimlane VARCHAR(200) DEFAULT 'Default swimlane'");
    $pdo->exec("ALTER TABLE projects ADD COLUMN show_default_swimlane BOOLEAN DEFAULT '1'");
}

function version_18($pdo)
{
    $pdo->exec("ALTER TABLE project_has_users ADD COLUMN is_owner BOOLEAN DEFAULT '0'");
}

function version_17($pdo)
{
    $pdo->exec('ALTER TABLE tasks ALTER COLUMN title SET NOT NULL');
}

function version_16($pdo)
{
    $pdo->exec("
        CREATE TABLE project_daily_summaries (
            id SERIAL PRIMARY KEY,
            day CHAR(10) NOT NULL,
            project_id INTEGER NOT NULL,
            column_id INTEGER NOT NULL,
            total INTEGER NOT NULL DEFAULT 0,
            FOREIGN KEY(column_id) REFERENCES columns(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
        )
    ");

    $pdo->exec('CREATE UNIQUE INDEX project_daily_column_stats_idx ON project_daily_summaries(day, project_id, column_id)');
}

function version_15($pdo)
{
    $pdo->exec("ALTER TABLE projects ADD COLUMN is_everybody_allowed BOOLEAN DEFAULT '0'");
}

function version_14($pdo)
{
    $pdo->exec("
        CREATE TABLE project_activities (
            id SERIAL PRIMARY KEY,
            date_creation INTEGER NOT NULL,
            event_name VARCHAR(50) NOT NULL,
            creator_id INTEGER,
            project_id INTEGER,
            task_id INTEGER,
            data TEXT,
            FOREIGN KEY(creator_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
        )
    ");

    $pdo->exec('DROP TABLE task_has_events');
    $pdo->exec('DROP TABLE comment_has_events');
    $pdo->exec('DROP TABLE subtask_has_events');
}

function version_13($pdo)
{
    $pdo->exec("ALTER TABLE tasks ADD COLUMN date_started INTEGER");
    $pdo->exec("ALTER TABLE tasks ADD COLUMN time_spent FLOAT DEFAULT 0");
    $pdo->exec("ALTER TABLE tasks ADD COLUMN time_estimated FLOAT DEFAULT 0");

    $pdo->exec("ALTER TABLE task_has_subtasks ALTER COLUMN time_estimated TYPE FLOAT");
    $pdo->exec("ALTER TABLE task_has_subtasks ALTER COLUMN time_spent TYPE FLOAT");
}

function version_12($pdo)
{
    $pdo->exec("ALTER TABLE projects ADD COLUMN is_private BOOLEAN DEFAULT '0'");
}

function version_11($pdo)
{
    $rq = $pdo->prepare('INSERT INTO settings VALUES (?, ?)');
    $rq->execute(array('application_date_format', 'm/d/Y'));
}

function version_10($pdo)
{
    $pdo->exec("
        CREATE TABLE settings (
            option VARCHAR(100) PRIMARY KEY,
            value VARCHAR(255) DEFAULT ''
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

function version_9($pdo)
{
    $pdo->exec("ALTER TABLE tasks ADD COLUMN reference VARCHAR(50) DEFAULT ''");
    $pdo->exec("ALTER TABLE comments ADD COLUMN reference VARCHAR(50) DEFAULT ''");

    $pdo->exec('CREATE INDEX tasks_reference_idx ON tasks(reference)');
    $pdo->exec('CREATE INDEX comments_reference_idx ON comments(reference)');
}

function version_8($pdo)
{
    $pdo->exec('CREATE UNIQUE INDEX users_username_idx ON users(username)');
}

function version_7($pdo)
{
    $pdo->exec("ALTER TABLE config ADD COLUMN default_columns VARCHAR(255) DEFAULT ''");
}

function version_6($pdo)
{
    $pdo->exec("
        CREATE TABLE task_has_events (
            id SERIAL PRIMARY KEY,
            date_creation INTEGER NOT NULL,
            event_name VARCHAR(50) NOT NULL,
            creator_id INTEGER,
            project_id INTEGER,
            task_id INTEGER,
            data TEXT,
            FOREIGN KEY(creator_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
        );
    ");

    $pdo->exec("
        CREATE TABLE subtask_has_events (
            id SERIAL PRIMARY KEY,
            date_creation INTEGER NOT NULL,
            event_name VARCHAR(50) NOT NULL,
            creator_id INTEGER,
            project_id INTEGER,
            subtask_id INTEGER,
            task_id INTEGER,
            data TEXT,
            FOREIGN KEY(creator_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(subtask_id) REFERENCES task_has_subtasks(id) ON DELETE CASCADE,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
        );
    ");

    $pdo->exec("
        CREATE TABLE comment_has_events (
            id SERIAL PRIMARY KEY,
            date_creation INTEGER NOT NULL,
            event_name VARCHAR(50) NOT NULL,
            creator_id INTEGER,
            project_id INTEGER,
            comment_id INTEGER,
            task_id INTEGER,
            data TEXT,
            FOREIGN KEY(creator_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(comment_id) REFERENCES comments(id) ON DELETE CASCADE,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
        );
    ");
}

function version_5($pdo)
{
    $pdo->exec("ALTER TABLE projects ADD COLUMN is_public BOOLEAN DEFAULT '0'");
}

function version_4($pdo)
{
    $pdo->exec("ALTER TABLE users ADD COLUMN notifications_enabled BOOLEAN DEFAULT '0'");

    $pdo->exec("
        CREATE TABLE user_has_notifications (
            user_id INTEGER,
            project_id INTEGER,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            UNIQUE(project_id, user_id)
        );
    ");
}

function version_3($pdo)
{
    $pdo->exec("ALTER TABLE config ADD COLUMN webhooks_url_task_modification VARCHAR(255)");
    $pdo->exec("ALTER TABLE config ADD COLUMN webhooks_url_task_creation VARCHAR(255)");
}

function version_2($pdo)
{
    $pdo->exec("ALTER TABLE tasks ADD COLUMN creator_id INTEGER DEFAULT 0");
    $pdo->exec("ALTER TABLE tasks ADD COLUMN date_modification INTEGER DEFAULT 0");
}

function version_1($pdo)
{
    $pdo->exec("
        CREATE TABLE config (
            language CHAR(5) DEFAULT 'en_US',
            webhooks_token VARCHAR(255) DEFAULT '',
            timezone VARCHAR(50) DEFAULT 'UTC',
            api_token VARCHAR(255) DEFAULT ''
        );

        CREATE TABLE users (
            id SERIAL PRIMARY KEY,
            username VARCHAR(50),
            password VARCHAR(255),
            is_admin BOOLEAN DEFAULT '0',
            default_project_id INTEGER DEFAULT 0,
            is_ldap_user BOOLEAN DEFAULT '0',
            name VARCHAR(255),
            email VARCHAR(255),
            google_id VARCHAR(255),
            github_id VARCHAR(30)
        );

        CREATE TABLE remember_me (
            id SERIAL PRIMARY KEY,
            user_id INTEGER,
            ip VARCHAR(40),
            user_agent VARCHAR(255),
            token VARCHAR(255),
            sequence VARCHAR(255),
            expiration INTEGER,
            date_creation INTEGER,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE TABLE last_logins (
            id SERIAL PRIMARY KEY,
            auth_type VARCHAR(25),
            user_id INTEGER,
            ip VARCHAR(40),
            user_agent VARCHAR(255),
            date_creation INTEGER,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE TABLE projects (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) UNIQUE,
            is_active BOOLEAN DEFAULT '1',
            token VARCHAR(255),
            last_modified INTEGER DEFAULT 0
        );

        CREATE TABLE project_has_users (
            id SERIAL PRIMARY KEY,
            project_id INTEGER,
            user_id INTEGER,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE(project_id, user_id)
        );

        CREATE TABLE project_has_categories (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255),
            project_id INTEGER,
            UNIQUE (project_id, name),
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
        );

        CREATE TABLE columns (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255),
            position INTEGER,
            project_id INTEGER,
            task_limit INTEGER DEFAULT 0,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            UNIQUE (title, project_id)
        );

        CREATE TABLE tasks (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255),
            description TEXT,
            date_creation INTEGER,
            color_id VARCHAR(255),
            project_id INTEGER,
            column_id INTEGER,
            owner_id INTEGER DEFAULT 0,
            position INTEGER,
            is_active BOOLEAN DEFAULT '1',
            date_completed INTEGER,
            score INTEGER,
            date_due INTEGER,
            category_id INTEGER DEFAULT 0,
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE,
            FOREIGN KEY(column_id) REFERENCES columns(id) ON DELETE CASCADE
        );

        CREATE TABLE task_has_subtasks (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255),
            status SMALLINT DEFAULT 0,
            time_estimated INTEGER DEFAULT 0,
            time_spent INTEGER DEFAULT 0,
            task_id INTEGER NOT NULL,
            user_id INTEGER,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
        );

        CREATE TABLE task_has_files (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255),
            path VARCHAR(255),
            is_image BOOLEAN DEFAULT '0',
            task_id INTEGER,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE
        );

        CREATE TABLE comments (
            id SERIAL PRIMARY KEY,
            task_id INTEGER,
            user_id INTEGER,
            date INTEGER,
            comment TEXT,
            FOREIGN KEY(task_id) REFERENCES tasks(id) ON DELETE CASCADE,
            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
        );

        CREATE TABLE actions (
            id SERIAL PRIMARY KEY,
            project_id INTEGER,
            event_name VARCHAR(50),
            action_name VARCHAR(50),
            FOREIGN KEY(project_id) REFERENCES projects(id) ON DELETE CASCADE
        );

        CREATE TABLE action_has_params (
            id SERIAL PRIMARY KEY,
            action_id INTEGER,
            name VARCHAR(50),
            value VARCHAR(50),
            FOREIGN KEY(action_id) REFERENCES actions(id) ON DELETE CASCADE
        );
    ");

    $pdo->exec("
        INSERT INTO users
        (username, password, is_admin)
        VALUES ('admin', '".\password_hash('admin', PASSWORD_BCRYPT)."', '1')
    ");

    $pdo->exec("
        INSERT INTO config
        (webhooks_token, api_token)
        VALUES ('".Security::generateToken()."', '".Security::generateToken()."')
    ");
}
