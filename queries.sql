USE yeticave;

INSERT INTO category (name)
VALUES
  ('Доски и лыжи'),
  ('Крепления'),
  ('Ботинки'),
  ('Одежда'),
  ('Инструменты'),
  ('Разное');

INSERT INTO user (registeration_date, email, name, password)
VALUES
  (SUBDATE(now(), INTERVAL 1 DAY), 'ignat.v@gmail.com', 'Игнат', '$2y$10$OqvsKHQwr0Wk6FMZDoHo1uHoXd4UdxJG/5UDtUiie00XaxMHrW8ka'),
  (SUBDATE(now(), INTERVAL "7 6:11" DAY_MINUTE), 'kitty_93@li.ru', 'Леночка', '$2y$10$bWtSjUhwgggtxrnJ7rxmIe63ABubHQs0AS0hgnOo41IEdMHkYoSVa'),
  (SUBDATE(now(), INTERVAL "4 2:32" DAY_MINUTE), 'warrior07@mail.ru', 'Руслан', '$2y$10$2OxpEH7narYpkOT1H5cApezuzh10tZEEQ2axgFOaKW.55LxIJBgWW');

INSERT INTO lot (date_start, date_expires, name, description, image, price_start, bet_step, likes, author_id, category_id)
VALUES
  (now(), ADDDATE(now(), INTERVAL 1 DAY),
  '2014 Rossignol District Snowboard',
  'Легкий маневренный сноуборд, готовый дать жару в любом парке, растопив снег мощным щелчкоми
  четкими дугами. Стекловолокно Bi-Ax, уложенное в двух направлениях, наделяет этот снаряд
  отличной гибкостью и отзывчивостью  , а симметричная геометрия в сочетании с классическим
  прогибом кэмбер позволит уверенно держать высокие скорости. А если к концу катального дня
  сил совсем не останется, просто посмотрите на Вашу доску и улыбнитесь, крутая графика от
  Шона Кливера еще никого не оставляла равнодушным.',
  'img/lot-1.jpg',
  10999, 300,
  0, 1, 1),

  (now(), ADDDATE(now(), INTERVAL 1 DAY),
  'DC Ply Mens 2016/2017 Snowboard',
  'Нет описания',
  'img/lot-2.jpg',
  159999, 500,
  0, 2, 1),

  (now(), ADDDATE(now(), INTERVAL 1 DAY),
  'Крепления Union Contact Pro 2015 года размер L/XL',
  'Нет описания',
  'img/lot-3.jpg',
  8000, 200,
  0, 3, 2),

  (now(), ADDDATE(now(), INTERVAL 1 DAY),
  'Ботинки для сноуборда DC Mutiny Charocal',
  'Нет описания',
  'img/lot-4.jpg',
  10999, 300,
  0, 1, 3),

  ('2017-09-13 09:00:00', ADDDATE('2017-09-13 09:00:00', INTERVAL 1 DAY),
  'Куртка для сноуборда DC Mutiny Charocal',
  'Нет описания',
  'img/lot-5.jpg',
  10999, 300,
  0, 1, 4),

  (now(), ADDDATE(now(), INTERVAL 1 DAY),
  'Маска Oakley Canopy',
  'Нет описания',
  'img/lot-6.jpg',
  5400, 200,
  0, 1, 6);

INSERT INTO bet (date, price, lot_id, user_id)
VALUES
  (ADDDATE(now(), INTERVAL '0 1:11' DAY_MINUTE), 11999, 1, 2),
  (ADDDATE(now(), INTERVAL '0 1:12' DAY_MINUTE), 13999, 1, 2),
  (ADDDATE(now(), INTERVAL '0 0:11' DAY_MINUTE), 8500, 3, 1);

-- Получить список из всех категорий
SELECT name FROM category;

-- Получить самые новые, открытые лоты. Каждый лот должен включать
-- название, стартовую цену, ссылку на изображение, цену, количество ставок, название категории;
SELECT
  name,
  price_start,
  image,
  IFNULL(MAX(bet.price), lot.price_start) AS curr_price,
  count(bet.lot_id) as bets_num,
  category_id
FROM lot
LEFT JOIN bet ON bet.lot_id = lot.id
WHERE date_expires > now()
GROUP BY lot.id
ORDER BY date_start DESC;

-- Найти лот по его названию или описанию;
SELECT * FROM lot
WHERE name LIKE 'query'
OR description LIKE 'query';

-- Обновить название лота по его идентификатору;
UPDATE lot SET name = 'New name'
WHERE id = 4;

-- Получить список самых свежих ставок для лота по его идентификатору;
SELECT * FROM bet WHERE lot_id = 3 ORDER BY id DESC;
