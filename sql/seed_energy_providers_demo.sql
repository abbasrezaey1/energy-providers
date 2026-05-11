-- ============================================================================
-- Demo / editorial seed data for Energy Providers Index
-- Run AFTER: sql/migrate_submissions_energy_columns.sql (if columns were missing)
--
-- 1. Edit SET @site_web_id if your install uses a different web_id (must match
--    PHP: empty BASE_PATH uses HTTP_HOST, e.g. providers.asindex.org).
-- 2. Backup your DB before running.
-- 3. Article `image` column = filename only under /img/ (see lib/article_helpers.php).
--    Bundled names match common files in your logo library (UK filenames used as thumbnails
--    where no DE-specific asset exists). Rename files on disk if your spelling differs.
-- 4. If you still see collation errors, run: SHOW FULL COLUMNS FROM web_settings
--    LIKE 'web_id'; — then use that column collation in SET NAMES / COLLATE(...) below.
-- ============================================================================

-- Match table collation (avoids #1267 Illegal mix of collations with phpMyAdmin defaults)
SET NAMES utf8mb4 COLLATE utf8mb4_0900_ai_ci;

SET @site_web_id = 'providers.asindex.org';
SET @editor_user_id = 18;

-- ---------------------------------------------------------------------------
-- Site-wide contact (legacy columns on web_settings)
-- Adjust WHERE if your row uses another web_id (e.g. imported dump still says abc-cbd.fr).
-- ---------------------------------------------------------------------------
UPDATE `web_settings`
SET
  `name` = 'Energy Providers Index',
  `about` = 'Editorial listings of electricity suppliers with indicative generation mix figures and environmental indicators. Profiles are for general information; contract terms vary by product and region.',
  `contact` = CONCAT(
    'AS Index editorial office<br>',
    'Fuhrmannsweg 2<br>',
    '07607 Eisenberg<br>',
    'Germany<br><br>',
    'Email: <a href="mailto:info@asindex.org">info@asindex.org</a><br>'
  )
WHERE `web_id` = (@site_web_id COLLATE utf8mb4_0900_ai_ci)
LIMIT 1;

-- If 0 rows updated (imported dump still has another web_id), run once by hand, e.g.:
-- UPDATE `web_settings` SET `name`='Energy Providers Index', `contact`='...' WHERE `web_no` = 1 LIMIT 1;

-- Optional: separate email/address columns if you added them via admin migrations
-- (uncomment and run only if these columns exist)
-- UPDATE `web_settings` SET `email` = 'info@asindex.org', `address` = 'Fuhrmannsweg 2, 07607 Eisenberg, Germany' WHERE `web_id` = (@site_web_id COLLATE utf8mb4_0900_ai_ci) LIMIT 1;

-- ---------------------------------------------------------------------------
-- Remove previous seed rows for this site (safe re-import). Comment out to append.
-- ---------------------------------------------------------------------------
DELETE FROM `submissions`
WHERE `web_id` = (@site_web_id COLLATE utf8mb4_0900_ai_ci)
  AND `submission_id` BETWEEN 10001 AND 10024;

-- ---------------------------------------------------------------------------
-- Provider rows: real company names + publicly known headquarters addresses.
-- Mix values are rounded, illustrative (not live tariffs). IEPN numbers are fictional editorial IDs.
-- ---------------------------------------------------------------------------
INSERT INTO `submissions` (
  `submission_id`, `user_id`, `web_id`, `url`, `title`, `content`,
  `keywords`, `metadescription`,
  `related_links_1`, `related_links_text_1`, `related_links_2`, `related_links_text_2`, `related_links_3`, `related_links_text_3`,
  `submitted`, `rejected`, `published`,
  `scan_id`, `scan_status`, `is_duplicated`, `duplicate_percentage`, `similar_results`, `used_credits`,
  `idd`, `score`, `business_activeness`, `date`, `category`, `image_url`, `image`, `website`, `tel`, `address`,
  `co2`, `nuclear_waste`, `coal`, `gas`, `nuclear`, `renewable`, `iepn`
) VALUES
(10001, @editor_user_id, @site_web_id, 'E.ON-SE', 'E.ON SE',
 'Large integrated energy company serving millions of customers in Germany and neighbouring markets. Generation and retail portfolios span renewables, gas-fired capacity, and distribution networks.',
 'E.ON, electricity, Germany', 'Profile of E.ON SE — retail electricity and energy networks in Germany.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10001, 78.50, 82, '2026-05-01', 'incumbent', '', 'E-ON-UK.png', 'www.eon.com', '+49 201 184 0000',
 'Brüsseler Platz 1, 45131 Essen, Germany',
 '380', '0.002', '15', '35', '12', '36', 'IEPN-DE-2024-0101'),

(10002, @editor_user_id, @site_web_id, 'RWE-AG', 'RWE AG',
 'German-headquartered electricity generator with growing renewables fleet and conventional flexibility assets; supplies industrial and household customers through various brands.',
 'RWE, power generation, Germany', 'Profile of RWE AG — generation portfolio and customer supply.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10002, 74.00, 79, '2026-05-01', 'incumbent', '', 'Scottish-Power.png', 'www.group.rwe.com', '+49 201 5179-0',
 'Opernplatz 1, 45128 Essen, Germany',
 '410', '0.004', '22', '28', '18', '28', 'IEPN-DE-2024-0102'),

(10003, @editor_user_id, @site_web_id, 'EnBW-AG', 'EnBW Energie Baden-Württemberg AG',
 'Regional supplier rooted in Baden-Württemberg with substantial hydropower history and ongoing investments in wind and solar alongside grid operations.',
 'EnBW, Baden-Württemberg, Strom', 'Profile of EnBW — south-west Germany electricity supply.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10003, 81.20, 88, '2026-05-02', 'regional', '', 'Engie.png', 'www.enbw.com', '+49 721 63-00',
 'Durlacher Allee 93, 76131 Karlsruhe, Germany',
 '320', '0.001', '8', '30', '14', '44', 'IEPN-DE-2024-0103'),

(10004, @editor_user_id, @site_web_id, 'Vattenfall-Europe', 'Vattenfall GmbH',
 'Swedish-owned utility active in northern Germany with offshore wind focus and district heating; retail brands serve household and business customers.',
 'Vattenfall, Hamburg, offshore wind', 'Profile of Vattenfall Germany operations.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10004, 76.30, 75, '2026-05-02', 'incumbent', '', 'Shell-Energy.png', 'corporate.vattenfall.de', '+49 40 65325-0',
 'Überseering 12, 22297 Hamburg, Germany',
 '340', '0.002', '12', '25', '10', '48', 'IEPN-DE-2024-0104'),

(10005, @editor_user_id, @site_web_id, 'Stadtwerke-Muenchen', 'Stadtwerke München GmbH',
 'Municipal utility for Munich: electricity, gas, heat and mobility services with substantial local renewables and efficiency programmes.',
 'Stadtwerke München, SWM, municipal', 'Profile of Stadtwerke München.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10005, 88.00, 91, '2026-05-03', 'municipal', '', 'Together-Energy.png', 'www.swm.de', '+49 89 2361-0',
 'Emmy-Noether-Straße 2, 80992 München, Germany',
 '290', '0.001', '5', '28', '10', '54', 'IEPN-DE-2024-0105'),

(10006, @editor_user_id, @site_web_id, 'Stadtwerke-Berlin', 'Berliner Stadtwerke GmbH',
 'City-affiliated supplier offering electricity and heat with emphasis on Berlin grid integration and customer tariffs tied to municipal climate targets.',
 'Berliner Stadtwerke, Berlin Strom', 'Profile of Berliner Stadtwerke municipal supply.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10006, 84.50, 86, '2026-05-03', 'municipal', '', 'Bristol-Energy.png', 'www.berliner-stadtwerke.com', '+49 30 7878780',
 'Alexanderstraße 2, 10178 Berlin, Germany',
 '310', '0.002', '18', '32', '8', '38', 'IEPN-DE-2024-0106'),

(10007, @editor_user_id, @site_web_id, 'Mainova', 'Mainova AG',
 'Frankfurt-area municipal-linked supplier providing electricity, gas and heat with urban infrastructure projects and green tariff options.',
 'Mainova, Frankfurt, utility', 'Profile of Mainova AG.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10007, 79.40, 84, '2026-05-04', 'regional', '', 'First-Utility.png', 'www.mainova.de', '+49 69 213-0',
 'Solmsstraße 26, 60486 Frankfurt am Main, Germany',
 '330', '0.002', '14', '34', '11', '37', 'IEPN-DE-2024-0107'),

(10008, @editor_user_id, @site_web_id, 'LEW-Lechwerke', 'Lechwerke AG',
 'Regional supplier in Bavaria / Swabia named after the Lech river system; mix includes regional hydro and wind PPAs.',
 'Lechwerke, Bayern, Strom', 'Profile of Lechwerke AG.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10008, 82.70, 80, '2026-05-04', 'regional', '', 'npower.png', 'www.lew.de', '+49 821 9068-0',
 'Zugspitzstraße 15, 86165 Augsburg, Germany',
 '300', '0.001', '10', '29', '13', '45', 'IEPN-DE-2024-0108'),

(10009, @editor_user_id, @site_web_id, 'LichtBlick', 'LichtBlick SE',
 'Hamburg-based green-energy retailer focused on renewable tariffs and sector coupling concepts for households and SMEs.',
 'LichtBlick, Ökostrom, Hamburg', 'Profile of LichtBlick renewable retail supply.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10009, 90.50, 92, '2026-05-05', 'green retail', '', 'Bulb-Energy.png', 'www.lichtblick.de', '+49 40 432199-0',
 'Heidenkampsweg 81, 20097 Hamburg, Germany',
 '220', '0.000', '0', '15', '0', '82', 'IEPN-DE-2024-0109'),

(10010, @editor_user_id, @site_web_id, 'Green-Planet-Energy', 'Green Planet Energy eG',
 'Hamburg-based renewable electricity cooperative (successor brand to Greenpeace Energy). Customer tariffs emphasise new renewable build-out.',
 'Green Planet Energy, cooperative, Ökostrom, Hamburg', 'Profile of Green Planet Energy eG.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10010, 91.00, 89, '2026-05-05', 'green retail', '', 'Ecotricity.png', 'www.green-planet-energy.de', '+49 40 808110-600',
 'Hongkongstraße 10, 20457 Hamburg, Germany',
 '210', '0.000', '0', '10', '0', '88', 'IEPN-DE-2024-0110'),

(10011, @editor_user_id, @site_web_id, 'Naturstrom', 'Naturstrom AG',
 'Düsseldorf-based company offering renewable electricity products with emphasis on traceability and customer transparency.',
 'Naturstrom, renewables, Düsseldorf', 'Profile of Naturstrom AG.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10011, 87.25, 87, '2026-05-06', 'green retail', '', 'Pure-Planet.png', 'www.naturstrom.de', '+49 211 77900-100',
 'Parsevalstraße 11, 40468 Düsseldorf, Germany',
 '230', '0.000', '0', '12', '0', '85', 'IEPN-DE-2024-0111'),

(10012, @editor_user_id, @site_web_id, 'Octopus-Energy-DE', 'Octopus Energy Germany',
 'UK-founded retailer operating in Germany with digital-first customer service and dynamic tariff experiments in eligible regions.',
 'Octopus Energy, Germany, electricity', 'Profile of Octopus Energy in Germany.',
 '', '', '', '', '', '',
 1, 0, 1,
 '', 'skipped', '', '', '', '1',
 10012, 85.00, 90, '2026-05-06', 'green retail', '', 'Octopus-Energy.png', 'octopusenergy.de', '+49 800 0006628',
 'Wilhelmstraße 118, 10963 Berlin, Germany',
 '250', '0.000', '0', '18', '0', '75', 'IEPN-DE-2024-0112');

-- Keep idd aligned for routing / comments
UPDATE `submissions` SET `idd` = `submission_id` WHERE `submission_id` BETWEEN 10001 AND 10024;
