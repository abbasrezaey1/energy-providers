-- Point seeded rows at logo files in /img/ (filename only). Run after seed import or to replace remote URLs.
-- Filenames must match your server exactly (case-sensitive on Linux).

UPDATE `submissions` SET `image` = 'E-ON-UK.png' WHERE `submission_id` = 10001;
UPDATE `submissions` SET `image` = 'Scottish-Power.png' WHERE `submission_id` = 10002;
UPDATE `submissions` SET `image` = 'Engie.png' WHERE `submission_id` = 10003;
UPDATE `submissions` SET `image` = 'Shell-Energy.png' WHERE `submission_id` = 10004;
UPDATE `submissions` SET `image` = 'Together-Energy.png' WHERE `submission_id` = 10005;
UPDATE `submissions` SET `image` = 'Bristol-Energy.png' WHERE `submission_id` = 10006;
UPDATE `submissions` SET `image` = 'First-Utility.png' WHERE `submission_id` = 10007;
UPDATE `submissions` SET `image` = 'npower.png' WHERE `submission_id` = 10008;
UPDATE `submissions` SET `image` = 'Bulb-Energy.png' WHERE `submission_id` = 10009;
UPDATE `submissions` SET `image` = 'Ecotricity.png' WHERE `submission_id` = 10010;
UPDATE `submissions` SET `image` = 'Pure-Planet.png' WHERE `submission_id` = 10011;
UPDATE `submissions` SET `image` = 'Octopus-Energy.png' WHERE `submission_id` = 10012;
