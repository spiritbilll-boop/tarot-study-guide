<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title></title>
  </head>
  <body>
    <h1>Tarot Study Guide CMS</h1>
    <p>Version: 0.2.0-dev</p>
    <h2>Overview</h2>
    <p>The Tarot Study Guide CMS is a web-based knowledge management system for
      the study of the 78 Tarot cards.</p>
    <p>It is designed to help students, teachers, researchers, and writers
      organize detailed study notes for every card in both Upright and Reversed
      orientations.</p>
    <p>Rather than functioning as a Tarot reading application, the project
      emphasizes structured knowledge, commentary, personal insights, source
      attribution, and long-term study.</p>
    <hr>
    <h2>Current Features</h2>
    <ul>
      <li>
        <p>Browse Tarot cards</p>
      </li>
      <li>
        <p>Card of the Day</p>
      </li>
      <li>
        <p>Random Card</p>
      </li>
      <li>
        <p>Study Notes Manager</p>
        <ul>
          <li>
            <p>Create Study Notes</p>
          </li>
          <li>
            <p>Edit Study Notes</p>
          </li>
          <li>
            <p>Enable/Disable Notes</p>
          </li>
          <li>
            <p>Sequence Notes</p>
          </li>
        </ul>
      </li>
      <li>
        <p>Git version control</p>
      </li>
      <li>
        <p>GitHub repository</p>
      </li>
      <li>
        <p>Feature branch workflow</p>
      </li>
    </ul>
    <hr>
    <h2>Planned Features</h2>
    <ul>
      <li>
        <p>Delete Study Notes</p>
      </li>
      <li>
        <p>Automatic resequencing</p>
      </li>
      <li>
        <p>Dashboard</p>
      </li>
      <li>
        <p>Search</p>
      </li>
      <li>
        <p>Card Browser</p>
      </li>
      <li>
        <p>Shared page templates</p>
      </li>
      <li>
        <p>Statistics</p>
      </li>
      <li>
        <p>Authentication</p>
      </li>
      <li>
        <p>Export facilities</p>
      </li>
    </ul>
    <hr>
    <h2>Technology</h2>
    <ul>
      <li>
        <p>PHP 8</p>
      </li>
      <li>
        <p>MariaDB</p>
      </li>
      <li>
        <p>Apache</p>
      </li>
      <li>
        <p>HTML5</p>
      </li>
      <li>
        <p>CSS3</p>
      </li>
      <li>
        <p>JavaScript</p>
      </li>
      <li>
        <p>Git</p>
      </li>
      <li>
        <p>GitHub</p>
      </li>
    </ul>
    <p>The project is developed on Debian Linux using the Apache web server and
      MariaDB.</p>
    <hr>
    <h2>Project Structure</h2>
    <pre><code>cards/
css/
documents/
includes/
js/
sql/
study_notes/

README.md
PROJECT_NOTES.md
TODO.md
VERSION
database.php
</code></pre>
    <hr>
    <h2>Documentation</h2>
    <p>Project documentation is located in the <code>documents</code>
      directory.</p>
    <h3>architecture</h3>
    <p>Permanent technical documentation.</p>
    <h3>design</h3>
    <p>Future planning and feature design.</p>
    <h3>meetings</h3>
    <p>Engineering decisions and design discussions.</p>
    <h3>releases</h3>
    <p>Release notes and change history.</p>
    <h3>screenshots</h3>
    <p>Images of completed features.</p>
    <hr>
    <h2>Development Workflow</h2>
    <p>Development follows a simple Git workflow.</p>
    <pre><code>main
    ↑
develop
    ↑
feature/*
</code></pre>
    <p>Features are developed on feature branches, tested, merged into <code>develop</code>,
      and eventually released through <code>main</code>.</p>
    <hr>
    <h2>Coding Standards</h2>
    <p>The project favors:</p>
    <ul>
      <li>
        <p>readable code over clever code</p>
      </li>
      <li>
        <p>descriptive function names</p>
      </li>
      <li>
        <p>prepared SQL statements</p>
      </li>
      <li>
        <p>modular design</p>
      </li>
      <li>
        <p>separation of presentation, controller logic, and database logic</p>
      </li>
      <li>
        <p>incremental Git commits</p>
      </li>
      <li>
        <p>thorough testing after every feature</p>
      </li>
    </ul>
    <hr>
    <h2>Project Goals</h2>
    <p>The long-term goal is to build a comprehensive Tarot Knowledge Management
      System suitable for serious personal study, research, and writing.</p>
    <p>The emphasis is on preserving knowledge rather than generating readings.</p>
    <hr>
    <h2>Current Status</h2>
    <p>Active development.</p>
    <p>Current milestone:</p>
    <p><strong>Version 0.2.0-dev</strong></p>
    <p></p>
  </body>
</html>
