<nav class="nav">
  <ul class="nav__list container">
  <?php foreach($categories as $category): ?>
    <li class="nav__item">
      <a href="all-lots.html"><?= htmlspecialchars($category); ?></a>
    </li>
  <?php endforeach; ?>
  </ul>
</nav>

<section class="lot-item container">
    <h2><?=$lot['name']?></h2>

    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['image']; ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= $categories[$lot['category']]; ?></span></p>
            <p class="lot-item__description">
                <?= htmlspecialchars($lot['description']); ?>
            </p>
        </div>
        <div class="lot-item__right">
            <div class="lot-item__state">
                <div class="lot-item__timer timer">
                    10:54:12
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?= $lot['price']; ?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span>12 000 р</span>
                    </div>
                </div>
                <?php if ($is_auth): ?>
                    <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
                        <p class="lot-item__form-item">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="number" name="cost" placeholder="12 000">
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                <?php endif; ?>
                </div>
            <div class="history">
                <h3>История ставок (<span>4</span>)</h3>
                <!-- заполните эту таблицу данными из массива $bets-->
                <table class="history__list">
                <?php foreach($bets as $bet): ?>
                    <tr class="history__item">
                        <td class="history__name"><?= htmlspecialchars($bet['name']); ?></td>
                        <td class="history__price"><?= htmlspecialchars($bet['price']); ?> ₽</td>
                        <td class="history__time"><?= formatTime($bet['ts']);  ?></td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>

