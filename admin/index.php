<?php

$page_title = "Tarot Study Guide Administration";

require_once("common/header.php");

?>

<h2>Content Management</h2>

<p>
<a href="<?= ADMIN_URL ?>/study_notes/editor.php">
Study Notes Editor
</a>
</p>

<p>
<a href="<?= ADMIN_URL ?>/entity_topics/index.php">
Entity Topics Manager
</a>
</p>

<p>
<a href="<?= ADMIN_URL ?>/topic_editor/index.php">
Topic Editor
</a>
</p>

<p>
<a href="<?= ADMIN_URL ?>/domain_editor/">
Domain Editor
</a>
</p>

<h2>Reports &amp; Statistics</h2>

<p>
<a href="<?= ADMIN_URL ?>/reports/">
Reports &amp; Statistics
</a>
</p>

<p>
<a href="<?= APP_ROOT ?>/knowledge_graph/topics_by_card.php">
Topics by Card
</a>
</p>

<p>
<a href="<?= APP_ROOT ?>/knowledge_graph/cards_by_topic.php">
Cards by Topic
</a>
</p>

<h2>System</h2>

<p>User Management (coming soon)</p>

<p>Configuration (coming soon)</p>

<?php

require_once("common/footer.php");

?>
