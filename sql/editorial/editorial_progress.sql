--
-- editorial_progress.sql
--
-- Purpose:
--     Show the editorial progress for every Tarot card.
--
-- Interpretation:
--     orientations = 0, notes = 0  Untouched
--     orientations = 1, notes = 1  Started
--     orientations = 2, notes = 2  Nearly Complete
--     orientations = 2, notes = 3  Complete
--
SELECT
  c.card_name,
  COUNT(DISTINCT n.orientation) AS orientations,
  COUNT(n.id) AS notes
FROM tarot_cards c
LEFT JOIN tarot_card_notes n
ON c.id = n.card_id
GROUP BY
c.id,
c.card_name
ORDER BY
orientations DESC,
notes DESC,
c.card_name;
