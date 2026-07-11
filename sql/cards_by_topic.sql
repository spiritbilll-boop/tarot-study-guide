-- ==========================================================
-- cards_by_topic.sql
--
-- Purpose:
--     List every tarot card associated with a specified topic.
--     Useful for exploring the knowledge graph from the topic
--     perspective.
--
-- Inputs:
--     t.name = Topic name
--
-- Returns:
--     One row for each tarot card associated with the
--     specified topic, including:
--         - Card ID
--         - Card name
--         - Topic name
--
-- Example Output:
--     card_id  card_name             topic
--     -------  --------------------  ----------
--     59       The High Priestess    Moon
--     75       The Moon              Moon
--
--     Results are ordered by card ID.
-- Notes:
--     Joins the topics, entity_topics,
--     and tarot_cards tables.
--     Intended for exploring the knowledge graph
--     from the topic perspective.
--     Results are ordered by card ID.
-- ==========================================================
SELECT
    tc.id      AS card_id,
    tc.card_name,
    t.id       AS topic_id,
    t.name     AS topic
FROM topics t
JOIN entity_topics et
    ON et.topic_id = t.id
JOIN tarot_cards tc
    ON tc.id = et.entity_id
WHERE t.name = 'Moon'
ORDER BY tc.id;
