-- ==========================================================
-- topics_by_card.sql
--
-- Purpose:
--     List every topic associated with a specified tarot card.
--     Useful for exploring the knowledge graph from the card
--     perspective.
--
-- Inputs:
--     tc.id = Tarot card ID
--
-- Returns:
--     One row for each topic associated with the specified
--     tarot card, including:
--         - Card ID
--         - Card name
--         - Domain
--         - Topic ID
--         - Topic name
--
-- Example Output:
--     card_id  card_name          domain        topic_id  topic
--     -------  -----------------  ------------  --------  -------------
--     59       The High Priestess Astrology            2  Moon
--     59       The High Priestess Astrology           23  Water
--     59       The High Priestess Hermeticism         32  Emerald Tablet
--
-- Notes:
--     Joins tarot_cards, entity_topics, topics,
--     and domains.
--     Results are ordered by domain and topic.
--     Intended as the inverse of cards_by_topic.sql.
-- ==========================================================

SELECT
    tc.id      AS card_id,
    tc.card_name,
    d.name     AS domain,
    t.id       AS topic_id,
    t.name     AS topic
FROM tarot_cards tc
JOIN entity_topics et
    ON et.entity_id = tc.id
JOIN topics t
    ON t.id = et.topic_id
LEFT JOIN domains d
    ON d.id = t.domain_id
WHERE tc.id = 1  -- Change to any card ID while testing
ORDER BY
    d.name,
    t.name;
