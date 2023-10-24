USE default;
DROP TABLE IF EXISTS `requests`;
CREATE TABLE `requests` (
    `id` UUID NOT NULL,
    `create_at` DateTime() NOT NULL,
    `length` Int64 NOT NULL,
    `url` String NOT NULL
) ENGINE=TinyLog;
