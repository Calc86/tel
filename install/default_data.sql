SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

INSERT INTO `org` (`id`, `name`, `login`, `passwd`, `hash`, `money`, `fullname`) VALUES (0, 'Свободные пиры', '', '', '', 0, '');

--
-- Дамп данных таблицы `dtypes`
--

INSERT INTO `dtypes` (`id`, `name`, `cost`) VALUES
(1, 'AddPac AP190', 0),
(2, 'Linksys PAP2T', 2250),
(3, 'AddPac AP200', 0),
(4, 'Linksys SPA2102', 0);

INSERT INTO `pt` (`id`, `name`) VALUES
(1, 'msm'),
(2, 'mcn'),
(3, 'interlan'),
(4, 'mx_tel'),
(0, 'test'),
(6, 'csv'),
(7, 'degunino');
