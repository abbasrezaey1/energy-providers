-- Remove old stock-import demo articles (Lorem Ipsum, "Why do we use it?", etc. from `abc-cbd.fr`).
-- IDs may match rows from older demo dumps. Backup your DB first.
-- If your dummy rows use other IDs, run: SELECT submission_id, title, web_id FROM submissions WHERE title LIKE '%Lorem%';

SET NAMES utf8mb4;

DELETE FROM `comments` WHERE `submission_id` IN (109, 111, 112, 113, 114, 115, 116);

DELETE FROM `submissions` WHERE `submission_id` IN (109, 111, 112, 113, 114, 115, 116);
