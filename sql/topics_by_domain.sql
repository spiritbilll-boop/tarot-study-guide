-- ==========================================================
-- topics_by_domain.sql
--
-- Purpose:
--     List every enabled topic together with its domain.
--     Useful for verifying the domains/topics hierarchy and
--     providing lookup data for application drop-down lists.
--
-- Inputs:
--     None.
--
-- Returns:
--     One row for each enabled topic, including:
--         - Domain ID
--         - Domain name
--         - Topic ID
--         - Topic name
--         - Topic display order
--
-- Example Output:
--     domain_id  domain         topic
--     ---------  -------------  ---------------------
--     1          Astrology      Moon
--     2          Alchemy        Nigredo
--     3          Hermeticism    Emerald Tablet
--
-- Notes:
--     Joins the domains and topics tables.
--     Returns only enabled domains and enabled topics.
--     Intended as the primary lookup query for topic selection
--     lists and administrative reports.
--     Results are ordered by domain display_order,
--     topic display_order, and topic name.
-- ==========================================================
SELECT
    d.id             AS domain_id,
    d.name           AS domain,
    d.display_order  AS domain_display_order,
    t.id             AS topic_id,
    t.name           AS topic,
    t.display_order  AS topic_display_order
FROM domains d
JOIN topics t
    ON t.domain_id = d.id
WHERE d.enabled = 1
  AND t.enabled = 1
ORDER BY
    d.display_order,
    t.display_order,
    t.name;
