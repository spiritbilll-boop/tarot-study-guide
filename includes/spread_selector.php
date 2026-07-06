<?php
/*
 * Version 2
 * Tarot Study Guide
 * Spread selector component
 */
?>

<center>

<form method="POST" action="">

    <label for="question">
        Focus your mind and enter your question:
    </label>

    <input
        type="text"
        id="question"
        name="question"
        value="<?php echo htmlspecialchars($question); ?>"
        required
        placeholder="e.g., What does my career path look like this month?">

    <label for="spread_type">
        Choose Spread:
    </label>

    <select
        id="spread_type"
        name="spread_type">

<?php foreach ($spreads as $key => $info): ?>

        <option
            value="<?php echo $key; ?>"
            <?php echo ($selected_spread === $key) ? 'selected' : ''; ?>>

            <?php echo $info['label']; ?>

        </option>

<?php endforeach; ?>

    </select>

    <button
        type="submit"
        name="draw">

        Draw Deck

    </button>

</form>

</center>
