-- ==========================================================
-- entity_topics_report.sql
--
-- Purpose:
--     Display every entity-to-topic relationship currently
--     stored in the knowledge graph.
--     Useful for verifying data entry and auditing the
--     current state of the Entity Topics table.
--
-- Inputs:
--     None.
--
-- Returns:
--     One row for each entity-topic relationship,
--     including:
--         - Card ID
--         - Card name
--         - Domain
--         - Topic
--
-- Example Output:
--     card_id  card_name          domain        topic
--     -------  -----------------  ------------  ----------------
--     59       The High Priestess Astrology     Moon
--     59       The High Priestess Astrology     Water
--     59       The High Priestess Hermeticism   Emerald Tablet
--
-- Notes:
--     Joins entity_topics, tarot_cards, topics,
--     and domains.
--     Results are ordered by card ID, then domain,
--     then topic.
--     Useful for reviewing, validating, and cleaning
--     the knowledge graph before and during data entry.
-- ==========================================================
SELECT
    tc.id          AS card_id,
    tc.card_name,
    d.name         AS domain,
    t.name         AS topic
FROM entity_topics et
JOIN tarot_cards tc
    ON tc.id = et.entity_id
JOIN topics t
    ON t.id = et.topic_id
LEFT JOIN domains d
    ON d.id = t.domain_id
ORDER BY
    tc.id,
    d.name,
    t.name;
